define([
  'jquery',
  'lodash',
  'backbone'
  ], function ($, _, Backbone) {

    var ResultView = Backbone.View.extend({

      template: 'result',

      tagName: 'div',

      data: function () {
        return { doc: this.model.toJSON() };
      }

    });

    return ResultView;
  });
