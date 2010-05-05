/* $Id$ */

(function ($) {
  Drupal.color = {
    logoChanged: false,
    callback: function(context, settings, form, farb, height, width) {

      // Background
      $('#preview-p', form).css('backgroundColor', $('#palette input[name="palette[background]"]', form).val());

      // Navigation
      $('#navigation-p', form).css('backgroundColor', $('#palette input[name="palette[navigation]"]', form).val());

      // Site slogan
      $('#site-slogan-p', form).css('color', $('#palette input[name="palette[slogan]"]', form).val());

      // Border
      $('#page-p', form).css('border-color', $('#palette input[name="palette[border]"]', form).val());

      // Text
      $('#preview #preview-main h2, #preview #preview-main p', form).css('color', $('#palette input[name="palette[text]"]', form).val());
      $('#preview #preview-content a', form).css('color', $('#palette input[name="palette[link]"]', form).val());
    }

  };
})(jQuery);
