define([
  'jquery',
  'lodash',
  'backbone'
  ], function ($, _, Backbone) {

  var FacetAuthorView = Backbone.View.extend({
    el: $('#facet_author'),
    template: 'facet-author',

    initialize: function () {
      this.collection.on('reset', this.render, this);
    },

    data: function () {
      return this.collection;
    }

  });

  return FacetAuthorView;

});
