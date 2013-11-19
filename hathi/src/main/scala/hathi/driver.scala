package edu.umd.mith.hathi

import argonaut._, Argonaut._
import edu.umd.mith.hathi.mets.MetsFile
import scala.io.Source
import scalaz._, Scalaz._

object Test extends MetadataModelJson with MetadataModel with App {
  val dir = new java.io.File(
    "/home/travis/code/projects/haskell/hathi/records/cb_845869729"
  )

  val Htid = "htid:(.*)".r

  val results: ValidationNel[Throwable, Map[String, Result]] =
    dir.listFiles.sorted.toList.traverseU(file =>
      Parse.decodeValidation[Map[String, Result]](
        Source.fromFile(file).mkString
      ).leftMap(
        new Exception(_)
      ).toValidationNel.map(
        _.map {
          case (Htid(id), result) => id -> result
        }
      )
    ).map(_.foldLeft(Map.empty[String, Result])(_ ++ _))

  val corpus = new Corpus(
    new java.io.File("/home/travis/media/corpora/prosody/cb_845869729")
  )

  val withContents = results.flatMap(
    _.toList.traverseU {
      case (id, result) =>
        corpus.volume(id).flatMap {
          case (mets, zippedText) => MetsFile.parsePages(mets)
            //corpus.pagesForVolume(mets, zippedText)
        }.map(pages => id -> (pages, result)).validation.toValidationNel
    }
  )

  withContents.fold(
    _.foreach(println), 
    _.foreach(println)
  )
}

