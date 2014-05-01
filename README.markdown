Princeton Prosody Archive
=========================

The Princeton Prosody Archive is a Drupal-based site that allows users
to browse, search, correct, and annotate a collection of several thousand
works on the study of poetic meter and verse form. The current version of
the the archive includes mostly monographs written in English that are in
the public domain in the United States. The code in this repository (and
[related repositories](https://github.com/umd-mith/hathi)) was developed by
[Travis Brown](https://twitter.com/travisbrown)
first as an independent contractor and then in his role as Assistant Director
at the [Maryland Institute for Technology in the Humanities](http://mith.umd.edu/)
as part of a partnership between the Princeton Prosody Archive and MITH.

Most of the code developed for the project has been moved from this repository
to [a more general HathiTrust utilities project](https://github.com/umd-mith/hathi)
maintained by MITH (all Prosody Archive-specific code remains here).

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
[Dispatch](http://dispatch.databinder.net/Dispatch.html)).

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

### Bibliography Module

The Prosody Archive site uses the Drupal [Bibliography Module](https://drupal.org/project/biblio)
to provide the Drupal data model and views for its contents.
We extracted the MARC XML records, enrich them with additional metadata
from the METS files and Bibliographic API JSON, and load them into Drupal via the
Bibliography Module's import functionality.

### Full-Text Indexing

Similarly, in the first version of the site, we used the
[Solr backend](https://drupal.org/project/search_api_solr) for the Drupal
[Search API](https://drupal.org/project/search_api) module, but this also proved unwieldy
at the scale of several thousand volumes, and difficult to integrate with the faceted
search provided by the Bibliography Module. In the current version we have followed the
model that MITH used in the development of the Shelley-Godwin Archive, in which the
search functionality is mostly managed on the client side in a [Backbone.js](http://backbonejs.org/)
application, which communicates with Solr through a proxy that only allows read-only
queries.

Installation
------------

In order to set up a new installation, you need to have the HathiTrust Pairtree structures
and Bibliographic API JSON metadata saved locally. You also need a spreadsheet that
lists the volumes that you want to be included in the archive (and which collections they
should be included in). See the `metadata` directory for the Prosody Archive spreadsheet,
and see [the MITH HathiTrust utilities repository](https://github.com/umd-mith/hathi) for
information about how to access the Bibliographic API, etc.

You should have an installation of Drupal 7 available (there is currently a configured
but unloaded backup of the database in `/home/drupal/backup/empty.sql`).
The Drupal theme and module in the
`drupal` directory should be copied to the appropriate location (generally `sites/all/themes/`
and `sites/all/modules` and enabled via [Drush](https://drupal.org/project/drush).
The `solr-search/assets` directory should be copied to `sites/default/files/`, and
the `solr-search` `require.js` file to `sites/default/files/solr-search/`.

Next you'll need to create the MARC record files for import. First compile the application:

``` bash
./sbt assembly
```

And then run the MARC generation:

``` bash
java -jar core/target/scala-2.10/ppa-assembly-0.0.0-SNAPSHOT.jar \
  --marc metadata/ppa-volumes.xlsx
```

Next you'll need to navigate to the Drupal installation and run the Drush script
to perform the import:

``` bash
drush php-script ~/code/projects/prosody/scripts/import-marc.php
```

This will take a few minutes (and will display some warnings).
Next run the following to add the relationships
between records, volumes, and collections:

```
java -jar core/target/scala-2.10/ppa-assembly-0.0.0-SNAPSHOT.jar \
  --itemize metadata/ppa-volumes.xlsx
```

And finally run the Solr indexer:

```
java -jar core/target/scala-2.10/ppa-assembly-0.0.0-SNAPSHOT.jar \
  --index metadata/ppa-volumes.xlsx
```

This will take up to several hours, so you may want to use `nohup` to avoid
broken connection errors:

```
nohup java -jar core/target/scala-2.10/ppa-assembly-0.0.0-SNAPSHOT.jar \
  --index metadata/ppa-volumes.xlsx &
```

And then the installation will be ready for use.

Licensing
---------

The [MITH HathiTrust utilities library](https://github.com/umd-mith/hathi) and the `core`
project in this repository are released under the [Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0).
The customized theme and modules in the `drupal` repository are released under the
[GNU General Public License, Version 2](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html).
The modified Javascript application in the `solr-search` directory is released under the
[MIT License](http://opensource.org/licenses/MIT). Please see the individual projects
in these directories for full information about copyright and licensing.

