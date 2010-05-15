<?php
// $Id$

/**
 * Override or insert variables into the html template.
 */
function corolla_preprocess_html(&$variables) {
}

/**
 * Override or insert variables into the html template.
 */
function corolla_process_html(&$variables) {
  // Hook into color.module
  if (module_exists('color')) {
    _color_html_alter($variables);
  }

  // Add conditional stylesheets for IEs. TODO: move this to info file when this patch gets into core http://drupal.org/node/522006
  $variables['styles'] .= "\n<!--[if lte IE 8]>\n" . '<link type="text/css" rel="stylesheet" media="all" href="' . file_create_url(path_to_theme() . '/ie8.css') . '" />' . "\n" . "<![endif]-->\n";
  $variables['styles'] .= "\n<!--[if lte IE 7]>\n" . '<link type="text/css" rel="stylesheet" media="all" href="' . file_create_url(path_to_theme() . '/ie7.css') . '" />' . "\n" . "<![endif]-->\n";
  $variables['styles'] .= "\n<!--[if lte IE 6]>\n" . '<link type="text/css" rel="stylesheet" media="all" href="' . file_create_url(path_to_theme() . '/ie6.css') . '" />' . "\n" . "<![endif]-->\n";
}

/**
 * Override or insert variables into the page template.
 */
function corolla_process_page(&$variables) {
  // Hook into color.module 
  if (module_exists('color')) {
    _color_page_alter($variables);
  }
}

/**
 * Override or insert variables into the block template.
 */
function corolla_preprocess_block(&$variables) {
  // Remove "block" class from "Main page content" block
  if ( $variables['block']->module == 'system' && $variables['block']->delta == 'main') {
    unset($variables['classes_array']['0']);
  }
}

/**
 * Disable core stylesheets
 */
function corolla_css_alter(&$css) {
  unset($css[drupal_get_path('module', 'system') . '/system-menus.css']);
  unset($css[drupal_get_path('module', 'system') . '/system-menus-rtl.css']);
}

/**
 * Override of theme_tablesort_indicator().
 *
 * Use custom arrow images
 */
function corolla_tablesort_indicator($variables) {
  if ($variables['style'] == "asc") {
    return theme('image', array('path' => path_to_theme() . '/images/tablesort-ascending.png', 'alt' => t('sort ascending'), 'title' => t('sort ascending')));
  }
  else {
    return theme('image', array('path' => path_to_theme() . '/images/tablesort-descending.png', 'alt' => t('sort descending'), 'title' => t('sort descending')));
  }
}

/**
 * Override of theme_messages().
 *
 * If there are serveral messages, print them in separate divs.
 */
function corolla_messages($variables) {
  $display = $variables['display'];
  $output = '';
  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
  );
  foreach (drupal_get_messages($display) as $type => $messages) {
    if (!empty($status_heading[$type])) {
      $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>\n";
    }
    foreach ($messages as $message) {
      $output .= "<div class=\"messages message-$type\">\n";
      $output .= $message;
      $output .= "</div>\n";
    }
  }
  return $output;
}

/**
 * Override of theme_more_link().
 *
 * Append arrow.
 */
function corolla_more_link($variables) {
  return '<div class="more-link">' . t('<a href="@link" title="@title">more ›</a>', array('@link' => check_url($variables['url']), '@title' => $variables['title'])) . '</div>';
}

/**
 * Override of theme_breadcrumb().
 *
 * Wrap separator with span element
 */
function corolla_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb">' . implode('<span class="separator">»</span>', $breadcrumb) . '</div>';
    return $output;
  }
}

