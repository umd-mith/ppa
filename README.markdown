Princeton Prosody Archive
=========================

The Princeton Prosody Archive is a Drupal-based site that allows users
to browse, search, correct, and annotate a collection of several thousand
works on the study of poetic meter and verse form. The current version of
the the archive includes mostly monographs written in English that are in
the public domain in the United States. The code in this repository (and
related repositories) was developed by [Travis Brown](https://twitter.com/travisbrown)
first as an independent contractor and then in his role as Assistant Director
at the [Maryland Institute for Technology in the Humanities](http://mith.umd.edu/)
as part of a partnership between the Princeton Prosody Archive and MITH.

Technology Overview
-------------------

### Text and Structural Metadata

Most of the volumes in the archive are from the [HathiTrust Digital
Library](http://www.hathitrust.org/). The Library delivered the textual
content of these volumes to the Prosody Archive as a set of
[Pairtree](https://confluence.ucop.edu/display/Curation/PairTree)
archives containing [METS records](http://www.loc.gov/standards/mets/METSOverview.v2.html)
describing the page structure of each volume and zip files containing
the individual page text (which is most cases was produced by an optical
character recognition system without human supervision or correction).

The Prosody Archive has developed software for working with Pairtree archives
(and specifically archives following the conventions used by the HathiTrust)
in both [Haskell](https://github.com/travisbrown/haskell-pairtree) and
[Scala](blob/master/hathi/src/main/scala/util/pairtree.scala). This repository
also includes code for reading volume structure (including page types, numbers,
etc.) out of the structure maps in the METS files.

### Data API

In some cases we have had to recover missing files or other data from the
HathiTrust [Data API](http://www.hathitrust.org/data_api) (application programming interface).
During the first
months of this phase of work on the Prosody Archive, access to the first
version of the Data API was disabled. Version 2 requires validation via a
somewhat unusual flavor of [OAuth](http://oauth.net/)
(["one-legged"](https://github.com/Mashape/mashape-oauth/blob/master/FLOWS.md#oauth-10a-one-legged)
authentication) that is not supported by most OAuth client libraries.
The Prosody Archive has developed generalized code for one-legged
OAuth authentication in both Haskell (built on the
[`http-conduit`](http://hackage.haskell.org/package/http-conduit) library)
and [Scala](blob/master/http://hackage.haskell.org/package/http-conduit) (built on
[Dispatch](http://dispatch.databinder.net/Dispatch.html).

### Bibliographic API

The Prosody Archive relies much more heavily on the HathiTrust
[Bibliographic API](http://www.hathitrust.org/bib_api), since the metadata
in the METS files is entirely structural and does not include any bibliographical
information about authors, titles, dates of publication, etc.
The Archive has developed code for requesting [JSON](http://www.json.org/) from
the Bibliographic API and parsing it into _record_ and _item_ metadata (see the
Bibliographic API documentation for a full definition of these terms in this context;
in short each _record_ describes a bibliographic entity, while _items_ are physical
volumes).

Much of the bibliographic metadata of interest to the archive is not directly
available in the JSON, but is instead embedded in the JSON as MARC records
(serialized as XML and placed in a JSON string; if this sounds like an encoding
nightmare, it is). The Archive has developed Scala code that uses the
[Argonaut](http://argonaut.io/) library to parse the JSON and 
[MARC4J](https://github.com/marc4j/marc4j) to parse this embedded MARC XML.

### Aligning Data and Metadata

Much of the code in this repository is designed to solve the problem of gathering
pieces of data and metadata from all of these different sources into a single
coherent model. Because processing all of this data for thousands of volumes can
be time consuming, the code uses [Scalaz's](https://github.com/scalaz/scalaz)
disjunction and validation sum types to model failure at the value-level instead
of relying on exceptions. While this involves some syntactic overhead, it makes
it much easier to be able to start a processing run and come back ten minutes later
to find a clean, comprehensive list of validation errors instead of a single useless
stack trace.

### Drupal 7

The user-facing parts of the Archive are built on [Drupal 7](https://drupal.org/drupal-7.0).
Currently the Drupal repository (and module repositories) are linked here as a
[Git submodule](http://git-scm.com/book/en/Git-Tools-Submodules); this is an approach
that MITH is experimenting with following our experiences with the development and
deployment of the [Shelley-Godwin Archive](http://shelleygodwinarchive.org/), which is
also built on Drupal 7.

The first version of the Drupal site (developed for the May workshop) ran on the
[Apache web server](http://httpd.apache.org/) and the [MySQL](http://www.mysql.com/)
database. Given MITH's experiences with the Shelley-Godwin Archive, the current
version of the Prosody Archive site runs on [Nginx](http://nginx.org/) and
[MariaDB](https://mariadb.org/). These changes are not visible to site visitors,
but allow the site to run more smoothly on less powerful hardware.

### Bibliography Module

The Prosody Archive site uses the Drupal [Bibliography Module](https://drupal.org/project/biblio)
to provide the Drupal data model and views for its contents. In the first version
of the site, we extracted the MARC XML records, enriched them with additional metadata
from the METS files and Bibliographic API JSON, and loaded them into Drupal via the
Bibliography Modules import functionality. This has proven much too slow and error-prone
for large data sets, however (taking up to hours for even two or three thousand records,
many of which failed for no apparent reason). In the current version of the site, we use
Scala code to write the Scala model of the data directly to the Drupal database.

### Full-Text Indexing

Similarly, in the first version of the site, we used the
[Solr backend](https://drupal.org/project/search_api_solr) for the Drupal
[Search API](https://drupal.org/project/search_api) module, but this also proved unwieldy
at the scale of several thousand volumes, and difficult to integrate with the faceted
search provided by the Bibliography Module. In the current version we have followed the
model that MITH used in the development of the Shelley-Godwin Archive, in which the
search functionality is mostly managed on the client side in a [Backbone.js](http://backbonejs.org/)
application, which communicates with Solr through a very thin web service. All data and metadata
are loaded into Solr by Scala code via the [embedded Solr server API](http://wiki.apache.org/solr/Solrj#EmbeddedSolrServer)
instead of through Drupal over HTTP. This process is much more efficient and less error-prone.

Licensing
---------

All code will be released under the [Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0).
At the current moment this repository is not publicly available, however.

