<?php
// $Id$

/**
 * Override or insert variables into the html template.
 */
function corolla_preprocess_html(&$variables) {
  // Add reset CSS
  drupal_add_css($data = path_to_theme() . '/reset.css', $options['type'] = 'file', $options['weight'] = CSS_SYSTEM - 1);
}

/**
 * Override or insert variables into the html template.
 */
function corolla_process_html(&$variables) {
  // Hook into color.module
  if (module_exists('color')) {
    _color_html_alter($variables);
  }

  // Add conditional stylesheets for IE
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

  // Add $footer_columns_number variable to page.tpl.php file
  $columns = 0;
  foreach (array('first', 'second', 'third', 'fourth') as $n) {
    if ($variables["page"]["footer_column_$n"]) {
      $columns++;
    }
  }
  $variables['footer_columns_number'] = $columns;
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
  unset($css[drupal_get_path('module', 'system') . '/defaults.css']);
  unset($css[drupal_get_path('module', 'system') . '/defaults-rtl.css']);
  unset($css[drupal_get_path('module', 'system') . '/system.css']);
  unset($css[drupal_get_path('module', 'system') . '/system-rtl.css']);
  unset($css[drupal_get_path('module', 'system') . '/system-behavior.css']);
  unset($css[drupal_get_path('module', 'system') . '/system-behavior-rtl.css']);
  unset($css[drupal_get_path('module', 'system') . '/system-menus.css']);
  unset($css[drupal_get_path('module', 'system') . '/system-menus-rtl.css']);
  unset($css[drupal_get_path('module', 'system') . '/admin.css']);
  unset($css[drupal_get_path('module', 'system') . '/admin-rtl.css']);
  unset($css[drupal_get_path('module', 'node') . '/node.css']);
  unset($css[drupal_get_path('module', 'node') . '/node-rtl.css']);
  unset($css[drupal_get_path('module', 'user') . '/user.css']);
  unset($css[drupal_get_path('module', 'user') . '/user-rtl.css']);
  unset($css[drupal_get_path('module', 'poll') . '/poll.css']);
  unset($css[drupal_get_path('module', 'poll') . '/poll-rtl.css']);
  unset($css[drupal_get_path('module', 'search') . '/search.css']);
  unset($css[drupal_get_path('module', 'search') . '/search-rtl.css']);
  unset($css[drupal_get_path('module', 'comment') . '/comment.css']);
  unset($css[drupal_get_path('module', 'comment') . '/comment-rtl.css']);
  unset($css[drupal_get_path('module', 'forum') . '/forum.css']);
  unset($css[drupal_get_path('module', 'forum') . '/forum-rtl.css']);
  unset($css[drupal_get_path('module', 'book') . '/book.css']);
  unset($css[drupal_get_path('module', 'book') . '/book-rtl.css']);
  unset($css[drupal_get_path('module', 'aggregator') . '/aggregator.css']);
  unset($css[drupal_get_path('module', 'aggregator') . '/aggregator-rtl.css']);
  unset($css[drupal_get_path('module', 'field') . '/theme/field.css']);
  unset($css[drupal_get_path('module', 'field') . '/theme/field-rtl.css']);
  unset($css[drupal_get_path('module', 'filter') . '/filter.css']);
  unset($css[drupal_get_path('module', 'filter') . '/filter-rtl.css']);
  unset($css['misc/vertical-tabs.css']);
  unset($css['misc/vertical-tabs-rtl.css']);
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
 * Override of theme_field().
 *
 * Remove "clearfix" class from top-level DIV. This class causes problems on IE6/7 (can't disable hasLayout)
 */
function corolla_field($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<div class="field-label"' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</div>';
  }

  // Render the items.
  $output .= '<div class="field-items"' . $variables['content_attributes'] . '>';
  foreach ($variables['items'] as $delta => $item) {
    $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
    $output .= '<div class="' . $classes . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</div>';
  }
  $output .= '</div>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

  return $output;
}

/**
 * Override of theme_messages().
 *
 * Separate the status messages out into their own divs.
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
 * Override of theme_node_recent_block() and theme_node_recent_content().
 *
 * Make output for "Recent content" block consistent with other blocks
 */
function corolla_node_recent_block($variables) {
  $l_options = array('query' => drupal_get_destination());

  foreach ($variables['nodes'] as $node) {
    if (node_access('delete', $node) && node_access('update', $node)) { 
      $items[] = theme('node_recent_content', array('node' => $node)) . " (" . l(t('edit'), 'node/' . $node->nid . '/edit', $l_options) . " | " . l(t('delete'), 'node/' . $node->nid . '/delete', $l_options) . ")";
    }
    else
    $items[] = theme('node_recent_content', array('node' => $node));
  }

  if (user_access('access content overview')) {
    $items[] = theme('more_link', array('url' => url('admin/content'), 'title' => t('Show more content')));
  }

  return theme('item_list', array('items' => $items));

}
function corolla_node_recent_content($variables) {
  $node = $variables['node'];
  $output = l($node->title, 'node/' . $node->nid);
  $output .= theme('mark', array('type' => node_mark($node->nid, $node->changed)));
  return $output;
}

