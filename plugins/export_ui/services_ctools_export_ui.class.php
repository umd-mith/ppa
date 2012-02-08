<?php

/**
 * @file
 * Export-ui handler for the Services module.
 */

class services_ctools_export_ui extends ctools_export_ui {

  /**
   * Page callback for the resources page.
   */
  function resources_page($js, $input, $item) {
    drupal_set_title($this->get_page_title('resources', $item));
    return drupal_get_form('services_edit_form_endpoint_resources', $item);
  }

  /**
   * Page callback for the server page.
   */
  function server_page($js, $input, $item) {
    drupal_set_title($this->get_page_title('server', $item));
    return drupal_get_form('services_edit_form_endpoint_server', $item);
  }


  /**
   * Page callback for the authentication page.
   */
  function authentication_page($js, $input, $item) {
    drupal_set_title($this->get_page_title('authentication', $item));
    return drupal_get_form('services_edit_form_endpoint_authentication', $item);
  }

  /**
   * Page callback for the resource authentication page.
   */
  function resource_authentication_page($js, $input, $item) {
    drupal_set_title($this->get_page_title('resource_authentication', $item));
    return drupal_get_form('services_edit_form_endpoint_resource_authentication', $item);
  }

  // Avoid standard submit of edit form by ctools.
  function edit_save_form($form_state) { }
    
  function set_item_state($state, $js, $input, $item) {
    ctools_export_set_object_status($item, $state);

    menu_rebuild();
    if (!$js) {
      drupal_goto(ctools_export_ui_plugin_base_path($this->plugin));
    }
    else {
      return $this->list_page($js, $input);
    }
  }
}

/**
 * Endpoint authentication configuration form.
 */
function services_edit_form_endpoint_authentication($form, &$form_state) {
  list($endpoint) = $form_state['build_info']['args'];
  // Loading runtime include as needed by services_authentication_info().
  module_load_include('runtime.inc', 'services');

  $auth_modules = module_implements('services_authentication_info');

  $form['endpoint_object'] = array(
    '#type'  => 'value',
    '#value' => $endpoint,
  );
  if (empty($auth_modules)) {
    $form['message'] = array(
      '#type'          => 'item',
      '#title'         => t('Authentication'),
      '#description'   => t('No authentication modules are installed, all requests will be anonymous.'),
    );
    return $form;
  }
  if (empty($endpoint->authentication)) {
    $form['message'] = array(
      '#type'          => 'item',
      '#title'         => t('Authentication'),
      '#description'   => t('No authentication modules are enabled, all requests will be anonymous.'),
    );
    return $form;
  }
  // Add configuration fieldsets for the authentication modules
  foreach ($endpoint->authentication as $module => $settings) {
    $info = services_authentication_info($module);
    if (empty($info)) {
      continue;
    }
    $form[$module] = array(
      '#type' => 'fieldset',
      '#title' => isset($info['title']) ? $info['title'] : $module,
      '#tree' => TRUE,
    );

    // Append the default settings for the authentication module.
    $default_security_settings = services_auth_invoke($module, 'default_security_settings');
    if (is_array($default_security_settings)) {
      $settings += $default_security_settings;
    }
    // Ask the authentication module for a settings form.
    $module_settings_form = services_auth_invoke($module, 'security_settings', $settings, $form_state);

    if (is_array($module_settings_form)) {
      $form[$module] += $module_settings_form;
    }
    else {
      $form[$module]['message'] = array(
        '#type'   => 'item',
        '#markup'  => t('@module has no settings available.', array('@module' => drupal_ucfirst($module))),
      );
    }
  }

  $form['submit'] = array(
    '#type'  => 'submit',
    '#value' => 'Save',
  );

  return $form;
}

function services_edit_form_endpoint_authentication_submit($form, $form_state) {
  $endpoint = $form_state['values']['endpoint_object'];

  foreach (array_keys($endpoint->authentication) as $module) {
    if (isset($form_state['values'][$module])) {
      $endpoint->authentication[$module] = $form_state['values'][$module];
    }
  }

  drupal_set_message(t('Your authentication options have been saved.'));
  services_endpoint_save($endpoint);
}

