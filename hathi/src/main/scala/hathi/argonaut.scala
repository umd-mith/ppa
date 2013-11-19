package edu.umd.mith.hathi

import argonaut._, Argonaut._
import edu.umd.mith.util.ArgonautUtils
import edu.umd.mith.util.marc.{ MarcUtils, Record }
import org.apache.commons.lang.StringEscapeUtils.{
  escapeJavaScript,
  unescapeJavaScript
}
import java.io.ByteArrayInputStream
import org.marc4j.MarcXmlReader
import scalaz._, Scalaz._

trait MetadataModelJson extends ArgonautUtils with MarcUtils {
  this: MetadataModel =>
  implicit val UpdatedCodecJson: CodecJson[Updated] = CodecJson(
    (a: Updated) => Json.jString(a.toString),
    (c: HCursor) => c.as[String].flatMap {
      case "00000000" => DecodeResult.ok(Updated(None))
      case s => tryResult(c.history)(
        Updated.format.parseLocalDate(s)
      ).map(date => Updated(Some(date)))
    }
  )

  implicit val EscapedCodecJson: CodecJson[Escaped] = CodecJson(
    (a: Escaped) => Json.jString(escapeJavaScript(a.value)),
    (c: HCursor) => c.as[String].map(s => Escaped(unescapeJavaScript(s)))
  )

  implicit val EnumcronEncodeJson: CodecJson[Enumcron] = CodecJson.derived(
    EncodeJson[Enumcron] {
      case BooleanEnumcron(value) => Json.jBool(value)
      case OtherEnumcron(value) => Json.jString(value)
    },
    implicitly[DecodeJson[Boolean]].map[Enumcron](BooleanEnumcron(_)) |||
    implicitly[DecodeJson[String]].map(OtherEnumcron(_))
  )

  implicit val RecordListDecodeJson: DecodeJson[List[Record]] =
    DecodeJson { c =>
      c.as[String].flatMap { contents =>
        val stream = new ByteArrayInputStream(contents.getBytes("UTF-8"))

        tryResult(c.history) {
          val reader = new MarcXmlReader(stream) 
          reader.toRecordList
        }
      }
    }

  implicit val ItemDecodeJson: DecodeJson[Item] = jdecode8L(Item.apply)(
    "itemURL",
    "htid",
    "orig",
    "fromRecord",
    "rightsCode",
    "lastUpdate",
    "enumcron",
    "usRightsString"
  )

  implicit val HathiRecordDecodeJson: DecodeJson[HathiRecord] =
    jdecode7L(HathiRecord.apply)(
      "recordURL",
      "titles",
      "isbns",
      "issns",
      "oclcs",
      "publishDates",
      "marc-xml"
    )

  implicit val ResultDecodeJson: DecodeJson[Result] =
    jdecode2L(Result.apply)("records", "items")
}

