package edu.umd.mith.hathi.mets

import edu.umd.mith.util.Pairtree
import java.io.{ BufferedReader, InputStreamReader, File, FileReader }
import java.util.zip.{ ZipEntry, ZipFile }
import scala.language.postfixOps
import scales.utils._
import scales.xml._, ScalesXml._, xpath.AttributePath
import
  scalaz.\/,
  scalaz.std.list._,
  scalaz.syntax.apply._,
  scalaz.syntax.either._,
  scalaz.syntax.std.boolean._,
  scalaz.syntax.std.option._,
  scalaz.syntax.traverse._

case class UnexpectedMetsStructure(msg: String) extends Exception(msg)

trait PageInfo {
  def path: String
  def number: Option[String]
  def allLabels: Set[String]
  protected def zipFile: File

  def isMultiworkBoundary = allLabels("MULTIWORK_BOUNDARY")
  def isFrontCover = allLabels("FRONT_COVER") || allLabels("COVER")
  def isTitle = allLabels("TITLE")
  def isCopyright = allLabels("COPYRIGHT")
  def isFirstContentChapterStart = allLabels("FIRST_CONTENT_CHAPTER_START")
  def isChapterStart = allLabels("CHAPTER_START")
  def isReferences = allLabels("REFERENCES")
  def isIndex = allLabels("INDEX")
  def isBackCover = allLabels("BACK_COVER")
  def isBlank = allLabels("BLANK")
  def isUntypical = allLabels("UNTYPICAL_PAGE")
  def hasImage = allLabels("IMAGE_ON_PAGE")
  def hasImplicitNumber = allLabels("IMPLICIT_PAGE_NUMBER")

  def contents: Throwable \/ String = \/.fromTryCatch {
    val zip = new ZipFile(zipFile)

    val builder = new StringBuilder()
    val reader = new BufferedReader(
      new InputStreamReader(zip.getInputStream(zip.getEntry(path)))
    )

    var line = reader.readLine()
    while (line != null) {
      builder.append(line)
      builder.append('\n')
      line = reader.readLine()
    }
    
    builder.toString
  }

  override def toString = s"$path: ${number.getOrElse("N/A")}"
}

object MetsFile {
  val metsNs = Namespace("http://www.loc.gov/METS/")
  val xlinkNs = Namespace("http://www.w3.org/1999/xlink")

  private[this] val knownLabels = Set(
    "MULTIWORK_BOUNDARY",
    "COVER",
    "FRONT_COVER",
    "TITLE",
    "COPYRIGHT",
    "TABLE_OF_CONTENTS",
    "FIRST_CONTENT_CHAPTER_START",
    "CHAPTER_START",
    "REFERENCES",
    "INDEX",
    "BACK_COVER",
    "BLANK",
    "UNTYPICAL_PAGE",
    "IMPLICIT_PAGE_NUMBER",
    "IMAGE_ON_PAGE",
    // The following are currently ignored.
    "FRONT_COVER_FLAP",
    "BACK_COVER_FLAP",
    "INSIDE_FRONT_COVER",
    "PREFACE",
    "CHECKOUT_PAGE",
    "RIGHT,COVER",
    "FRONT_COVER_IMAGE_CORRECTION",
    "PAGE_TURNBACK",
    "RIGHT",
    "LEFT",
    "MISSING_PAGE",
    "MISSING",
    "FOLDOUT",
    "UNS",
    "ERRATA",
    "TP",
    "IND"
  )

  private[this] def parseLabels(s: String): Throwable \/ Set[String] = {
    val labels = s.split(",\\s*").toSet
    val unknownLabels = labels - "" -- knownLabels

    unknownLabels.nonEmpty.either(
      UnexpectedMetsStructure(
        s"Unknown labels: ${unknownLabels.mkString(", ")}."
      )
    ).or(labels)
  }

