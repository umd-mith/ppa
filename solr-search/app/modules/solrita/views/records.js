define([
  'jquery',
  'lodash',
  'backbone',
  'plugins/spin'
  ], function ($, _, Backbone, Spinner) {

    var RecordsView = Backbone.View.extend({
      spinner: {},

      template: 'records',

      initialize: function () {
        this.model.on('reset', this.render, this);
        this.model.on('fetch', this.start, this);
        this.model.on('parse', this.stop, this);
        this.spinner = new Spinner({
          color: "#777"
        });
      },

      data: function () {
        return this.model.toJSON();
      },

      start: function () {
        this.spinner.spin(document.getElementById('results-header'));
      },

      stop: function () {
        this.spinner.stop();
      },

      cleanup: function () {
        this.model.off(null, null, this);
      }
    });

    return RecordsView;
  });
