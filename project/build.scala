import sbt._, Keys._

object PpaBuild extends Build {
  lazy val root: Project = Project(
    id = "ppa",
    base = file("."),
    settings = commonSettings
  ).aggregate(core, drupal, indexing)

  lazy val core: Project = Project(
    id = "ppa-core",
    base = file("core"),
    settings = commonSettings ++ Seq(
      libraryDependencies <++= scalaVersion(v => Seq(
        "org.scala-lang" % "scala-compiler" % v
      ))
    )
  )

  lazy val drupal: Project = Project(
    id = "scruple",
    base = file("scruple"),
    settings = commonSettings ++ Seq(
      libraryDependencies ++= Seq(
        "mysql" % "mysql-connector-java" % "5.1.27",
        "com.typesafe.slick" % "slick_2.10" % "2.0.0-M2"
      ) ++ jsDependencies
    )
  )

  lazy val indexing: Project = Project(
    id = "scruple-indexing",
    base = file("indexing"),
    settings = commonSettings ++ Seq(
      libraryDependencies ++= Seq(
        "org.apache.solr" % "solr-core" % "4.4.0",
        "org.apache.solr" % "solr-solrj" % "4.4.0"
      )
    )
  )

  lazy val hathi: Project = Project(
    id = "hathi",
    base = file("hathi"),
    settings = commonSettings ++ Seq(
      resolvers += "Index Data" at "http://maven.indexdata.com/",
      libraryDependencies ++= Seq(
        "org.scalesxml" % "scales-xml_2.10" % "0.6.0-M1",
        "io.argonaut" %% "argonaut" % "6.1-SNAPSHOT" changing(),
        "org.marc4j" % "marc4j" % "2.6-SNAPSHOT",
        "com.github.nscala-time" %% "nscala-time" % "0.6.0",
        "commons-lang" % "commons-lang" % "2.6",
        "org.typelevel" %% "scalaz-contrib-210" % "0.2-SNAPSHOT" changing(),
        "net.databinder.dispatch" %% "dispatch-core" % "0.11.0",
        "com.chuusai" % "shapeless" % "2.0.0-SNAPSHOT" cross CrossVersion.full
      )
    )
  )

  def commonSettings = Defaults.defaultSettings ++ Seq(
    name := "Princeton Prosody Archive",
    organization := "edu.umd.mith",
    version := "0.0.0-SNAPSHOT",
    scalaVersion := "2.10.2",
    resolvers ++= Seq(
      Resolver.sonatypeRepo("snapshots")
    ),
    javaOptions += "-Xmx4G",
    scalacOptions := Seq(
      "-feature",
      "-deprecation",
      "-unchecked"
    ),
    libraryDependencies ++= Seq(
      "org.slf4j" % "slf4j-simple" % "1.7.5",
      "org.typelevel" %% "scalaz-contrib-210" % "0.1.5"
    )
  )

  val jsDependencies = Seq(
    "org.webjars" % "bootstrap" % "3.0.2",
    "org.webjars" % "requirejs" % "2.1.8"
  )
}

