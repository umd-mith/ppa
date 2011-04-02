<?php

function _node_resource_definition() {
  return array(
    'node' => array(
      'retrieve' => array(
        'file' => array('type' => 'inc', 'module' => 'services', 'name' => 'resources/node_resource'),
        'callback' => '_node_resource_retrieve',
        'args' => array(
          array(
            'name' => 'nid',
            'optional' => FALSE,
            'source' => array('path' => 0),
            'type' => 'int',
            'description' => 'The nid of the node to get',
          ),
        ),
        'access callback' => '_node_resource_access',
        'access arguments' => array('view'),
        'access arguments append' => TRUE,
      ),
      'create' => array(
        'file' => array('type' => 'inc', 'module' => 'services', 'name' => 'resources/node_resource'),
        'callback' => '_node_resource_create',
        'args' => array(
          array(
            'name' => 'node',
            'optional' => FALSE,
            'source' => 'data',
            'description' => 'The node object to create',
            'type' => 'array',
          ),
        ),
        'access callback' => '_node_resource_access',
        'access arguments' => array('create'),
        'access arguments append' => TRUE,
      ),
      'update' => array(
        'file' => array('type' => 'inc', 'module' => 'services', 'name' => 'resources/node_resource'),
        'callback' => '_node_resource_update',
        'args' => array(
          array(
            'name' => 'nid',
            'optional' => FALSE,
            'source' => array('path' => 0),
            'type' => 'int',
            'description' => 'The nid of the node to get',
          ),
          array(
            'name' => 'node',
            'optional' => FALSE,
            'source' => 'data',
            'description' => 'The node data to update',
            'type' => 'array',
          ),
        ),
        'access callback' => '_node_resource_access',
        'access arguments' => array('update'),
        'access arguments append' => TRUE,
      ),
      'delete' => array(
        'file' => array('type' => 'inc', 'module' => 'services', 'name' => 'resources/node_resource'),
        'callback' => '_node_resource_delete',
        'args' => array(
          array(
            'name' => 'nid',
            'optional' => FALSE,
            'source' => array('path' => 0),
            'type' => 'int',
          ),
        ),
        'access callback' => '_node_resource_access',
        'access arguments' => array('delete'),
        'access arguments append' => TRUE,
      ),
      'index' => array(
        'file' => array('type' => 'inc', 'module' => 'services', 'name' => 'resources/node_resource'),
        'callback' => '_node_resource_index',
        'args' => array(
          array(
            'name' => 'page',
            'optional' => TRUE,
            'type' => 'int',
            'description' => 'The zero-based index of the page to get, defaults to 0.',
            'default value' => 0,
            'source' => array('param' => 'page'),
          ),
          array(
            'name' => 'fields',
            'optional' => TRUE,
            'type' => 'string',
            'description' => 'The fields to get.',
            'default value' => '*',
            'source' => array('param' => 'fields'),
          ),
          array(
            'name' => 'parameters',
            'optional' => TRUE,
            'type' => 'array',
            'description' => 'Parameters',
            'default value' => NULL,
            'source' => array('param' => 'parameters'),
          ),
        ),
        'access arguments' => array('access content'),
      ),
      'relationships' => array(
        'files' => array(
          'file' => array('type' => 'inc', 'module' => 'services', 'name' => 'resources/node_resource'),
          'help'   => t('This method returns files associated with a node.'),
          'access callback' => '_node_resource_access',
          'access arguments' => array('view'),
          'access arguments append' => TRUE,
          'callback' => '_node_resource_load_node_files',
          'args'     => array(
            array(
              'name' => 'nid',
              'optional' => FALSE,
              'source' => array('path' => 0),
              'type' => 'int',
              'description' => 'The nid of the node whose files we are getting',
            ),
            array(
              'name' => 'file_contents',
              'type' => 'int',
              'description'  => t('To return file contents or not.'),
              'source' => array('path' => 2),
              'optional' => TRUE,
              'default value' => TRUE,
            ),
          ),
        ),
      ),
    ),
  );
}

