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
    'slogan' => t('Slogan'),
    'navigation' => t('Navigation'),
    'blocktitle' => t('Block title'),
    'border' => t('Border'),
    'borderstrong' => t('Border strong'),
  ),

  // Pre-defined color schemes.
  'schemes' => array(
    'default' => array(
      'title' => t('Default'),
      'colors' => array(
        'base' => '#ffffff',
        'background' => '#f8f8f8',
        'text' => '#2e2e2e',
        'link' => '#086782',
        'linkhover' => '#e25401',
        'slogan' => '#e25400',
        'navigation' => '#2e2e2d',
        'blocktitle' => '#779125',
        'border' => '#e1e1e1',
        'borderstrong' => '#c4c4c4',
      ),
    ),
    'green' => array(
      'title' => t('Green'),
      'colors' => array(
        'base' => '#ffffff',
        'background' => '#fbfcf2',
        'text' => '#4a4a4a',
        'link' => '#0a80a1',
        'linkhover' => '#de4c01',
        'slogan' => '#819521',
        'navigation' => '#819521',
        'blocktitle' => '#779125',
        'border' => '#e8f0be',
        'borderstrong' => '#d0dc93',
      ),
    ),
    'blue' => array(
      'title' => t('Blue'),
      'colors' => array(
        'base' => '#ffffff',
        'background' => '#fbfcfd',
        'text' => '#63565f',
        'link' => '#017292',
        'linkhover' => '#e63f19',
        'slogan' => '#36788c',
        'navigation' => '#36788c',
        'blocktitle' => '#779125',
        'border' => '#d5e2e7',
        'borderstrong' => '#b7ced7',
      ),
    ),
    'purple' => array(
      'title' => t('Purple'),
      'colors' => array(
        'base' => '#ffffff',
        'background' => '#fefafb',
        'text' => '#2e2e2e',
        'link' => '#6c0d28',
        'linkhover' => '#83a80e',
        'slogan' => '#e25401',
        'navigation' => '#6c0d28',
        'blocktitle' => '#e25401',
        'border' => '#f7d6e2',
        'borderstrong' => '#d9a3b7',
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
