<?php
// $Id$
 
$info = array(

  // Available colors and color labels used in theme.
  'fields' => array(
    'base' => t("Base"),
    'page' => t("Page"),
    'border' => t('Borders'),
    'text' => t('Text'),
    'link' => t('Link'),
    'linkhover' => t('Hovered Link'),
    'line' => t('Header line'),
    'tab' => t('Tabs'),
    'tabborder' => t('Tabs border'),
    'slogan' => t('Slogan'),
  ),

  // Pre-defined color schemes.
  'schemes' => array(
    'default' => array(
      'title' => t('Green (default)'),
      'colors' => array(
        'base' => '#f8f8ef',
        'page' => '#FCFCF8',
        'border' => '#e5e5d3',
        'text' => '#63565F',
        'link' => '#2c98b9',
        'linkhover' => '#a2c32d',
        'line' => '#bee043',
        'tab' => '#efefdb',
        'tabborder' => '#d7d7a8',
        'slogan' => '#8d8d8d',
      ),
    ),
    'blue' => array(
      'title' => t('Blue'),
      'colors' => array(
        'base' => '#f7fbfc',
        'page' => '#ffffff',
        'border' => '#d1e6ef',
        'text' => '#63565F',
        'link' => '#349dbe',
        'linkhover' => '#98b72b',
        'line' => '#45a2bf',
        'tab' => '#e6f1f7',
        'tabborder' => '#94b6c4',
        'slogan' => '#45a2bf',
      ),
    ),
    'orange' => array(
      'title' => t('Orange'),
      'colors' => array(
        'base' => '#fefcf3',
        'page' => '#fffdff',
        'border' => '#fbe2bb',
        'text' => '#63565F',
        'link' => '#ef8022',
        'linkhover' => '#f30012',
        'line' => '#f5bc5f',
        'tab' => '#fcf4d1',
        'tabborder' => '#f5bc5f',
        'slogan' => '#ef8022',
      ),
    ),
    'brown' => array(
      'title' => t('Brown'),
      'colors' => array(
        'base' => '#faf9ef',
        'page' => '#fffdff',
        'border' => '#fbe2bb',
        'text' => '#63565F',
        'link' => '#be9636',
        'linkhover' => '#f16918',
        'line' => '#d9ba72',
        'tab' => '#f2eed3',
        'tabborder' => '#ccc180',
        'slogan' => '#be9636',
      ),
    ),
    'gray' => array(
      'title' => t('Gray'),
      'colors' => array(
        'base' => '#f9f9f9',
        'page' => '#fffdff',
        'border' => '#dcdcdc',
        'text' => '#63565F',
        'link' => '#259fc0',
        'linkhover' => '#7ec5db',
        'line' => '#b5b5b5',
        'tab' => '#eeeeee',
        'tabborder' => '#cacaca',
        'slogan' => '#9a8a95',
      ),
    ),
    'bluegreen' => array(
      'title' => t('Blue-green'),
      'colors' => array(
        'base' => '#f7f9fb',
        'page' => '#ffffff',
        'border' => '#d7e1ec',
        'text' => '#63565F',
        'link' => '#38a9ca',
        'linkhover' => '#94aa12',
        'line' => '#b1cc16',
        'tab' => '#ecf2f9',
        'tabborder' => '#abc5de',
        'slogan' => '#38a9ca',
      ),
    ),
    'aquamarine' => array(
      'title' => t('Aquamarine'),
      'colors' => array(
        'base' => '#eff7f6',
        'page' => '#ffffff',
        'border' => '#cce4e1',
        'text' => '#63565F',
        'link' => '#54a89e',
        'linkhover' => '#aca24b',
        'line' => '#9bcdc7',
        'tab' => '#dcedeb',
        'tabborder' => '#a5cfcb',
        'slogan' => '#54a89e',
      ),
    ),
    'purple' => array(
      'title' => t('Purple'),
      'colors' => array(
        'base' => '#fbf6fa',
        'page' => '#fffdff',
        'border' => '#ebd0e4',
        'text' => '#63565F',
        'link' => '#ab589b',
        'linkhover' => '#f32b2b',
        'line' => '#ab589b',
        'tab' => '#f5e7f2',
        'tabborder' => '#ddafd1',
        'slogan' => '#ab589b',
      ),
    ),
    'yellow' => array(
      'title' => t('Yellow'),
      'colors' => array(
        'base' => '#faf7f2',
        'page' => '#fffdff',
        'border' => '#ebdfcb',
        'text' => '#63565F',
        'link' => '#38a9ca',
        'linkhover' => '#eda90d',
        'line' => '#f7c430',
        'tab' => '#f4eee3',
        'tabborder' => '#d6c19a',
        'slogan' => '#eda90d',
      ),
    ),
    'pink' => array(
      'title' => t('Pink'),
      'colors' => array(
        'base' => '#fbf5f8',
        'page' => '#fffdff',
        'border' => '#e9d1dd',
        'text' => '#63565F',
        'link' => '#2baacc',
        'linkhover' => '#e4317d',
        'line' => '#e4317d',
        'tab' => '#f5dfeb',
        'tabborder' => '#dab2c6',
        'slogan' => '#e4317d',
      ),
    ),
  ),
 
  // Images to copy over.
  'copy' => array(
    'logo.png',
  ),
 
  // CSS files (excluding @import) to rewrite with new color scheme.
  'css' => array(
    'color.css',
  ),

  // Gradient definitions.
  'gradients' => array(
    array(
      // (x, y, width, height).
      'dimension' => array(0, 0, 200, 20),
      // Direction of gradient ('vertical' or 'horizontal').
      'direction' => 'vertical',
      // Keys of colors to use for the gradient.
      'colors' => array('link', 'text'),
    ),
  ),

  // Color areas to fill (x, y, width, height).
  'fill' => array(
    'link' => array(0, 0, 100, 600),
  ),
 
  // Coordinates of all the theme slices (x, y, width, height)
  // with their filename as used in the stylesheet.
  'slices' => array(
  ),
 
  // Reference color used for blending. Matches the base.png's colors.
  'blend_target' => '#ffffff',
 
  // Preview files.
  'preview_image' => 'color/preview.png',
  'preview_css' => 'color/preview.css',
 
  // Base file for image generation.
  'base_image' => 'color/base.png',
);
