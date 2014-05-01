package edu.umd.mith.ppa

import edu.umd.mith.ppa.spreadsheet.SpreadsheetReader
import java.io.File

trait DevServer extends PpaProcessor with FromDrupalConfig {
  lazy val drupalConfigFile = new File("/var/www/prosody_dev/sites/default/settings.php")
  lazy val metadataBase = new File("/home/travis.brown/media/corpora/prosody/metadata")
  lazy val datasetBase = new File("/home/travis.brown/media/corpora/prosody/data")
  lazy val numberOfCollections = 4
}

object PpaDriver extends App {
  if (args.length != 2) sys.error(
    "Please provide two arguments: the command (--itemize or --index) and the spreadsheet path."
  )

  val command = args(0)
  val spreadsheetPath = args(1)

  command match {
    case "--itemize" => 
      val itemizer = new Itemizer with SpreadsheetReader with DevServer {
        val spreadsheetFile = new File(spreadsheetPath)
      }
      itemizer.deleteAllItems()
      itemizer.addItemsToDatabase()
    case "--index" =>
      val indexer = new Indexer with SpreadsheetReader with DevServer {
        val spreadsheetFile = new File(spreadsheetPath)
      }
      indexer.index()
  }
}

