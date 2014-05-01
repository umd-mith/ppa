define([
  'jquery',
  'lodash',
  'backbone'
  ], function ($, _, Backbone) {

    var RecordsView = Backbone.View.extend({
      template: 'records',

      initialize: function () {
        this.model.on('reset', this.render, this);
      },

      data: function () {
        return this.model.toJSON();
      }
    });

    return RecordsView;
  });
