<?php
// $Id$

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/garland.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 */
?>
<?php if ($page['content']): ?>
  <?php $column++; ?>
  <div id="content-wrapper">
    <div id="content" class="<?php if ($column == 1): ?> first<?php endif; ?><?php if ($column == $main_columns_number): ?> last<?php endif; ?>">

      <div id="page" class="clearfix">
        <?php if ($breadcrumb): ?><div id="breadcrumb" class="clearfix"><?php print $breadcrumb; ?></div><?php endif; ?>
        <?php if ($messages): ?><div id="messages"><?php print $messages; ?></div><?php endif; ?>
        <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>

        <a id="main-content" class="element-invisible"></a>
        <?php print render($title_prefix); ?>

        <?php if ($title && !isset($node)): ?>
          <h1 class="page-title"><?php print $title ?></h1>
        <?php endif; ?>

        <?php print render($title_suffix); ?>

        <?php print render($page['help']); ?>
        <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
        <?php print render($page['content']); ?>
        <?php print $feed_icons; ?>
      </div> <!-- /#page -->

      <div id="closure" class="clearfix">
        <?php if ($secondary_menu): ?>
          <div id="secondary-menu">
            <?php print theme('links__system_secondary_menu', array('links' => $secondary_menu, 'attributes' => array('class' => array('links', 'clearfix')), 'heading' => t('Secondary menu'))); ?>
          </div> <!-- /#secondary-menu -->
        <?php endif; ?>

        <div id="theme-info">
          Theme by <a href="http://www.kiwi-themes.com">Jarosław Foksa</a>
        </div>
      </div>

    </div> <!-- /#content -->

  </div> <!-- /#content-wrapper -->
<?php endif; ?>