function services_edit_form_endpoint_server($form, &$form_state) {
  list($endpoint) = $form_state['build_info']['args'];
  $servers = services_get_servers();

  $server = !empty($servers[$endpoint->server]) ? $servers[$endpoint->server] : FALSE;

  $form['endpoint_object'] = array(
    '#type'  => 'value',
    '#value' => $endpoint,
  );

  if (!$server) {
    $form['message'] = array(
      '#type'          => 'item',
      '#title'         => t('Unknown server @name', array('@name' => $endpoint->server)),
      '#description'   => t('No server matching the one used in the endpoint.'),
    );
  }
  else if (empty($server['settings'])) {
    $form['message'] = array(
      '#type'          => 'item',
      '#title'         => t('@name has no settings', array('@name' => $endpoint->server)),
      '#description'   => t("The server doesn't have any settings that needs to be configured."),
    );
  }
  else {
    $definition = $server['settings'];

    $settings = isset($endpoint->server_settings) ? $endpoint->server_settings : array();

    if (!empty($definition['file'])) {
      call_user_func_array('module_load_include', $definition['file']);
    }

    $form[$endpoint->server] = array(
      '#type' => 'fieldset',
      '#title' => $server['name'],
      '#tree' => TRUE,
    );
    call_user_func_array($definition['form'], array(&$form[$endpoint->server], $endpoint, $settings));

    $form['submit'] = array(
      '#type'  => 'submit',
      '#value' => 'Save',
    );
  }

  return $form;
}

function services_edit_form_endpoint_server_submit($form, $form_state) {
  $endpoint = $form_state['values']['endpoint_object'];
  $servers = services_get_servers();
  $definition = $servers[$endpoint->server]['settings'];

  $values = $form_state['values'][$endpoint->server];

  // Allow the server to alter the submitted values before they're stored
  // as settings.
  if (!empty($definition['submit'])) {
    if (!empty($definition['file'])) {
      call_user_func_array('module_load_include', $definition['file']);
    }
    $values = call_user_func_array($definition['submit'], array($endpoint, &$values));
  }

  // Store the settings in the endpoint
  $endpoint->server_settings = $values;
  services_endpoint_save($endpoint);

  drupal_set_message(t('Your server settings have been saved.'));
}

/**
 * services_edit_endpoint_resources function.
 *
 * Edit Resources endpoint form
 * @param object $endpoint
 * @return string  The form to be displayed
 */
function services_edit_endpoint_resources($endpoint) {
  if (!is_object($endpoint)) {
    $endpoint = services_endpoint_load($endpoint);
  }
  if ($endpoint && !empty($endpoint->title)) {
    drupal_set_title($endpoint->title);
  }
  return drupal_get_form('services_edit_form_endpoint_resources', $endpoint);
}

/**
 * services_edit_form_endpoint_resources function.
 *
 * @param array &$form_state
 * @param object $endpoint
 * @return Form
 */
