this["JST"] = this["JST"] || {};

this["JST"]["app/templates/facet-author.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '        <div class="block-content content">\n          <div class= view-display-id-block">\n            <div class="view-content">\n              <div class="item-list">\n                <ul>\n                  ';

                    var collectionName = '';
                    var collectionUri = '';
                    var collectionCount = 0;
                    if (facetCounts.facet_fields !== undefined && facetCounts.facet_fields['author'] !== undefined) {
                      for (var i = 0; i < facetCounts.facet_fields['author'].length; i++) {
                        var facetValue = facetCounts.facet_fields['author'][i];
                        var collectionCount = facetValue[1];
                        var constraintUri = infoSolr.currentParams + '&fq=author:%22' + escape(facetValue[0]) + '%22'
                  ;
__p += '\n                  <li class="views-row">\n                    <div class="views-field views-field-name">\n                      <span class="field-content">\n                        <a href="/search/text#?' +
((__t = ( constraintUri )) == null ? '' : __t) +
'" data-route>' +
((__t = ( facetValue[0] )) == null ? '' : __t) +
'</a>\n                        (' +
((__t = ( facetValue[1].toLocaleString() )) == null ? '' : __t) +
' pages)\n                      </span>\n                    </div>\n                  </li>\n                  ';

                      }  
                    }
                  ;
__p += '\n                </div>\n              </div>\n            </div>\n          </div>\n        </div>\n\n';

}
return __p
};

this["JST"]["app/templates/facet-city.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '        <div class="block-content content">\n          <div class= view-display-id-block">\n            <div class="view-content">\n              <div class="item-list">\n                <ul>\n                  ';

                    var collectionName = '';
                    var collectionUri = '';
                    var collectionCount = 0;
                    if (facetCounts.facet_fields !== undefined && facetCounts.facet_fields['city'] !== undefined) {
                      for (var i = 0; i < facetCounts.facet_fields['city'].length; i++) {
                        var facetValue = facetCounts.facet_fields['city'][i];
                        var collectionCount = facetValue[1];
                        var constraintUri = infoSolr.currentParams + '&fq=city:%22' + escape(facetValue[0]) + '%22'
                  ;
__p += '\n                  <li class="views-row">\n                    <div class="views-field views-field-name">\n                      <span class="field-content">\n                        <a href="/search/text#?' +
((__t = ( constraintUri )) == null ? '' : __t) +
'" data-route>' +
((__t = ( facetValue[0] )) == null ? '' : __t) +
'</a>\n                        (' +
((__t = ( facetValue[1].toLocaleString() )) == null ? '' : __t) +
' pages)\n                      </span>\n                    </div>\n                  </li>\n                  ';

                      }  
                    }
                  ;
__p += '\n                </div>\n              </div>\n            </div>\n          </div>\n        </div>\n\n';

}
return __p
};

this["JST"]["app/templates/facet-collection.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '        <div class="block-content content">\n          <div class="view view-view-collection view-id-view_collection view-display-id-block">\n            <div class="view-content">\n              <div class="item-list">\n                <ul>\n                  ';

                    var collectionName = '';
                    var collectionUri = '';
                    var collectionCount = 0;
                    if (facetCounts.facet_fields !== undefined && facetCounts.facet_fields['tid'] !== undefined) {
                      for (var i = 0; i < facetCounts.facet_fields['tid'].length; i++) {
                        var facetValue = facetCounts.facet_fields['tid'][i];
                        var collectionCount = facetValue[1];
                        var constraintUri = infoSolr.currentParams + '&fq=tid:%22' + escape(facetValue[0]) + '%22'
                        switch (facetValue[0]) {
                          case '3':
                            collectionName = 'Subject Search';
                            collectionUri = '/collection/subject-search';
                            break;
                          case '2':
                            collectionName = 'Prosody Archive';
                            collectionUri = '/collection/prosody-archive';
                            break;
                          case '4':
                            collectionName = 'Brogan&#039;s English Versification';
                            collectionUri = '/collection/brogans';
                            break;
                          case '1':
                            collectionName = 'Graphically / Typographically Unique';
                            collectionUri = '/collection/unique';
                            break;
                        }
                  ;
__p += '\n                  <li class="views-row">\n                    <div class="views-field views-field-name">\n                      <span class="field-content">\n                        <a href="/search/text#?' +
((__t = ( constraintUri )) == null ? '' : __t) +
'" data-route>' +
((__t = ( collectionName )) == null ? '' : __t) +
'</a>\n                        (' +
((__t = ( facetValue[1].toLocaleString() )) == null ? '' : __t) +
' pages)\n                      </span>\n                    </div>\n                  </li>\n                  ';

                      }  
                    }
                  ;
__p += '\n                </div>\n              </div>\n            </div>\n          </div>\n        </div>\n\n';

}
return __p
};

