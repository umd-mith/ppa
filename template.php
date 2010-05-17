<?php
// $Id$

/**
 * @file
 * Theme callbacks for the corolla theme.
 */

/**
 * Implements hook_css_alter().
 */
function corolla_css_alter(&$css) {

  // Remove core stylesheets.
  // unset($css[drupal_get_path('module', 'system') . '/system-menus.css']);
  // unset($css[drupal_get_path('module', 'system') . '/system-menus-rtl.css']);
}

/**
 * Implements template_process_html().
 */
function corolla_process_html(&$variables) {

  // Hook into color module
  if (module_exists('color')) {
    _color_html_alter($variables);
  }

  // Add conditional stylesheets for IEs. TODO: move this to info file when this patch gets into core http://drupal.org/node/522006
  $variables['styles'] .= "\n<!--[if lte IE 8]>\n" . '<link type="text/css" rel="stylesheet" media="all" href="' . file_create_url(path_to_theme() . '/ie8.css') . '" />' . "\n" . "<![endif]-->\n";
  $variables['styles'] .= "\n<!--[if lte IE 7]>\n" . '<link type="text/css" rel="stylesheet" media="all" href="' . file_create_url(path_to_theme() . '/ie7.css') . '" />' . "\n" . "<![endif]-->\n";
  $variables['styles'] .= "\n<!--[if lte IE 6]>\n" . '<link type="text/css" rel="stylesheet" media="all" href="' . file_create_url(path_to_theme() . '/ie6.css') . '" />' . "\n" . "<![endif]-->\n";
}

/**
 * Implements template_process_page().
 */
function corolla_process_page(&$variables) {

  // Since the title and the shortcut link are both block level elements,
  // positioning them next to each other is much simpler with a wrapper div.
  if (!empty($variables['title_suffix']['add_or_remove_shortcut']) ) {
    // Add a wrapper div using the title_prefix and title_suffix render elements.
    $variables['title_prefix']['shortcut_wrapper'] = array(
      '#markup' => '<div class="shortcut-wrapper clearfix">',
      '#weight' => 100,
    );
    $variables['title_suffix']['shortcut_wrapper'] = array(
      '#markup' => '</div>',
      '#weight' => -99,
    );
    // Make sure the shortcut link is the first item in title_suffix.
    $variables['title_suffix']['add_or_remove_shortcut']['#weight'] = -100;
  }
}


/**
 * Implements template_preprocess_block().
 */
function corolla_preprocess_block(&$variables) {

  // Remove "block" class from "Main page content" block
  if ($variables['block']->module == 'system' && $variables['block']->delta == 'main') {
    unset($variables['classes_array']['0']);
  }
}

/**
 * Overrides theme_breadcrumb().
 */
function corolla_breadcrumb($variables) {

  // Wrap separator with span element.
  if (!empty($variables['breadcrumb'])) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';
    $output .= '<div class="breadcrumb">' . implode('<span class="separator">»</span>', $variables['breadcrumb']) . '</div>';
    return $output;
  }
}

/**
 * Overrides theme_more_link().
 */
function corolla_more_link($variables) {

  return '<div class="more-link">' . t('<a href="@link" title="@title">more ›</a>', array('@link' => check_url($variables['url']), '@title' => $variables['title'])) . '</div>';
}

/**
 * Overrides theme_status_messages().
 */
function corolla_status_messages($variables) {

  $output = '';
  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
  );

  // Print serveral messages in separate divs.
  foreach (drupal_get_messages($variables['display']) as $type => $messages) {
    if (!empty($status_heading[$type])) {
      $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>\n";
    }
    foreach ($messages as $message) {
      $output .= '<div class="messages message ' . $type . '">';
      $output .= $message;
      $output .= "</div>\n";
    }
  }

  return $output;
}

/**
 * Overrides theme_tablesort_indicator().
 */
function corolla_tablesort_indicator($variables) {

  // Use custom arrow images.
  if ($variables['style'] == 'asc') {
    return theme('image', array('path' => path_to_theme() . '/images/tablesort-ascending.png', 'alt' => t('sort ascending'), 'title' => t('sort ascending')));
  }
  else {
    return theme('image', array('path' => path_to_theme() . '/images/tablesort-descending.png', 'alt' => t('sort descending'), 'title' => t('sort descending')));
  }
}