/**
 * Returns the results of a node_load() for the specified node.
 *
 * This returned node may optionally take content_permissions settings into
 * account, based on a configuration setting.
 *
 * @param $nid
 *   NID of the node we want to return.
 * @return
 *   Node object or FALSE if not found.
 *
 * @see node_load()
 */
function _node_resource_retrieve($nid) {
  global $user;
  $node = node_load($nid);

  if ($node) {
    $uri = entity_uri('node', $node);
    $node->path = url($uri['path'], array('absolute' => TRUE));
    // Unset uri as it has complete entity and this
    // cause never ending recursion in rendering.
    unset($node->uri);
  }
  return $node;
}

/**
 * Creates a new node based on submitted values.
 *
 * Note that this function uses drupal_execute() to create new nodes,
 * which may require very specific formatting. The full implications of this
 * are beyond the scope of this comment block. The Googles are your friend.
 *
 * @param $node
 *   Array representing the attributes a node edit form would submit.
 * @return
 *   An associative array contained the new node's nid and, if applicable,
 *   the fully qualified URI to this resource.
 *
 * @see drupal_execute()
 */
function _node_resource_create($node) {
  if (!isset($node['type'])) {
    return services_error(t('Missing node type'), 406);
  }

  // Load the required includes for drupal_execute
  module_load_include('inc', 'node', 'node.pages');
  // Setup form_state
  $form_state = array();
  $form_state['values'] = $node;
  $form_state['values']['op'] = t('Save');

  // Wanted to return a gracefull error instead of a blank nid, this should
  // allow for that.
  $types = node_type_get_types();
  $node_type = $node['type'];
  if (isset($types[$node_type])) {
    drupal_form_submit($node_type . '_node_form', $form_state, (object)$node);
  }
  else {
    return services_error(t('Node type @type does not exist.', array('@type' => $node_type)), 406);
  }

  if ($errors = form_get_errors()) {
    return services_error(implode(" ", $errors), 406);
  }
  // Fetch $nid out of $form_state
  $nid = $form_state['nid'];
  // Only add the URI for servers that support it.
  $node = array('nid' => $nid);
  if ($uri = services_resource_uri(array('node', $nid))) {
    $node['uri'] = $uri;
  }
  return $node;
}

/**
 * Updates a new node based on submitted values.
 *
 * Note that this function uses drupal_execute() to create new nodes,
 * which may require very specific formatting. The full implications of this
 * are beyond the scope of this comment block. The Googles are your friend.
 *
 * @param $nid
 *   Node ID of the node we're editing.
 * @param $node
 *   Array representing the attributes a node edit form would submit.
 * @return
 *   The node's nid.
 *
 * @see drupal_execute()
 */
function _node_resource_update($nid, $node) {
  $node['nid'] = $nid;

  $old_node = node_load($nid);
  node_object_prepare($old_node);
  if ($old_node->nid) {
    // Load the required includes for drupal_execute
    module_load_include('inc', 'node', 'node.pages');

    // Setup form_state.
    $form_state = array();
    $form_state['values'] = $node;
    $form_state['values']['op'] = t('Save');
    $form_state['node'] = $old_node;
    drupal_form_submit($node['type'] . '_node_form', $form_state, $old_node);


    if ($errors = form_get_errors()) {
      return services_error(implode(" ", $errors), 406);
    }
  }
  else {
    return services_error(t('Node @nid not found', array('@nid' => $old_node->nid)), 404);
  }

  $node = array('nid' => $nid);
  if ($uri = services_resource_uri(array('node', $nid))) {
    $node['uri'] = $uri;
  }
  return $node;
}

/**
 * Delete a node given its nid.
 *
 * @param $nid
 *   Node ID of the node we're deleting.
 * @return
 *   The node's nid.
 */
function _node_resource_delete($nid) {
  node_delete($nid);
  return 1;
}

