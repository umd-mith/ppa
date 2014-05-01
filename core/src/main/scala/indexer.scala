package edu.umd.mith.ppa

import edu.umd.mith.hathi._
import scala.slick.driver.JdbcDriver.backend.Database, Database.dynamicSession
import scala.slick.jdbc.{ GetResult, StaticQuery => Q }

import org.apache.solr.client.solrj._
import org.apache.solr.client.solrj.embedded.EmbeddedSolrServer
import org.apache.solr.client.solrj.impl.HttpSolrServer
import org.apache.solr.common.SolrInputDocument
import org.apache.solr.core.CoreContainer
import scala.collection.JavaConverters._

trait Indexer extends DatabaseUtils { this: PpaProcessor =>
  val solrServer = new HttpSolrServer("http://localhost:8080/solr/volumes")

  case class HItem(
    nid: Int,
    tid: List[Int],
    htid: String,
    record: String,
    enumcron: Option[String],
    title: Option[String],
    year: Int,
    authors: List[String],
    publisher: Option[String],
    city: Option[String],
    keywords: List[String]
  )

  val itemQuery = Q.query[String, (Int, Option[String], Int)](
    """
    SELECT biblio_ht_item.nid, biblio_ht_item.enumcron, taxonomy_index.tid
      FROM biblio_ht_item
      JOIN taxonomy_index ON taxonomy_index.nid = biblio_ht_item.nid WHERE htid = ?
    """
  )

  val recordQuery = Q.query[
    Int,
    (Option[String], Int, Option[String], Option[String], Option[String])
  ](
    """
    SELECT b.biblio_full_title, b.biblio_year, b.biblio_publisher, b.biblio_place_published, b.biblio_url
      FROM biblio b where nid = ?
    """
  )

  val authorQuery = Q.query[Int, String](
    """
    SELECT bcd.name FROM biblio_contributor_data bcd
      JOIN biblio_contributor bc ON bc.cid = bcd.cid WHERE bc.nid = ?
    """
  )

  val keywordQuery = Q.query[Int, String](
    """
    SELECT bkd.word FROM biblio_keyword_data bkd
      JOIN biblio_keyword bk ON bk.kid = bkd.kid WHERE bk.nid = ?
    """
  )

  def index(): Unit = {
    solrServer.setMaxRetries(1)

    Database.forURL(url, driver = "com.mysql.jdbc.Driver") withDynSession {
      def getItem(htid: String) = {
        val results = itemQuery.list(htid)

        results.headOption match {
          case Some((nid, enumcron, _)) =>
            val (title, year, publisher, city, url) = recordQuery.list(nid) match {
              case List((t, y, p, c, Some(u))) => (t, y, p, c, u)
              case other => throw new Exception("No valid record for " + other)
            }

            val authors = authorQuery.list(nid)
            val keywords = keywordQuery.list(nid)

            HItem(
              nid,
              results.map(_._3),
              htid,
              url.takeRight(9),
              enumcron,
              title,
              year,
              authors,
              publisher,
              city,
              keywords
            )
          case _ => throw new Exception("No valid item for " + htid)
        }
      }

      getVolumes.grouped(2).foreach { volumesAndIdxs =>
        val docs = volumesAndIdxs.flatMap {
          case (Volume(metadata, pages), idxs) =>
            println(s"Indexing ${ metadata.htid }.")
            val item = getItem(metadata.htid.toString)
            pages.zipWithIndex.map {
              case (Page(pageMetadata, pageContent), i) =>
                val doc = new SolrInputDocument()
                doc.addField("id", "%s-%04d".format(item.htid, i + 1))
                doc.addField("nid", item.nid)
                //doc.addField("tid", collectionTermIds(idxs.min))
                idxs.foreach(idx => doc.addField("tid", collectionTermIds(idx)))
                doc.addField("htid", item.htid)
                //doc.addField("record", item.record.toInt)
                doc.addField("record", item.record)
                item.enumcron.foreach(doc.addField("enumcron", _))
                item.title.foreach(doc.addField("title", _))
                doc.addField("year", item.year)
                item.publisher.foreach(doc.addField("publisher", _))
                item.city.foreach(doc.addField("city", _))
                item.authors.foreach(doc.addField("author", _))
                item.keywords.foreach(doc.addField("keyword", _))
                doc.addField("seq", i + 1)
                pageMetadata.number.foreach(doc.addField("number", _))
                doc.addField("content", pageContent)
                doc
            }
        }

        solrServer.add(docs.asJava)
        solrServer.commit()
      }
    }
  }
}
