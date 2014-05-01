package edu.umd.mith.ppa

import edu.umd.mith.hathi._
import java.io.File

trait PpaProcessor {
  def metadataBase: File
  def datasetBase: File

  val collectionIds = List(
    "cb_845869729",
    "cb_1766456519",
    "cb_918207798",
    "cb_1899285150"
  )

  val collectionNames = List(
    "Graphically / Typographically Unique",
    "Brogan's English Versification",
    "Prosody Archive",
    "Subject Search"
  )

  lazy val collections = collectionIds.map(id =>
    new Collection(
      new File(metadataBase, id),
      new File(datasetBase, id)
    )
  )

  def volumes: List[(Htid, Set[Int])]

  def getRecordsMetadata: List[(RecordMetadata, Set[Int], List[VolumeMetadata])] =
    getVolumesMetadata.toList.groupBy(_._1.record.id).map {
      case (recordId, volumesAndIdxs) =>
        val record = volumesAndIdxs.head._1.record
        val idxs = volumesAndIdxs.flatMap(_._2).toSet
        val volumes = volumesAndIdxs.map(_._1)
        (record, idxs, volumes)
    }.toList

  def getVolumesMetadata: Iterator[(VolumeMetadata, Set[Int])] = volumes.toIterator.map {
    case (htid, idxs) =>
      collections(idxs.min).volumeMetadata(htid).fold(
        throwable => throw throwable,
        volume => (volume, idxs)
      )
  }

  def getVolumes: Iterator[(Volume, Set[Int])] = volumes.toIterator.map {
    case (htid, idxs) =>
      collections(idxs.min).volume(htid).fold(
        throwable => throw throwable,
        volume => (volume, idxs)
      )    
  }
}