this["JST"]["app/templates/filters.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {

 if (facetQueries.length>0) { ;
__p += '\n<div class="row-fluid">\n\t<div class="span12">\n\t\t<div>Filters </div>\n\t\t<ul class="breadcrumb">\n\t\t\t';
 for(var i=0; i<facetQueries.length; i++) { ;
__p += '\n\t\t\t<li>\n\t\t\t\t<span class="divider">x</span>\n\t\t\t\t<a id="' +
((__t = (facetQueries[i])) == null ? '' : __t) +
'" class="rfq" href="#">\n\t\t\t\t\t';

            var fq = facetQueries[i];
            var fn = fq;
            if (fq.match("^tid:")) {
              switch (fq.substring(7, 10)) {
                case '3':
                  fn = 'collection:"Subject Search"';
                  break;
                case '2':
                  fn = 'collection:"Prosody Archive"';
                  break;
                case '4':
                  fn = 'collection:"Brogan&#039;s English Versification"';
                  break;
                case '1':
                  fn = 'collection:"Graphically / Typographically Unique"';
                  break;
              }
            }
            if (fq.match("^nid:")) {
              fn = 'work:' + fq.substring(4);
            }
          ;
__p += '\n          ' +
((__t = ( unescape(fn) )) == null ? '' : __t) +
'\n\t\t\t\t</a> \n\t\t\t</li>\n\t\t\t';
 } ;
__p += '\n\t\t</ul>\n\t</div>\n</div>\n';
 } ;
__p += '\n';

}
return __p
};

this["JST"]["app/templates/layouts/main.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<div class="row-fluid">\n  <div class="span12">\n    <div id="search"></div>\n  </div>\n</div>\n<div id="filters"></div>\n<div class="row-fluid">\n  <div class="span9">\n    <div class="row-fluid">\n      <div class="span12">\n          <div id="results-header" class="row-fluid"></div>\n      </div>\n    </div>\n    <div id="records"></div>\n    <div id="results" class="row-fluid"/>\n    <div id="pagination" class="row-fluid"></div>\n  </div>\n</div>\n';

}
return __p
};

