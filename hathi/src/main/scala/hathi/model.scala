package edu.umd.mith.hathi

import com.github.nscala_time.time.Imports.LocalDate
import edu.umd.mith.util.marc.Record
import java.net.URL
import org.joda.time.format.DateTimeFormat

trait MetadataModel {
  case class Updated(date: Option[LocalDate]) {
    override def toString = date.fold("00000000")(Updated.format.print)
  }

  object Updated {
    val format = DateTimeFormat.forPattern("yyyyMMdd")
  }

  case class Escaped(value: String)

  sealed trait Enumcron
  case class BooleanEnumcron(value: Boolean) extends Enumcron
  case class OtherEnumcron(value: String) extends Enumcron

  case class Item(
    url: URL,
    htid: String,
    orig: String,
    fromRecord: String,
    rights: String,
    lastUpdate: Updated,
    enumcron: Enumcron,
    usRights: String
  )

  case class HathiRecord(
    url: URL,
    titles: List[String],
    isbns: List[String],
    issns: List[String],
    oclcs: List[String],
    publishDates: List[String],
    marc: List[Record]
  )

  case class Result(
    records: Map[String, HathiRecord],
    items: List[Item]
  )
}

