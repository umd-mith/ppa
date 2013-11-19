package edu.umd.mith.util

import argonaut._, Argonaut._
import java.net.URL
import org.apache.commons.lang.StringEscapeUtils.unescapeJavaScript
import scala.util.{ Success, Failure, Try }
import scalaz.{ -\/, \/, \/- }

trait ArgonautUtils {
  def disjunctionToResult[A](
    h: CursorHistory
  ): (Throwable \/ A) => DecodeResult[A] = {
    case \/-(res) => DecodeResult.ok(res)
    case -\/(e)   => DecodeResult.fail(e.getMessage, h)
  }

  def tryResult[A](h: CursorHistory)(a: => A): DecodeResult[A] =
    disjunctionToResult(h)(\/.fromTryCatch(a))

  implicit val URLCodecJson: CodecJson[URL] = CodecJson(
    (a: URL) => Json.jString(a.toString),
    (c: HCursor) => c.as[String].map(unescapeJavaScript).flatMap(s =>
      tryResult(c.history)(new URL(s))
    )
  )
}

