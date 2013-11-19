package edu.umd.mith.hathi

import edu.umd.mith.hathi.mets.{ MetsFile, PageInfo }
import edu.umd.mith.util.{ Pairtree, ZipFileUtils }
import java.io.File
import scalaz._, Scalaz._
/*import scalaz.\/
import
  scalaz.syntax.apply._,
  scalaz.syntax.either._,
  scalaz.syntax.std.boolean._*/

object Corpus {
  case class MissingVolumeDirectoryError(dir: File) extends Exception(
    s"Missing volume directory: $dir"
  )

  case class MissingFileError(file: File) extends Exception(
    s"Missing file: $file"
  )

  def checkFile(file: File): Throwable \/ File =
    (!file.isFile).either(MissingFileError(file)).or(file)
}

class Corpus(base: File) extends ZipFileUtils {
  private[this] val Pt = Pairtree.Default
  private[this] def volumeDirName(path: String) = Pt.pathToCleanId(path)

  private[this] val LibraryAndId = "([^\\.]+)\\.(.+)".r

  def pagesForVolume(mets: File, zippedText: File) = {
    val files = zipFileContents(zippedText).map(
      _.map(_.leftMap(_.split("/"))).flatMap {
        case (Array(_), contents) => None
        case (Array(_, path), contents) => Some(path, contents)
      }.toMap
    )

    val pages = MetsFile.parsePages(mets)

    files.flatMap { fileMap =>
      pages.map { ps =>
        ps.map { p =>
          p -> fileMap(p.path)
        }
      }
    }
  }

  def volume(idWithLibrary: String): Throwable \/ (File, File) =
    idWithLibrary match {
      case LibraryAndId(library, id) => volumeInLibrary(library)(id)
      case _ => new Exception(s"Invalid identifier: $idWithLibrary.").left
    } 

  def volumeInLibrary(library: String)(
    id: String
  ): Throwable \/ (File, File) =
    Pt.toPath(id).flatMap { path =>
      val name = volumeDirName(path)

      val volumeDir = new File(
        new File(
          new File(new File(base, library), "pairtree_root"),
          path
        ),
        name
      )

      (!volumeDir.isDirectory).either(
        Corpus.MissingVolumeDirectoryError(volumeDir)
      ).or(volumeDir).flatMap { dir =>
        val mets = Corpus.checkFile(new File(dir, s"$name.mets.xml"))
        val text = Corpus.checkFile(new File(dir, s"$name.zip"))

        mets tuple text
      }
    }
}

