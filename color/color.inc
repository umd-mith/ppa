<?php
// $Id$

$info = array(

  // Available colors and color labels used in theme.
  'fields' => array(
    'base' => t("Base"),
    'background' => t("Background"),
    'text' => t('Text'),
    'link' => t('Link'),
    'linkhover' => t('Hovered Link'),
    'linkunderline' => t('Link underline'),
    'slogan' => t('Slogan'),
    'navigation' => t('Navigation'),
    'navigationhover' => t('Navigation hover'),
    'tab' => t('Tab'),
    'blocktitle' => t('Block title'),
    'border' => t('Border'),
    'borderstrong' => t('Border strong'),
    'fieldset' => t('Fieldset'),
    'fieldsetborder' => t('Fieldset border'),
  ),

  // Pre-defined color schemes.
  'schemes' => array(
    'default' => array(
      'title' => t('Mixed (Default)'),
      'colors' => array(
        'base' => '#ffffff',
        'background' => '#f8f8f8',
        'text' => '#2e2e2e',
        'link' => '#086782',
        'linkhover' => '#e25401',
        'linkunderline' => '#cfdde5',
        'slogan' => '#e25400',
        'navigation' => '#2e2e2d',
        'navigationhover' => '#e25402',
        'tab' => '#f5f4f3',
        'blocktitle' => '#779125',
        'border' => '#e1e1e1',
        'borderstrong' => '#c4c4c4',
        'fieldset' => '#fbfbfb',
        'fieldsetborder' => '#e1e1e2',
      ),
    ),
    'green' => array(
      'title' => t('Green'),
      'colors' => array(
        'base' => '#ffffff',
        'background' => '#fbfaf5',
        'text' => '#4a4a4a',
        'link' => '#0a80a1',
        'linkhover' => '#de4c01',
        'linkunderline' => '#cfdde5',
        'slogan' => '#819521',
        'navigation' => '#819521',
        'navigationhover' => '#2e2e2e',
        'tab' => '#f7f8ec',
        'blocktitle' => '#779125',
        'border' => '#e8f0be',
        'borderstrong' => '#d0dc93',
        'fieldset' => '#fafaf4',
        'fieldsetborder' => '#d0dc93',
      ),
    ),
    'purple' => array(
      'title' => t('Purple'),
      'colors' => array(
        'base' => '#ffffff',
        'background' => '#fefafb',
        'text' => '#2e2e2e',
        'link' => '#6c0d28',
        'linkhover' => '#e25401',
        'linkunderline' => '#eac8d1',
        'slogan' => '#e25401',
        'navigation' => '#6c0d28',
        'navigationhover' => '#83a80e',
        'tab' => '#fbf3f6',
        'blocktitle' => '#e25401',
        'border' => '#f7d6e2',
        'borderstrong' => '#d9a3b7',
        'fieldset' => '#fefafb',
        'fieldsetborder' => '#f7d6e2',
      ),
    ),
    'brown' => array(
      'title' => t('Brown'),
      'colors' => array(
        'base' => '#ffffff',
        'background' => '#fbfbf8',
        'text' => '#383838',
        'link' => '#2e6b8e',
        'linkhover' => '#de4c01',
        'linkunderline' => '#dce8ef',
        'slogan' => '#9d875c',
        'navigation' => '#8b7241',
        'navigationhover' => '#423a29',
        'tab' => '#f7f8ec',
        'blocktitle' => '#8b7241',
        'border' => '#ece9c5',
        'borderstrong' => '#dcd093',
        'fieldset' => '#fbfbf8',
        'fieldsetborder' => '#ddd7b6',
      ),
    ),
    'red' => array(
      'title' => t('Red'),
      'colors' => array(
        'base' => '#ffffff',
        'background' => '#fefbfa',
        'text' => '#2e2e2e',
        'link' => '#0d5b6c',
        'linkhover' => '#b9400e',
        'linkunderline' => '#dce8ef',
        'slogan' => '#ca592b',
        'navigation' => '#b9400e',
        'navigationhover' => '#423a29',
        'tab' => '#fcf3f2',
        'blocktitle' => '#b9400e',
        'border' => '#f7dfd6',
        'borderstrong' => '#d9a8ac',
        'fieldset' => '#fefbfa',
        'fieldsetborder' => '#f7dad6',
      ),
    ),
    'black' => array(
      'title' => t('Black'),
      'colors' => array(
        'base' => '#ffffff',
        'background' => '#fbfbfb',
        'text' => '#2e2e2e',
        'link' => '#086782',
        'linkhover' => '#a3a3a3',
        'linkunderline' => '#cfdde5',
        'slogan' => '#858585',
        'navigation' => '#2e2e2d',
        'navigationhover' => '#8f8f8f',
        'tab' => '#f5f4f3',
        'blocktitle' => '#545454',
        'border' => '#e0e0e0',
        'borderstrong' => '#c4c4c4',
        'fieldset' => '#fbfbfb',
        'fieldsetborder' => '#e1e1e1',
      ),
    ),
    'greenblue' => array(
      'title' => t('Green-blue'),
      'colors' => array(
        'base' => '#ffffff',
        'background' => '#fafcfd',
        'text' => '#2e2e2e',
        'link' => '#0280a2',
        'linkhover' => '#08a8d4',
        'linkunderline' => '#c8dcea',
        'slogan' => '#6a8208',
        'navigation' => '#6a8208',
        'navigationhover' => '#95b503',
        'tab' => '#f3f8fb',
        'blocktitle' => '#365d78',
        'border' => '#e3eff3',
        'borderstrong' => '#c1d5e1',
        'fieldset' => '#fafcfe',
        'fieldsetborder' => '#d6e9f7',
      ),
    ),
    'aquatic' => array(
      'title' => t('Aquatic'),
      'colors' => array(
        'base' => '#ffffff',
        'background' => '#fbfdfd',
        'text' => '#2e2e2e',
        'link' => '#186991',
        'linkhover' => '#43a86c',
        'linkunderline' => '#c8e1ea',
        'slogan' => '#3590ac',
        'navigation' => '#3590ac',
        'navigationhover' => '#43a86c',
        'tab' => '#f3f9fb',
        'blocktitle' => '#5c5c5c',
        'border' => '#e0e7eb',
        'borderstrong' => '#a3c6d9',
        'fieldset' => '#fafdfe',
        'fieldsetborder' => '#d6f0f7',
      ),
    ),
    'blue' => array(
      'title' => t('Blue'),
      'colors' => array(
        'base' => '#ffffff',
        'background' => '#fcfcfd',
        'text' => '#404040',
        'link' => '#096a86',
        'linkhover' => '#e63f19',
        'linkunderline' => '#b7ced5',
        'slogan' => '#36788c',
        'navigation' => '#2f6475',
        'navigationhover' => '#2e2e2e',
        'tab' => '#f4f6fa',
        'blocktitle' => '#2b4355',
        'border' => '#e3eaed',
        'borderstrong' => '#b7ced7',
      ),
    ),
  ),

  // Images to copy over.
  'copy' => array(
    'logo.png',
  ),

  // CSS files (excluding @import) to rewrite with new color scheme.
  'css' => array(
    'colors.css',
  ),

  // Gradient definitions.
  'gradients' => array(
    array(
      // (x, y, width, height).
      'dimension' => array(0, 0, 0, 0),
      // Direction of gradient ('vertical' or 'horizontal').
      'direction' => 'vertical',
      // Keys of colors to use for the gradient.
      'colors' => array('link', 'text'),
    ),
  ),

  // Color areas to fill (x, y, width, height).
  'fill' => array(),

  // Coordinates of all the theme slices (x, y, width, height)
  // with their filename as used in the stylesheet.
  'slices' => array(),

  // Reference color used for blending. Matches the base.png's colors.
  'blend_target' => '#ffffff',

  // Preview files.
  'preview_image' => 'color/preview.png',
  'preview_css' => 'color/preview.css',
  'preview_js' => 'color/preview.js',
  'preview_html' => 'color/preview.html',

  // Base file for image generation.
  'base_image' => 'color/base.png',
);