  private[this] def parseStructMap(
    idWithLibrary: String,
    fileName: String,
    fileMap: Map[String, String],
    textZipFile: File
  )(
    xml: XmlPath
  ): Throwable \/ List[PageInfo] = (
    xml \* metsNs("div") \@ "TYPE" === "volume"
  ).\^.one.headOption.toRightDisjunction(
      UnexpectedMetsStructure("Expected one volume div in $fileName.")
  ).flatMap { div =>
    (
      div \* metsNs("div") \@ "TYPE" === "page" \^
    ).toList.traverseU { page =>
      val orderLabel = (page \@ "ORDERLABEL").one.headOption.map(_.attribute.value)
      val labels = (page \@ "LABEL").one.headOption.fold(
        Set.empty[String].right[Throwable]
      )(ls => parseLabels(ls.attribute.value))

      val path = (page \* metsNs("fptr") \@ "FILEID").toList.filter(fileId =>
        fileId.attribute.value.startsWith("TXT") ||
        fileId.attribute.value.startsWith("OCR")
      ).headOption.toRightDisjunction(
        UnexpectedMetsStructure(s"Missing text file pointer in $fileName.")
      ).flatMap {
        case AttributePath(Attribute(_, fileId), _) =>
          fileMap.get(fileId).toRightDisjunction(
            UnexpectedMetsStructure(s"""Missing path for "$fileId" in $fileName.""")
          )
      }

      for {
        ls <- labels
        p <- path
        libraryAndId <- Pairtree.toLibraryAndId(idWithLibrary)
        idPath <- Pairtree.Default.cleanId(libraryAndId._2)
      } yield new PageInfo {
        val path = s"$idPath/$p"
        val number = orderLabel
        val allLabels = ls
        val zipFile = textZipFile
      }
    }
  }

  private[this] def parseFileMap(
    doc: Doc
  ): Throwable \/ Map[String, String] = (
    top(doc) \*
    metsNs("fileSec") \*
    metsNs("fileGrp") \*
    metsNs("file")
  ).toList.traverseU { file =>
    val id = (file \@ "ID").one.headOption.toRightDisjunction(
      UnexpectedMetsStructure("Missing identifier.")
    ).map(_.attribute.value)

    val href = (
      file \* metsNs("FLocat") \@ xlinkNs("href")
    ).one.headOption.toRightDisjunction(
      UnexpectedMetsStructure("Missing link.")
    ).map(_.attribute.value)

    id tuple href
  }.map(_.toMap)

  private[this] def findId(doc: Doc): Throwable \/ String =
    (top(doc) \@ "OBJID").one.headOption.toRightDisjunction(
      UnexpectedMetsStructure("Missing object identifier.")
    ).map(_.attribute.value)

  private[this] def findZipFileName(
    doc: Doc
  ): Throwable \/ String = (
    top(doc) \*
    metsNs("fileSec") \*
    metsNs("fileGrp") \@
    "USE" === "zip archive"
  ).\^.one.headOption.toRightDisjunction(
    UnexpectedMetsStructure("Expected file group for zip archive.")
  ).flatMap { fileGrp =>
    (
      fileGrp \* metsNs("file") \* metsNs("FLocat") \@ xlinkNs("href")
    ).one.headOption.toRightDisjunction(
      UnexpectedMetsStructure("Expected file link for zip archive.")
    ).map(_.attribute.value)
  }

  def parsePages(file: File): Throwable \/ List[PageInfo] = {
    \/.fromTryCatch(loadXml(new FileReader(file))).leftMap(e =>
      UnexpectedMetsStructure(s"Invalid XML for file $file.")
    ).flatMap { doc =>
      for {
        id <- findId(doc)
        zipFileName <- findZipFileName(doc)
        fileMap <- parseFileMap(doc)
        structMap <- (
            top(doc) \* metsNs("structMap")
          ).one.headOption.toRightDisjunction(
            UnexpectedMetsStructure("Expected one structure map.")
          ).flatMap(parseStructMap(id, file.toString, fileMap, new File(file.getParent, zipFileName)))
      } yield structMap
    }
  }
}

