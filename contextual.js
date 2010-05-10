// $Id$
(function ($) {

Drupal.contextualLinks = Drupal.contextualLinks || {};

/**
 * Attach outline behavior for regions associated with contextual links.
 */
Drupal.behaviors.contextualLinks = {
  attach: function (context) {

    // Create "Edit page" button. Clicking on the button should toggle "show-contextual-links" class on body
    $('div.toolbar-drawer', context).once('edit-page-button').append('<a id="edit-page" href="#">Edit page</a>').click(function() {
      $('body').toggleClass('show-contextual-links');
      return false;
    });

    // Create trigger button. Clicking on the button should toggle "active" class on ul.contextual-links
    $('div.contextual-links-wrapper', context).once('contextual-links', function() {
      var $this = $(this);
      // Prepend the trigger.
      $(this).prepend($('<a class="contextual-links-trigger" href="#"/></a>').click(function() {
        $this.find('ul.contextual-links').toggleClass('active');
        return false;
      }));
    });

  }
};

})(jQuery);
