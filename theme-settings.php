<?php
// $Id$

function corolla_form_system_theme_settings_alter(&$form, $form_state) {
  // Generate the form using Forms API. http://api.drupal.org/api/7
  $form['custom'] = array(
    '#title' => 'Custom theme settings', 
    '#type' => 'fieldset', 
  );
  $form['custom']['sidebar_first_weight'] = array(
    '#title' => 'First sidebar position', 
    '#type' => 'select',
    '#default_value' => theme_get_setting('sidebar_first_weight'),
    '#options' => array(
      -2 => 'Far left',
      -1 => 'Left',
       1 => 'Right',
       2 => 'Far right',
    ),
  );
  $form['custom']['sidebar_second_weight'] = array(
    '#title' => 'Second sidebar position', 
    '#type' => 'select',
    '#default_value' => theme_get_setting('sidebar_second_weight'),
    '#options' => array(
      -2 => 'Far left',
      -1 => 'Left',
       1 => 'Right',
       2 => 'Far right',
    ),
  );
  $form['custom']['trim_pager'] = array(
    '#type' => 'select',
    '#title' => 'Trim pager after specified number of pages', 
    '#default_value' => theme_get_setting('triim_pager'),
    '#options' => corolla_generate_array(4, 15, 1, ''),
  );
}

function corolla_generate_array($min, $max, $increment, $postfix, $unlimited = NULL) {
  $array = array();
  if ($unlimited == 'first') {
    $array['none'] = 'Unlimited';
  }
  for ($a = $min; $a <= $max; $a += $increment) {
    $array[$a . $postfix] = $a . ' ' . $postfix;
  }
  if ($unlimited == 'last') {
    $array['none'] = 'Unlimited';
  }
  return $array;
}

