package edu.umd.mith.ppa
package spreadsheet

import edu.umd.mith.hathi._
import java.io.{ File, FileInputStream }
import org.apache.poi.xssf.usermodel.{ XSSFCell, XSSFRow, XSSFSheet, XSSFWorkbook }

trait SpreadsheetReader { this: PpaProcessor =>
  def spreadsheetFile: File
  def numberOfCollections: Int

  lazy val volumes = {
    val in = new FileInputStream(spreadsheetFile)
    val workbook = new XSSFWorkbook(in)
    val sheet = workbook.getSheetAt(0)

    val header = sheet.getRow(0)
    val collectionNames = (0 until numberOfCollections).map(i =>
      header.getCell(i + 2).getRawValue
    )

    (1 to sheet.getLastRowNum).map { i =>
      val row = sheet.getRow(i)
      val volumeId = row.getCell(0).getStringCellValue
      val recordId = row.getCell(1).getStringCellValue
      val collectionStatuses = (0 until numberOfCollections).map(j =>
        Option(row.getCell(j + 2)).map(_.getNumericCellValue.toInt).fold(false)(_ == 1)
      )

      (
        Htid.parse(volumeId),
        collectionStatuses.zipWithIndex.filter(_._1).map(_._2).toSet
      )
    }.filter(_._2.nonEmpty).filter {
      case (Htid("uc2", "ark:/13960/t5q81810k"), _) => false
      case _ => true
    }.toList
  }
}
