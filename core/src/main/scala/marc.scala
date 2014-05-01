package edu.umd.mith.ppa

import edu.umd.mith.hathi._
import java.io.{ BufferedOutputStream, File, FileOutputStream }
import java.text.BreakIterator
import org.marc4j.MarcStreamWriter
import scala.collection.JavaConverters._

trait MarcGenerator extends CleaningUtils { this: PpaProcessor =>
  def writeMarc(): Unit = getRecordsMetadata.grouped(1000).zipWithIndex.foreach {
    case (records, i) =>
      val output = new File("output")
      if (!output.exists) output.mkdir()

      val stream = new BufferedOutputStream(
        new FileOutputStream(
          new File(output, "records-%04d.mrc".format(i))
        )
      )

      val writer = new MarcStreamWriter(stream, "UTF-8")

      records.foreach {
        case (record, _, _) =>
          record.marc.underlying.getVariableFields("246").asScala.foreach {
            record.marc.underlying.removeVariableField(_)
          }

          record.marc.addOrReplace("246", '3', ' ', 'a') {
            val s = breakNear(cleanTrim(record.titles.head), 32)
            s
          }

          record.marc.modify("100", 'a')(cleanTrim)
          record.marc.modify("260", 'a')(cleanTrim)
          record.marc.modify("650", 'a')(cleanTrim)
          record.marc.modify("650", 'v')(cleanTrim)
          record.marc.modify("650", 'x')(cleanTrim)
          record.marc.modify("650", 'y')(cleanTrim)
          record.marc.modify("650", 'z')(cleanTrim)
          record.marc.modify("700", 'a')(cleanTrim)
          record.marc.add("856", '4', '0', 'u')(record.url.toString)
          
          writer.write(record.marc.underlying)
      }
      writer.close()
  }
}

trait CleaningUtils {
  val CleanTrim = """\s*(.+?)\s*[\.,;:]*\s*""".r

  def cleanTrim(s: String) = s.replaceAll("""\[from old catalog\]""", "") match {
    case CleanTrim(v) => v
  }

  def breakNear(s: String, n: Int) =
    if (n < s.size) {
      val iterator = BreakIterator.getWordInstance
      iterator.setText(s)

      s.substring(0, iterator.preceding(n)).trim + "..."
    } else s
}

