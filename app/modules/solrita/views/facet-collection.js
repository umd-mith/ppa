define([
  'jquery',
  'lodash',
  'backbone'
  ], function ($, _, Backbone) {

  var FacetCollectionView = Backbone.View.extend({
    el: $('#facet_collection'),
    template: 'facet-collection',

    initialize: function () {
      this.collection.on('reset', this.render, this);
    },

    data: function () {
      return this.collection;
    }

  });

  return FacetCollectionView;

});
