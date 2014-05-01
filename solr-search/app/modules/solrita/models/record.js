define([
  'jquery',
  'lodash',
  'backbone'
  ], function ($, _, Backbone) {

    var SolrRecord = Backbone.Model.extend({
      url: function() {
        var value = '/api/node/';
        var nid_counts = this.get("nid_counts");
        var nids = _.map(nid_counts, function(nid_count) { return nid_count[0]; });
        return value + nids.join(',');
      },

      defaults: {
        'nodes': [],
        'infoSolr': {}
      },

      parse: function (response) {
        var nodes = [];
        if (Array.isArray(response)) {
          nodes = response;
        } else {
          nodes = [ response ];
        }

        var nid_counts = this.get('nid_counts');
        for (var i = 0; i < nid_counts.length; i++) {
          nodes[i].count = nid_counts[i][1];
        }
        this.trigger("parse");

        return { 'nodes': nodes, 'infoSolr': this.get('infoSolr') };
      }
    });

    return SolrRecord;
  });
