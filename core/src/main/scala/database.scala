package edu.umd.mith.ppa

import edu.umd.mith.hathi._
import java.io.File
import scala.io.Source
import scala.slick.driver.JdbcDriver.backend.Database, Database.dynamicSession
import scala.slick.jdbc.{ GetResult, StaticQuery => Q }

trait DatabaseUtils { this: PpaProcessor =>
  def databaseName: String
  def databaseUser: String
  def databasePassword: String

  def url = s"jdbc:mysql://localhost/$databaseName?user=$databaseUser&password=$databasePassword"

  lazy val collectionTermIds: List[Int] = {
    Database.forURL(url, driver = "com.mysql.jdbc.Driver") withDynSession {
      val q = Q.query[String, Int]("SELECT tid FROM taxonomy_term_data WHERE name = ?")

      collectionNames.map { name =>
        q.list(name) match {
          case List(tid) => tid
          case _ => throw new Exception(s"No valid id for $name.")
        }
      }
    }
  }
}

/** This provides a simple way to avoid specifying configuration information
  * in multiple places. It is not intended to be robust against edits to the
  * settings file.
  */
trait FromDrupalConfig extends DatabaseUtils { this: PpaProcessor =>
  def drupalConfigFile: File

  private[this] val NamePattern = ".*'database'\\s*=>\\s*'([^']+)'.*".r
  private[this] val UserPattern = ".*'username'\\s*=>\\s*'([^']+)'.*".r
  private[this] val PasswordPattern = ".*'password'\\s*=>\\s*'([^']+)'.*".r

  private[this] lazy val lines = {
    val source = Source.fromFile(drupalConfigFile)
    // Filter lines that look like they are in comments.
    val lines = source.getLines.map(_.trim).filterNot(_.startsWith("*")).toList
    source.close()

    lines
  }

  lazy val databaseName = lines.collectFirst {
    case NamePattern(name) => name
  }.getOrElse(
    throw new Exception("Can't find database name in Drupal settings file.")
  )

  lazy val databaseUser = lines.collectFirst {
    case UserPattern(user) => user
  }.getOrElse(
    throw new Exception("Can't find database user in Drupal settings file.")
  )

  lazy val databasePassword = lines.collectFirst {
    case PasswordPattern(password) => password
  }.getOrElse(
    throw new Exception("Can't find database password in Drupal settings file.")
  )
}
