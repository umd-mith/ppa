define([
  'jquery',
  'lodash',
  'backbone',
  'modules/solrita/models/item',
  'app',
  'plugins/backbone.paginator'
  ], function ($, _, Backbone, model, app) {

    var SolrPaginatedCollection = Backbone.Paginator.requestPager.extend({

      initialize: function (models, options) {
        this.records = options.records;
        this.facetFields = app.defaultFacetFieldsArray;
        this.facetQueries = [];
        this.facetCounts = {};
      },

      model: model,
      records: {},

      paginator_core: {
        url: app.solrURL,
        jsonp: 'json.wrf'
      },

      paginator_ui: {
        firstPage: 0,
        currentPage: 0,
        perPage: app.defaultPerPage,
        sortField: app.defaultSortField
      },

      getRows: function () {
        for (var i = 0; i < this.facetQueries.length; i++) {
          if (this.facetQueries[i].match("^nid:")) {
            return this.perPage;
          }
        }
        return 0;
      },

      server_api: {
        'q': function () {
          return this.query;
        },
        'rows': function () {
          return this.getRows();
        },
        'start': function () {
          return this.currentPage * this.perPage;
        },
        'sort': function () {
          if (this.query !== '') {
            return '';
          } else {
            return this.sortField;
          }
        },
        'wt': 'json',
        'facet': 'true',
        'facet.mincount': 1,
        'facet.field': function () {
          return this.facetFields;
        },
        'fq': function () {
          return this.facetQueries;
        },
        'hl': function () {
          return true;
        },
        'hl.fl': function () {
          return '*';
        },
        'hl.fragsize': 2000,
        'hl.simple.pre': app.hlSimplePre,
        'hl.simple.post': app.hlSimplePro,
        'json.nl': 'arrarr'
      },

      query: app.defaultQuery,

      total: 0,

      solrStatus: 0,

      qTime: 0,

      facetFields: app.defaultFacetFieldsArray,

      facetQueries: [],

      facetCounts: {},

      infoSolr: {
        total: 0,
        printQuery: '',
        noResultsFound: false
      },

      parse: function (response, xhr) {
        this.total = response.response.numFound;
        this.totalPages = Math.ceil(this.total / this.perPage);
        this.solrStatus = xhr.status;
        this.qTime = xhr.QTime;
        this.facetCounts = response.facet_counts;

        /*var nids = [];
        for (var i = 0; i < this.facetCounts.facet_fields.nid.length; i++) {
          nids.push(this.facetCounts.facet_fields.nid[i][0]);
        }*/
        this.infoSolr = this.getInfoSolr();

        if (this.total > 0) {
          var r = this.records;
          this.records.trigger("fetch");
          this.records.set({ nid_counts: this.facetCounts.facet_fields.nid, infoSolr: this.infoSolr });
          this.records.fetch({
            success: function() {
              r.trigger("reset");
            }
          });
        } else {
          this.records.set({ nodes: [], nid_counts: [], infoSolr: this.infoSolr });
          this.records.trigger("reset");
        }
        var docs = this._getDocsWithValueAndValuehl(response.response.docs, response.highlighting);
        this.trigger("parse");
        return docs;
      },

      _getDocsWithValueAndValuehl: function (docs, highlighting) {
        var self = this;
        $.each(docs, function (nDoc, doc) {
          var id = doc.id;
          $.each(doc, function (field, value) {
            if (field !== "id") {
              doc[field] = self._getValuehl(highlighting, id, field, value);
            }
          });
        });
        return docs;
      },

      _getValuehl: function (highlighting, id, field, value) {
        var valuehl = {};
        var hl = highlighting[id][field];
        if (_.isArray(value)) {
          var multipleValuehl = [];
          $.each(value, function (nValue, currentValue) {
            var currentValuehl = currentValue;
            if (hl !== undefined) {
              $.each(hl, function (nValuehl, currenthl) {
                var currenthlNoTags = currenthl.replace(app.hlSimplePre, "").replace(app.hlSimplePro, "");
                if (currentValue === currenthlNoTags) {
                  currentValuehl = currenthl;
                }
              });
            }
            multipleValuehl.push({
              value: currentValue,
              valuehl: currentValuehl
            });
          });
          valuehl = multipleValuehl;
        } else {
          var simpleValuehl = value;
          if (hl !== undefined && hl[0] !== undefined) {
            simpleValuehl = hl[0];
          }
          valuehl = {
            value: value,
            valuehl: simpleValuehl
          };
        }
        return valuehl;
      },

      getInfoSolr: function () {
        var info = this.info();
        info.qTime = this.qTime;
        info.total = this.total;
				info.totalPages = this.totalPages;
        info.query = this.query;
        info.printQuery = (this.query !== app.defaultQuery) ? this.query : '';
        // I use _.escape because it changes " to &quote;
        // This is necessary to put the value in the search's input text
        info.printQuery = _.escape(info.printQuery);
        info.currentParams = this.getCurrentParams();
        info.startRecord = this.currentPage * this.perPage;
        info.beginIndex = Math.max(0, this.currentPage - app.paginationSize);
        info.endIndex = Math.min(info.beginIndex + (app.paginationSize * 2), info.totalPages);
        if (info.firstPage === undefined) {
          info.firstPage = 0;
        }
        info.searchBase = "/search/text#?" + info.currentParams;
        info.isFirstPage = (info.firstPage === info.currentPage);
        info.isLastPage = (info.currentPage + 1 === info.totalPages);
        info.noResultsFound = (this.total === 0);

        return info;
      },

      removeFacetQuery: function (facetQuery) {
        var index = $.inArray(facetQuery, this.facetQueries);
        if (index != -1) {
          this.facetQueries.splice(index, 1);
        }
      },

      search: function (options) {
        if (!_.isObject(options)) {
          options = {};
        }
        this.records.set({});
        this.records.trigger("reset");
        return this.pager(options);
      },

      getCurrentParams: function () {
        var params, sQuery = '';
        if (this.query !== '' && this.query !== app.defaultQuery) {
          sQuery = escape(this.query);
        }
        params = 'q=' + sQuery;
        $.each(this.facetQueries, function (index, value) {
          params = params + '&';
          params = params + 'fq=' + value;
        });
        if (this.perPage !== undefined && this.perPage !== app.defaultPerPage) {
          params = params + '&num=' + this.perPage;
        }
        if (this.sortField !== undefined && this.sortField !== '' && this.sortField !== app.defaultSortField) {
          params = params + '&sort=' + this.sortField;
        }
        return params;
      }
    });

    return SolrPaginatedCollection;

  });
