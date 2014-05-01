package edu.umd.mith.ppa

import edu.umd.mith.hathi._
import scala.slick.driver.JdbcDriver.backend.Database, Database.dynamicSession
import scala.slick.jdbc.{ GetResult, StaticQuery => Q }

trait Itemizer extends DatabaseUtils { this: PpaProcessor =>
  def deleteAllItems(): Unit = {
    Database.forURL(url, driver = "com.mysql.jdbc.Driver") withDynSession {
      Q.updateNA("DELETE FROM biblio_ht_item").execute()
      Q.updateNA("DELETE FROM url_alias WHERE alias LIKE 'record/%'").execute()
      Q.updateNA("DELETE FROM taxonomy_index").execute()
      Q.updateNA("DELETE from field_data_field_collection").execute()
    }
  }

  def addItemsToDatabase(): Unit = {
    val getNid = Q.query[String, Int](
      "SELECT nid FROM biblio WHERE biblio_url = ?"
    )

    val getItem = Q.query[(Int, String), Int](
      "SELECT COUNT(*) FROM biblio_ht_item WHERE nid = ? AND htid = ?"
    )

    val addItem = Q.update[(Int, Option[String], String)](
      "INSERT INTO biblio_ht_item (nid, enumcron, htid) VALUES (?, ?, ?)"
    )

    val getAlias = Q.query[String, Int](
      "SELECT COUNT(*) FROM url_alias WHERE source = ?"
    )

    val addAlias = Q.update[(String, String, String)](
      "INSERT INTO url_alias (source, alias, language) VALUES (?, ?, ?)"
    )

    val addTid = Q.update[(Int, Int, Long)](
      "INSERT INTO taxonomy_index (nid, tid, created) VALUES (?, ?, ?)"
    )

    val addTidField = Q.update[(Int, Int, Int, Int)](
      """
      INSERT INTO field_data_field_collection
        (entity_type, bundle, entity_id, revision_id, language, delta, field_collection_tid)
        VALUES ('node', 'biblio', ?, ?, 'und', ?, ?)
      """
    )

    Database.forURL(url, driver = "com.mysql.jdbc.Driver") withDynSession {
      getRecordsMetadata.foreach {
        case (record, idxs, volumes) =>
          getNid.firstOption(record.url.toString).fold(
            throw new Exception("Missing record: " + record.id)
          ) { nid =>
            if (getAlias.firstOption("node/%d".format(nid)).getOrElse(0) == 0) {
              addAlias.execute("node/%d".format(nid), "record/%s".format(record.id.id), "und")
            }

            idxs.zipWithIndex.foreach {
              case (idx, i) =>
                addTid.execute((nid, collectionTermIds(idx), System.currentTimeMillis / 1000))
                addTidField.execute((nid, nid, i, collectionTermIds(idx)))
            }

            volumes.sortBy(volume => (volume.enumcron, volume.htid)).foreach { volume =>
              if (getItem.firstOption(nid, volume.htid.toString).getOrElse(0) == 0) {
                addItem.execute((nid, volume.enumcron, volume.htid.toString))
              }
            }
          }
      }
    }
  }
}
