// $Id$
(function ($) {

Drupal.contextualLinks = Drupal.contextualLinks || {};

/**
 * Attach outline behavior for regions associated with contextual links.
 */
Drupal.behaviors.contextualLinks = {
  attach: function (context) {

    // Create "Edit page" button. Clicking on the button should toggle "show-contextual-links" class on body
    $('div.toolbar-drawer', context).once('edit-page-button', function () {
      var $toolbar = $(this);
      var $edit_page_button = $('<a id="edit-page" href="#">Edit page</a>').click(
        function () {
          $('body').toggleClass('show-contextual-links');
          return false;
        }
      );
      // Prepend the button
      $('div.toolbar-drawer').append($edit_page_button);
    });

    // Create trigger button. Clicking on the button should toggle "active" class on ul.contextual-links
    $('div.contextual-links-wrapper', context).once('contextual-links', function () {
      var $wrapper = $(this);
      var $links = $wrapper.find('ul.contextual-links');
      var $trigger = $('<a class="contextual-links-trigger" href="#"/></a>').click(
        function () {
          $wrapper.find('ul.contextual-links').toggleClass('active');
          return false;
        }
      );
      // Prepend the trigger.
      $links.end().prepend($trigger);
    });

  }
};

})(jQuery);