this["JST"]["app/templates/pagination.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {

if (total>0 && totalPages>1) { ;
__p += '\n<div class="navbar">\n\t<div class="navbar-inner">\n\t\t<div class="pagination pagination-centered">\n\t\t\t<ul style="list-style-type: none; margin: 0px; padding: 0px;">\n\t\t\t\t<li ' +
((__t = ((isFirstPage) ? 'class="disabled"':'' )) == null ? '' : __t) +
' style="display: inline;">\n\t\t\t\t<a ' +
((__t = ((isFirstPage) ? 'data-bypass':'')) == null ? '' : __t) +
' data-route\n\t\t\t\t\thref="' +
((__t = ((isFirstPage) ? '#' : searchBase + '&start=0')) == null ? '' : __t) +
'" class="serverfirst">«</a>\n\t\t\t\t</li>\n\n\t\t\t\t<li ' +
((__t = ((isFirstPage) ? 'class="disabled"':'' )) == null ? '' : __t) +
' style="display: inline;">\n\t\t\t\t<a ' +
((__t = ((isFirstPage) ? 'data-bypass':'')) == null ? '' : __t) +
' data-route \n\t\t\t\t\thref="' +
((__t = ((isFirstPage) ? '#' : searchBase + '&start='+(perPage*(currentPage-1)))) == null ? '' : __t) +
'" \n\t\t\t\t\tclass="serverprevious">&lt;</a>\n\t\t\t\t</li>\n\n\t\t\t\t';
 for(p=beginIndex;p<endIndex;p++){ ;
__p += '\n\t\t\t\t';
 var isCurrentPage = (currentPage === p);;
__p += '\n\t\t\t\t<li ' +
((__t = ((isCurrentPage)? 'class="active"': '' )) == null ? '' : __t) +
' style="display: inline;">\n\t\t\t\t<a ' +
((__t = ((isCurrentPage) ? 'data-bypass':'')) == null ? '' : __t) +
' data-route\n\t\t\t\t\thref="' +
((__t = ((isCurrentPage) ? '#': searchBase + '&start=' + (perPage*p))) == null ? '' : __t) +
'" class="page">' +
((__t = (p+1)) == null ? '' : __t) +
'</a>\n\t\t\t\t</li>\n\t\t\t\t';
 } ;
__p += '\t\n\n\t\t\t\t<li ' +
((__t = ((isLastPage) ? 'class="disabled"': '')) == null ? '' : __t) +
' style="display: inline;">\n\t\t\t\t<a ' +
((__t = ((isLastPage) ? 'data-bypass':'')) == null ? '' : __t) +
' data-route\n\t\t\t\t\thref="' +
((__t = ((isLastPage) ? '#' : searchBase + '&start=' + (perPage*(currentPage+1)))) == null ? '' : __t) +
'" \n\t\t\t\t\tclass="servernext">&gt;</a>\n\t\t\t\t</li>\n\t\t\t\t<li ' +
((__t = ((isLastPage) ? 'class="disabled"': '')) == null ? '' : __t) +
' style="display: inline;">\n\t\t\t\t<a ' +
((__t = ((isLastPage) ? 'data-bypass':'')) == null ? '' : __t) +
' data-route\n\t\t\t\t\thref="' +
((__t = ((isLastPage) ? '#' : searchBase + '&start=' + (perPage*(totalPages-1)))) == null ? '' : __t) +
'" \n\t\t\t\t\tclass="serverlast">»</a>\n\t\t\t\t</li>\n\t\t\t</ul>\n\t\t</div>\n\t</div>\n</div>\n';
};
__p += '\n';

}
return __p
};

this["JST"]["app/templates/records.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<div class="records">\n  ';

    if (nodes !== undefined) {
      for (var i = 0; i < nodes.length; i++) {
        var node = nodes[i];
        var nodeParams = infoSolr.currentParams + '&fq=nid:%22' + escape(node.nid) + '%22';
  ;
__p += '\n  <article class="node node-biblio node-promoted node-teaser contextual-links-region article even iat-n clearfix">\n  <div class="node-inner">\n    <header class="node-header">\n      <h1 property="dc:title" datatype="" class="node-title" rel="nofollow">\n        <a href="/search/text#?' +
((__t = ( nodeParams )) == null ? '' : __t) +
'" rel="bookmark" data-route>\n          <h4>' +
((__t = ( node.title )) == null ? '' : __t) +
' (' +
((__t = ( node.count.toLocaleString() )) == null ? '' : __t) +
' pages)</h4>\n        </a>\n      </h1>\n      <p class="submitted">\n    </header>\n    <div class="node-content">\n      <span class="biblio-authors">\n        ';

          for (var j = 0; j < node.biblio_contributors.length; j++) {
            var name = node.biblio_contributors[j].name;
            var nameParams = infoSolr.currentParams + '&fq=author:%22' + escape(name) + '%22';
        ;
__p += '\n        <a href="/search/text#?' +
((__t = ( nameParams )) == null ? '' : __t) +
'" data-route>' +
((__t = ( name )) == null ? '' : __t) +
'</a>';

          if (j != node.biblio_contributors.length - 1) {
        ;
__p += ', and ';
 } else { ;
__p += '.';
 } ;
__p += '\n        ';

          }
        ;
__p += '\n      </span>\n      \n     <span class="biblio-title-chicago">\n       ' +
((__t = ( node.biblio_edition )) == null ? '' : __t);
 if (node.biblio_edition == '') { ;
__p += ',';
 } ;
__p += '\n       ' +
((__t = ( node.biblio_publisher )) == null ? '' : __t) +
'\n       ' +
((__t = ( node.biblio_place_published )) == null ? '' : __t) +
', ' +
((__t = ( node.biblio_year )) == null ? '' : __t) +
'.\n    </div>\n  </div>\n</article>\n  ';

      }
    }
  ;
__p += '\n</div>\n';

}
return __p
};

