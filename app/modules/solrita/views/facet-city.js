define([
  'jquery',
  'lodash',
  'backbone'
  ], function ($, _, Backbone) {

  var FacetCityView = Backbone.View.extend({
    el: $('#facet_city'),
    template: 'facet-city',

    initialize: function () {
      this.collection.on('reset', this.render, this);
    },

    data: function () {
      return this.collection;
    }

  });

  return FacetCityView;

});
