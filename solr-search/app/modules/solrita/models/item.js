define([
  'backbone'
  ], function (Backbone) {

    var SolrItem = Backbone.Model.extend({
      defaults: {
        doc: {}
      }
    });

    return SolrItem;
  });
