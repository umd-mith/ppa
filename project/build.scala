import sbt._, Keys._
import sbtassembly.Plugin._, AssemblyKeys._

object ProsodyArchive extends Build {
  lazy val root: Project = Project(
    id = "ppa",
    base = file("."),
    settings = commonSettings
  ).aggregate(core)

  lazy val core: Project = Project(
    id = "ppa-core",
    base = file("core"),
    settings = commonSettings ++ assemblySettings ++ Seq(
      libraryDependencies ++= Seq(
        "com.typesafe.slick" % "slick_2.10" % "2.0.1",
        "mysql" % "mysql-connector-java" % "5.1.28",
        "org.apache.poi" % "poi-ooxml" % "3.10-FINAL",
        "org.apache.solr" % "solr-core" % "4.6.0",
        "org.apache.solr" % "solr-solrj" % "4.6.0"
      ),
      mainClass in assembly := Some(
        "edu.umd.mith.ppa.PpaDriver"
      ),
      mergeStrategy in assembly <<= (mergeStrategy in assembly) { (old) =>
        {
          case PathList("org", "apache", "commons", "logging", _*) => MergeStrategy.first
          case PathList("org", "slf4j", _*) => MergeStrategy.first
          case x => old(x)
        }
      }
    )
  ).dependsOn(
    ProjectRef(
      uri("git://github.com/umd-mith/hathi.git#eacc2944b584ecf911085e556060f008c6587dce"),
      "hathi-core"
    )
  )

  def commonSettings = Defaults.defaultSettings ++ Seq(
    name := "ppa",
    organization := "edu.umd.mith",
    version := "0.0.0-SNAPSHOT",
    scalaVersion := "2.10.4",
    resolvers ++= Seq(
      "Index Data" at "http://maven.indexdata.com/",
      "Restlet Repository" at "http://maven.restlet.org",
      Resolver.sonatypeRepo("snapshots")
    ),
    javaOptions += "-Xmx1.5G",
    scalacOptions := Seq(
      "-feature",
      "-deprecation",
      "-unchecked"
    ),
    libraryDependencies ++= Seq(
      "commons-logging" % "commons-logging" % "1.1.3",
      "org.slf4j" % "slf4j-simple" % "1.7.5",
      "org.scalaz" %% "scalaz-core" % "7.0.6",
      "org.scalaz" %% "scalaz-concurrent" % "7.0.6"
    )
  )
}

