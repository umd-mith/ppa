<?php

// Include the Google webfont definitions.
include_once(drupal_get_path('theme', 'corolla') . '/inc/gwf.inc');

/**
 * Override or insert vars into the html template.
 */
function corolla_preprocess_html(&$vars) {

  global $theme, $theme_key;

  $media_queries_css = array(
    'corolla-responsive-style.css',
    'corolla-responsive-gpanels.css'
  );
  foreach ($media_queries_css as $css) {
    $filepath = drupal_get_path('theme', 'corolla') . '/css/' . $css;
    drupal_add_css($filepath, array(
      'preprocess' => theme_get_setting('debug_media_queries') == 1 ? FALSE : TRUE,
      'group' => CSS_THEME,
      'media' => 'screen',
      'every_page' => TRUE,
      )
    );
  }

  // < IE 9
  drupal_add_css(path_to_theme() . '/css/ie/ie-lte-9.css', array(
    'group' => CSS_THEME,
    'browsers' => array(
      'IE' => 'lte IE 9',
      '!IE' => FALSE,
      ),
    'preprocess' => FALSE,
    )
  );

  if (module_exists('color')) {
    $info = color_get_info($theme);
    $info['schemes'][''] = array('title' => t('Custom'), 'colors' => array());
    $schemes = array();
    foreach ($info['schemes'] as $key => $scheme) {
      $schemes[$key] = $scheme['colors'];
    }
    $current_scheme = variable_get('color_' . $theme . '_palette', array());
    foreach ($schemes as $key => $scheme) {
      if ($current_scheme == $scheme) {
        $scheme_name = $key;
        break;
      }
    }
    if (empty($scheme_name)) {
      if (empty($current_scheme)) {
        $scheme_name = 'default';
      }
      else {
        $scheme_name = 'custom';
      }
    }
    $vars['classes_array'][] = 'color-scheme-' . drupal_html_class($scheme_name);
  }
  $vars['classes_array'][] = drupal_html_class($theme_key);
  $settings_array = array(
    'font_size',
    'box_shadows',
    'body_background',
    'menu_bullets',
    'content_corner_radius',
    'tabs_corner_radius',
    'image_alignment',
  );
  foreach ($settings_array as $setting) {
    $vars['classes_array'][] = theme_get_setting($setting);
  }

  $fonts = array(
    'bf'  => 'base_font',
    'snf' => 'site_name_font',
    'ssf' => 'site_slogan_font',
    'ptf' => 'page_title_font',
    'ntf' => 'node_title_font',
    'ctf' => 'comment_title_font',
    'btf' => 'block_title_font'
  );
  foreach($fonts as $key => $value) {

    $font_type = theme_get_setting($value . '_type');
    $font_value = theme_get_setting($value . (!empty($font_type) ? '_' . $font_type : ''));

    if ($font_type == '') {
      $vars['classes_array'][] = $font_value;
    }
    else {
      if ($font_type == 'gwf') {
        drupal_add_css('http://fonts.googleapis.com/css?family=' . $font_value, array('group' => CSS_THEME, 'type' => 'inline'));
      }

      $font_value = preg_replace('/[^\w\d_ -]/si', '', $font_value);
      $style_name = get_style_name($key, $font_type, $font_value);
      $vars['classes_array'][] = $style_name;

      switch($key) {
        case 'bf':
          drupal_add_css("body.$style_name, .$style_name .form-text {font-family: '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
          break;
        case 'snf':
          drupal_add_css("body.$style_name #site-name {font-family : '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
          break;
        case 'ssf':
          drupal_add_css("body.$style_name #site-slogan {font-family: '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
          break;
        case 'ptf':
          drupal_add_css("body.$style_name #page-title {font-family: '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
          break;
        case 'ntf':
          drupal_add_css("body.$style_name .article-title {font-family: '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
          break;
        case 'ctf':
          drupal_add_css("body.$style_name .comment-title {font-family: '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
          break;
        case 'btf':
          drupal_add_css("body.$style_name .block-title {font-family: '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
          break;
      }
    }
  }

  if (theme_get_setting('headings_styles_caps') == 1) {
    $vars['classes_array'][] = 'hs-caps';
  }
  if (theme_get_setting('headings_styles_weight') == 1) {
    $vars['classes_array'][] = 'hs-fwn';
  }
  if (theme_get_setting('headings_styles_shadow') == 1) {
    $vars['classes_array'][] = 'hs-ts';
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
function corolla_preprocess_field(&$vars) {
  $element = $vars['element'];
  $vars['classes_array'][] = 'view-mode-'. $element['#view_mode'];
  $vars['image_caption_teaser'] = FALSE;
  $vars['image_caption_full'] = FALSE;
  if(theme_get_setting('image_caption_teaser') == 1) {
    $vars['image_caption_teaser'] = TRUE;
  }
  if(theme_get_setting('image_caption_full') == 1) {
    $vars['image_caption_full'] = TRUE;
  }
  $vars['field_view_mode'] = '';
  $vars['field_view_mode'] = $element['#view_mode'];
}

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
  $fieldset_wrapper_class = '';

  $output = '<fieldset' . drupal_attributes($element['#attributes']) . '>';
  if (!empty($element['#title'])) {
    // Always wrap fieldset legends in a SPAN for CSS positioning.
    $output .= '<legend><span class="fieldset-legend">' . $element['#title'] . '</span></legend>';

    // add a class if a legend is being included
    $fieldset_wrapper_class = ' with-legend';
  }
  $output .= '<div class="fieldset-wrapper' . $fieldset_wrapper_class . '">';
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
