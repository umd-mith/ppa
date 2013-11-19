package edu.umd.mith.util.marc

import org.marc4j.MarcReader
import org.marc4j.marc.{ Record => Record4j }
import scala.collection.JavaConverters._
import scalaz._, Scalaz._

case class DataField(
  indicator1: Char,
  indicator2: Char,
  subfields: Map[Char, String]
)

trait Record {
  def controlFields: Map[String, String]
  def dataFields: Map[String, List[DataField]]

  def subjects: List[String] = dataFields.getOrElse("650", Nil).flatMap(
    _.subfields.values.toList
  )
}

class RecordWrapper(underlying: Record4j) extends Record {
  val controlFields = underlying.getControlFields.asScala.map(
    field => field.getTag -> field.getData
  ).toMap

  val dataFields = underlying.getDataFields.asScala.groupBy(
    _.getTag
  ).mapValues(
    _.map(field =>
      DataField(
        field.getIndicator1,
        field.getIndicator2,
        field.getSubfields.asScala.map {
          subfield => subfield.getCode -> subfield.getData
        }.toMap
      )
    ).toList
  ).view.force
}

trait MarcUtils {
  implicit class RichMarcReader(reader: MarcReader) {
    def toRecordList: List[Record] = {
      val records = scala.collection.mutable.ListBuffer.empty[Record]

      while (reader.hasNext()) {
        new RecordWrapper(reader.next()) +=: records 
      }

      records.reverse.toList
    }
  }
}

