<?php
// Corolla by Adaptivethemes.com

/**
 * Override or insert vars into the html template.
 */
function corolla_preprocess_html(&$vars) {
  global $theme_key;

  $theme_name = 'corolla';

  // Load the media queries styles
  $media_queries_css = array(
    $theme_name . '.responsive.style.css',
  );
  load_subtheme_media_queries($media_queries_css, $theme_name);

  // Load IE specific stylesheets
  $ie_files = array(
    'lte IE 9' => 'ie-lte-9.css',
  );
  load_subtheme_ie_styles($ie_files, $theme_name);

  // Add a class for the active color scheme
  if (module_exists('color')) {
    $class = check_plain(get_color_scheme_name($theme_key));
    $vars['classes_array'][] = 'color-scheme-' . drupal_html_class($class);
  }

  // Add class for the active theme
  $vars['classes_array'][] = drupal_html_class($theme_key);

  // Add theme settings classes
  $settings_array = array(
    'box_shadows',
    'body_background',
    'menu_bullets',
    'content_corner_radius',
    'tabs_corner_radius',
  );
  foreach ($settings_array as $setting) {
    $vars['classes_array'][] = theme_get_setting($setting);
  }
}

/**
 * Hook into the color module.
 */
function corolla_process_html(&$vars) {
  if (module_exists('color')) {
    _color_html_alter($vars);
  }
}
function corolla_process_page(&$vars) {
  if (module_exists('color')) {
    _color_page_alter($vars);
  }
}

/**
 * Override or insert vars into the block template.
 */
function corolla_preprocess_block(&$vars) {
  if ($vars['block']->module == 'superfish' || $vars['block']->module == 'nice_menu') {
    $vars['content_attributes_array']['class'][] = 'clearfix';
  }
  if (!$vars['block']->subject) {
    $vars['content_attributes_array']['class'][] = 'no-title';
  }
  if ($vars['block']->region == 'menu_bar' || $vars['block']->region == 'header') {
    $vars['title_attributes_array']['class'][] = 'element-invisible';
  }
}

/**
 * Override or insert vars into the field template.
 */
//function corolla_preprocess_field(&$vars) {
//  $element = $vars['element'];
//  $vars['classes_array'][] = 'view-mode-' . $element['#view_mode'];
//  $vars['field_view_mode'] = $element['#view_mode'] ? $element['#view_mode'] : '';
//  if ($element['#field_type'] == 'image') {
//    $vars['image_caption_teaser'] = FALSE;
//    $vars['image_caption_full'] = FALSE;
//    if (theme_get_setting('image_caption_teaser') == 1) {
//      $vars['image_caption_teaser'] = TRUE;
//    }
//    if (theme_get_setting('image_caption_full') == 1) {
//      $vars['image_caption_full'] = TRUE;
//    }
//  }
//}

/**
 * Returns HTML for a sort icon.
 *
 * @param $vars
 *   An associative array containing:
 *   - style: Set to either 'asc' or 'desc', this determines which icon to show.
 */
function corolla_tablesort_indicator($vars) {
  // Use custom arrow images.
  if ($vars['style'] == 'asc') {
    return theme('image', array('path' => path_to_theme() . '/css/images/tablesort-ascending.png', 'alt' => t('sort ascending'), 'title' => t('sort ascending')));
  }
  else {
    return theme('image', array('path' => path_to_theme() . '/css/images/tablesort-descending.png', 'alt' => t('sort descending'), 'title' => t('sort descending')));
  }
}

/**
 * Returns HTML for a fieldset form element and its children.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #attributes, #children, #collapsed, #collapsible,
 *     #description, #id, #title, #value.
 *
 * @ingroup themeable
 */
function corolla_fieldset($vars) {

  $element = $vars['element'];
  element_set_attributes($element, array('id'));
  _form_set_class($element, array('form-wrapper'));

  $output = '<fieldset' . drupal_attributes($element['#attributes']) . '>';

  // add a class to the fieldset wrapper if a legend exists, in some instances they do not
  $class = "without-legend";

  if (!empty($element['#title'])) {

    // Always wrap fieldset legends in a SPAN for CSS positioning.
    $output .= '<legend><span class="fieldset-legend">' . $element['#title'] . '</span></legend>';

    // Add a class to the fieldset wrapper if a legend exists, in some instances they do not
    $class = 'with-legend';
  }

  $output .= '<div class="fieldset-wrapper ' . $class  . '">';

  if (!empty($element['#description'])) {
    $output .= '<div class="fieldset-description">' . $element['#description'] . '</div>';
  }

  $output .= $element['#children'];

  if (isset($element['#value'])) {
    $output .= $element['#value'];
  }

  $output .= '</div>';
  $output .= "</fieldset>\n";

  return $output;
}