this["JST"]["app/templates/result.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<article class="node node-biblio node-promoted node-teaser contextual-links-region article even iat-n clearfix">\n  ';
 if (doc.htid !== undefined) { ;
__p += '\n  <div class="node-inner">\n    <header class="node-header">\n      <h1 property="dc:title" datatype="" class="node-title" rel="nofollow">\n        Page ';
 if (doc.number === undefined || doc.number.value == '') { ;
__p += 'sequence number ' +
((__t = ( doc.seq.value )) == null ? '' : __t);
 } else { ;
__p +=
((__t = ( doc.number.value )) == null ? '' : __t);
 } ;
__p += '\n      </h1>\n      <nav class="clearfix" style="margin-bottom: 10px;">\n        <ul class="links inline">\n          <li class="node-readmore first">\n            <a href="http://babel.hathitrust.org/cgi/pt?id=' +
((__t = ( doc.htid.value )) == null ? '' : __t) +
';view=1up;seq=' +
((__t = ( doc.seq.value )) == null ? '' : __t) +
'" target="_blank">\n            View page at HathiTrust\n            </a>\n          </li><li class="last"><a href="/record/' +
((__t = ( doc.record.value )) == null ? '' : __t) +
'">Metadata</a></li>\n        </ul>\n      </nav>\n    </header>\n    <div class="node-content">\n      ';
 if (typeof content!=="undefined") { ;
__p += '\n        <p class="search-snippet">' +
((__t = (doc.content.valuehl)) == null ? '' : __t) +
'</p>\n      ';
 } ;
__p += '\n    </div>\n  </div>\n  ';
 } ;
__p += '\n</article>\n\n';

}
return __p
};

this["JST"]["app/templates/results-header.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {

 if (total > 0) { ;
__p += '\n  <div class="navbar span12">\n    <div class="navbar-inner">\n      <div class="container">\n        <ul class="nav pull-left">\n          <li>\n            <p class="navbar-text"><span><strong>' +
((__t = (total.toLocaleString() )) == null ? '' : __t) +
' </strong>pages found</span></p>\n          </li>\n        </ul>\n      </div>\n    </div>\n  </div>\n';
 } ;
__p += '\n';
 if (noResultsFound) { ;
__p += '\n\t<div class="alert alert-warn" class="span12">No results found</div>\n';
 } ;
__p += '\n';

}
return __p
};

this["JST"]["app/templates/search.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<form id="search-form" class="form-search">\n  <div class="control-group well">\n    <div class="controls">\n      <input id="search-query" type="text" value="' +
((__t = (printQuery)) == null ? '' : __t) +
'" style="width: 47%" class="search-query">\n      <button type="submit" class="btn">\n        <i class="icon-search"></i> Search</button>\n    </div>\n  </div>\n</form>\n';

}
return __p
};