function services_edit_form_endpoint_resources($form, &$form_state, $endpoint) {
  module_load_include('resource_build.inc', 'services');
  module_load_include('runtime.inc', 'services');

  $form = array();
  $form['endpoint_object'] = array(
    '#type'  => 'value',
    '#value' => $endpoint,
  );

  $form['#attached']['js'] = array(
    'misc/tableselect.js',
    drupal_get_path('module', 'services') . '/js/services.admin.js',
  );

  $form['#attached']['css'] = array(
    drupal_get_path('module', 'services') . '/css/services.admin.css',
  );

  $ops = array(
    'create'   => t('Create'),
    'retrieve' => t('Retrieve'),
    'update'   => t('Update'),
    'delete'   => t('Delete'),
    'index'    => t('Index'),
  );

  // Call _services_build_resources() directly instead of
  // services_get_resources to bypass caching.
  $resources = _services_build_resources();

  // Apply the endpoint in a non-strict mode, so that the non-active resources
  // are preserved.
  _services_apply_endpoint($resources, $endpoint, FALSE);

  $form['instructions'] = array(
    '#type' => 'item',
    '#title' => t('Resources'),
    '#description' => t('Select the resource(s) or methods you would like to enable, and click <em>Save</em>.'),
   );

  $form['resources']= array(
    '#theme' => 'services_resource_table',
    '#tree' => TRUE,
   );

  $class_names = services_operation_class_info();
  // Collect authentication module info for later use and
  // append the default settings for authentication modules
  $auth_info = array();
  foreach ($endpoint->authentication as $module => $settings) {
    $auth_info[$module] = services_authentication_info($module);

    // Append the default settings for the authentication module.
    $default_settings = services_auth_invoke($module, 'default_security_settings');
    if (is_array($default_settings)) {
      $settings += $default_settings;
    }
    $endpoint->authentication[$module] = $settings;
  }

  // Generate the list of methods arranged by resource.
  foreach ($resources as $resource_name => $resource) {
    $resource_conf = array();
    if (isset($endpoint->resources[$resource_name])) {
      $resource_conf = $endpoint->resources[$resource_name];
    }
    $res_item = array(
      '#collapsed' => TRUE,
    );

    $alias = '';
    if (isset($form_state['input'][$resource_name]['alias'])) {
      $alias = $form_state['input'][$resource_name]['alias'];
    }
    else if (isset($resource_conf['alias'])) {
      $alias = $resource_conf['alias'];
    }

    $res_item['alias'] = array(
      '#type' => 'textfield',
      '#default_value' => $alias,
      '#size' => 20,
    );

    foreach ($class_names as $class => $info) {
      if (!empty($resource[$class])) {
        $res_item[$class] = array(
          '#type' => 'item',
          '#title' => $info['title'],
        );

        foreach ($resource[$class] as $op_name => $op) {
          $description = isset($op['help']) ? $op['help'] : t('No description is available');
          $default_value = 0;
          if (isset($resource_conf[$class][$op_name]['enabled'])) {
            $default_value = $resource_conf[$class][$op_name]['enabled'];
          }
          $res_item[$class][$op_name] = array(
            '#type' => 'item',
            '#title' => $op_name,
            '#description' => $description,
          );
          $res_item[$class][$op_name]['enabled'] = array(
            '#type' => 'checkbox',
            '#title' => t('Enabled'),
            '#default_value' => $default_value,
          );

          $controller_settings = array();
          foreach ($endpoint->authentication as $module => $settings) {
            $auth_settings = services_auth_invoke($module, 'controller_settings', $settings, $op, $endpoint->authentication[$module], $class, $op_name);
            if (is_array($auth_settings)) {
              $auth_settings = array(
                '#title' => $auth_info[$module]['title'],
                '#type' => 'item',
              ) + $auth_settings;
              $controller_settings[$module] = $auth_settings;
            }
          }
          $res_item[$class][$op_name]['settings'] = $controller_settings;
        }
      }
    }

    $form['resources'][$resource_name] = $res_item;
  }
  $form['save'] = array(
     '#type'  => 'submit',
     '#value' => t('Save'),
  );
  return $form;
}

/**
 * services_edit_form_endpoint_resources_validate function.
 *
 * @param array $form
 * @param array $form_state
 * @return void
 */
function services_edit_form_endpoint_resources_validate($form, $form_state) {
  $input = $form_state['values'];

  // Validate aliases.
  foreach ($input['resources'] as $resource_name => $resource) {
    if (!empty($resource['alias']) && !preg_match('/^[a-z-]+$/', $resource['alias'])) {
      // Still this doesn't highlight needed form element.
      form_set_error("resources][{$resource_name}][alias", t("The alias for the !name resource may only contain lower case a-z and dashes.", array(
        '!name' => $resource_name,
      )));
    }
  }
}

/**
 * Resources form submit function.
 *
 * @param array $form
 * @param array $form_state
 * @return void
 */
function services_edit_form_endpoint_resources_submit($form, $form_state) {
  $endpoint  = $form_state['values']['endpoint_object'];
  $resources = $form_state['input']['resources'];

  $class_names = services_operation_class_info();
  // Iterate over the resources, its operation classes and operations.
  // The main purpose is to remove empty configuration for disabled elements.
  foreach ($resources as $resource_name => $resource) {
    if (empty($resource['alias'])) {
      unset($resource['alias']);
    }
    foreach ($class_names as $class_name => $info) {
      if (!empty($resource[$class_name])) {
        foreach ($resource[$class_name] as $op_name => $op) {
          // Remove the operation if it has been disabled.
          if (!$op['enabled']) {
            unset($resource[$class_name][$op_name]);
          }
        }
      }
      // Remove the operation class element if it doesn't
      // have any enabled operations.
      if (empty($resource[$class_name])) {
        unset($resource[$class_name]);
      }
    }
    // Remove the resource if it doesn't have any properties.
    if (empty($resource)) {
      unset($resources[$resource_name]);
    }
    // Add the processed resource if it does.
    else {
      $resources[$resource_name] = $resource;
    }
  }

  $endpoint->resources = $resources;
  services_endpoint_save($endpoint);
  drupal_set_message('Resources have been saved');
}
