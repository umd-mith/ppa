<?php

/**
 * @return multitype:string NULL
 */
function biblio_token_info() {
  $schema = drupal_get_schema('biblio');
  $fields = array_diff($schema['fields'], array('nid', 'vid'));
  $node_token['biblio']= array(
      'name' => t('Biblio'),
      'description' => t('Tokens related to the Biblio content type.'),
      'type' => 'biblio'
  );
  foreach ($fields as $key => $value) {
    $name = str_replace('biblio_', '', $key);
    $name = str_replace('_', ' ', $name);
    $name = ucwords($name);
    $tokens[$key] = array(
        'name' => t($name),
        'description' => (isset($value['description'])) ? t("!desc" , array('!desc' => $value['description'])) : '',
    );
  }

  $tokens['biblio_first_author'] = array(
      'name' => t("Author - First"),
      'description' => 'First author of the publication',
  );
  $tokens['biblio_type_name'] = array(
      'name' => t("Type Name"),
      'description' => t('The name of the publication type (i.e. Journal, Book, etc.'),
  );


  $types['biblio'] = array(
      'name' => t('Biblio'),
      'description' => t('Tokens related to Biblio node type.'),
      'needs-data' => 'node',
  );


  return array(
      'types' => $types,
      'tokens' => array(
          'biblio' => $tokens,
          'node'   => $node_token),
      );
}

/**
 * @param unknown_type $type
 * @param unknown_type $tokens
 * @param unknown_type $data
 * @param unknown_type $options
 * @return multitype:NULL
 */
function biblio_tokens($type, $tokens, $data = array(), $options = array()) {
  $replacements = array();
  if ($type == 'node' && !empty($data['node']) && $data['node']->type == 'biblio') {
    $sanitize = !empty($options['sanitize']);
    $node = $data['node'];
    foreach ($tokens as $name => $original) {
      $name = str_replace('biblio:', '', $name);
      switch ($name) {
        case 'biblio_first_author':
          $replacements[$original] = $sanitize ? check_plain($node->biblio_contributors[0]['lastname']) : $node->biblio_contributors[0]['lastname'];
          break;
        case 'biblio_type_name':
          $type = db_query('SELECT name FROM {biblio_types} as t WHERE t.tid = :tid', array(':tid' => $node->biblio_type))->fetchField();
          $replacements[$original] = $sanitize ? check_plain($type) : $type;
          break;
        default:
          $replacements[$original] = $sanitize ? check_plain($node->$name) : $node->$name;
      }
    }
  }

  return $replacements;
}