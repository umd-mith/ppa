/* $Id$ */

(function ($) {
  Drupal.color = {
    logoChanged: false,
    callback: function(context, settings, form, farb, height, width) {

      // Background
      $('#wrapper-p', form).css('backgroundColor', $('#palette input[name="palette[background]"]', form).val());

      // Navigation
      $('#navigation-p', form).css('backgroundColor', $('#palette input[name="palette[navigation]"]', form).val());

      // Border
      $('#page-p', form).css('border-color', $('#palette input[name="palette[border]"]', form).val());

      // Site slogan
      $('#slogan-p', form).css('color', $('#palette input[name="palette[slogan]"]', form).val());

      // Block title
      $('#sidebar-p .block-title-p', form).css('color', $('#palette input[name="palette[blocktitle]"]', form).val());

      // Link hovered
      $('#wrapper-p a:hover', form).css('color', $('#palette input[name="palette[linkhover]"]', form).val());

      // Link
      $('#wrapper-p a', form).css('color', $('#palette input[name="palette[link]"]', form).val());

      // Text
      $('#preview #preview-main h2, #preview #preview-main p', form).css('color', $('#palette input[name="palette[text]"]', form).val());
    }

  };
})(jQuery);