/**
 * Return an array of optionally paged nids baed on a set of criteria.
 *
 * An example request might look like
 *
 * http://domain/endpoint/node?fields=nid,vid&parameters[nid]=7&parameters[uid]=1
 *
 * This would return an array of objects with only nid and vid defined, where
 * nid = 7 and uid = 1.
 *
 * @param $page
 *   Page number of results to return (in pages of 20).
 * @param $fields
 *   The fields you want returned.
 * @param $parameters
 *   An object containing fields and values used to build a sql WHERE clause indicating
 *   what items should be deleted.
 * @return
 *   An array of node objects.
 *
 * @todo
 *   Evaluate the functionality here in general. Particularly around
 *     - Do we need fields at all? Should this just return full nodes?
 *     - Is there an easier syntax we can define which can make the urls
 *       for index requests more straightforward?
 */
function _node_resource_index($page = 0, $fields = '*', $parameters = NULL) {
  $node_select = db_select('node', 'n')
    ->addTag('node_access')
    ->orderBy('sticky', 'DESC')
    ->orderBy('created', 'DESC')
    ->range($page * 20, 20);

  if ($fields == '*') {
    $node_select->fields('n');
  }
  else {
    $fields = explode(',', $fields);
    $node_select->fields('n', $fields);
  }

  if (!user_access('administer nodes')) {
    $node_select->condition('status', 1);
  }

  foreach ($parameters as $parameter => $parameter_value) {
    $node_select->condition($parameter, $parameter_value, '=');
  }

  $result = $node_select->execute();

  $nodes = array();
  foreach ($result as $node) {
    if ($uri = services_resource_uri(array('node', $node->nid))) {
      $node->uri = $uri;
    }
    $nodes[] = $node;
  }

  return $nodes;
}

/**
 * Determine whether the current user can access a node resource.
 *
 * @param $op
 *   One of view, update, create, delete per node_access().
 * @param $args
 *   Resource arguments passed through from the original request.
 * @return bool
 *
 * @see node_access()
 */
function _node_resource_access($op = 'view', $args = array()) {
  // Make sure we have an object or this all fails, some servers can
  // mess up the types.
  if (is_array($args[0])) {
    $args[0] = (object) $args[0];
  }
  elseif (!is_array($args[0]) && !is_object($args[0])) {  //This is to determine if it is just a string happens on node/%NID
    $args[0] = (object)array('nid' => $args[0]);
  }

  if ($op != 'create' && !empty($args)) {
    $node = node_load($args[0]->nid);
  }
  elseif ($op == 'create') {
    if (isset($args[0]->type)) {
      $node = $args[0]->type;
      return node_access($op, $node);
    }
    else {
      return services_error(t('Node type is required'), 406);
    }
  }
  if (isset($node->nid) && $node->nid) {
    return node_access($op, $node);
  }
  else {
    return services_error(t('Node @nid could not be found', array('@nid' => $args[0]->nid)), 404);
  }
}

/**
 * Generates an array of base64 encoded files attached to a node
 *
 * @param $nid
 *   Number. Node ID
 * @param $include_file_contents
 *   Bool Whether or not to include the base64_encoded version of the file.
 * @return
 *   Array. A list of all files from the given node
 */
function _node_resource_load_node_files($nid, $include_file_contents = TRUE) {
  module_load_include('inc', 'services', 'resources/file_resource');
  $node = node_load($nid);

  // Hopefully theres another way to get a nodes fields that are a file, but this was the only way I could do it.
  $fields = field_info_fields();
  $files = array();

  // Loop through all of the fields on the site
  foreach ($fields as $key => $field) {
    //if we are a field type of file
    if ($field['type'] == 'image' || $field['type'] == 'file') {
      // If this field exists on our current node..
      if (isset($node->{$field['field_name']})) {
        // If there are items in the field...
        if (isset($node->{$field['field_name']}[LANGUAGE_NONE])) {
          // Grab the items given and attach them to the array.
          $node_file_field_items = $node->{$field['field_name']}[LANGUAGE_NONE];
          foreach ($node_file_field_items as $file) {
            $files[] = _file_resource_retrieve($file['fid'], $include_file_contents);
          }
        }
      }
    }
  }

  return $files;
}
