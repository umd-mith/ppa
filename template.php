<?php
// $Id$

/**
 * @file
 * Theme functions overrides.
 */

/**
 * Override or insert variables into the html template.
 */
function corolla_preprocess_html(&$variables) {
  // Add reset.css
  drupal_add_css($data = path_to_theme() . '/reset.css', $options['type'] = 'file', $options['weight'] = CSS_SYSTEM - 2);

  // Add conditional stylesheets for IE
  drupal_add_css(path_to_theme() . '/ie8.css', array('weight' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 8', '!IE' => FALSE), 'preprocess' => FALSE));
  drupal_add_css(path_to_theme() . '/ie7.css', array('weight' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 7', '!IE' => FALSE), 'preprocess' => FALSE));
  drupal_add_css(path_to_theme() . '/ie6.css', array('weight' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 6', '!IE' => FALSE), 'preprocess' => FALSE));

  /* Add dynamic stylesheet */
  ob_start();
  include('dynamic.css.php');
  $dynamic_styles = ob_get_contents();
  ob_end_clean();
  drupal_add_css($data = $dynamic_styles, $options['type'] = 'inline', $options['weight'] = CSS_SYSTEM - 1);
}

/**
 * Override or insert variables into the html template.
 */
function corolla_process_html(&$variables) {
  // Hook into color module
  if (module_exists('color')) {
    _color_html_alter($variables);
  }
}

/**
 * Override or insert variables into the page template.
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
  // Provide a variable to check if the page is in the overlay.
  if (module_exists('overlay')) {
    $variables['in_overlay'] = (overlay_get_mode() == 'child');
  }
  else {
    $variables['in_overlay'] = FALSE;
  }

   // Add variables with weight value for each main column
  $variables['weight']['content'] = 0;
  $variables['weight']['sidebar-first'] = 'disabled';
  $variables['weight']['sidebar-second'] = 'disabled';
  if ($variables["page"]["sidebar_first"]) {
    $variables['weight']['sidebar-first'] = theme_get_setting('sidebar_first_weight');
  }
  if ($variables["page"]["sidebar_second"]) {
    $variables['weight']['sidebar-second'] = theme_get_setting('sidebar_second_weight');
  }

  // Add $main_columns_number variable (used in page-*.tpl.php files)
  $columns = 0;
  foreach (array('content', 'sidebar_first', 'sidebar_second') as $n) {
    if ($variables["page"]["$n"]) {
      $columns++;
    }
  }
  $variables['main_columns_number'] = $columns;  

  // Add $navigation variable to page.tpl.php. Unlike built-in $main_menu variable, it supports nested menus
  if (isset($variables['main_menu'])) {
    $pid = variable_get('menu_main_links_source', 'main-menu');
    $tree = menu_tree($pid);
    $variables['navigation'] = drupal_render($tree);
  } else {
    $variables['navigation'] = FALSE;
  }
}

/**
 * Override or insert variables into the block template.
 */
function corolla_preprocess_block(&$variables) {
  // Remove "block" class from blocks in "Main page content" region
  if ($variables['elements']['#block']->region == 'content') {
    foreach ($variables['classes_array'] as $key => $val) {
      if ($val == 'block') {
        unset($variables['classes_array'][$key]);
      }
    }
  }
}

/**
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return
 *   A string containing the breadcrumb output.
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
 * Returns HTML for a "more" link, like those used in blocks.
 *
 * @param $variables
 *   An associative array containing:
 *   - url: The url of the main page.
 *   - title: A descriptive verb for the link, like 'Read more'.
 */
function corolla_more_link($variables) {
  return '<div class="more-link">' . t('<a href="@link" title="@title">more ›</a>', array('@link' => check_url($variables['url']), '@title' => $variables['title'])) . '</div>';
}

/**
 * Returns HTML for status and/or error messages, grouped by type.
 *
 * An invisible heading identifies the messages for assistive technology.
 * Sighted users see a colored box. See http://www.w3.org/TR/WCAG-TECHS/H69.html
 * for info.
 *
 * @param $variables
 *   An associative array containing:
 *   - display: (optional) Set to 'status' or 'error' to display only messages
 *     of that type.
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
 * Returns HTML for a sort icon.
 *
 * @param $variables
 *   An associative array containing:
 *   - style: Set to either 'asc' or 'desc', this determines which icon to show.
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

