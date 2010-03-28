<?php
// $Id$

function corolla_form_system_theme_settings_alter(&$form, $form_state) {
  // Generate the form using Forms API. http://api.drupal.org/api/7
  $form['custom'] = array(
    '#title' => 'Custom theme settings', 
    '#type' => 'fieldset', 
  );
  $form['custom']['color_scheme'] = array(
    '#title' => 'Color scheme', 
    '#type' => 'select',
    '#default_value' => theme_get_setting('color_scheme'),
    '#options' => array(
      'green' => 'Green (default)',
      'blue' => 'Blue',
      'black' => 'Black',
    ),
  );
}

