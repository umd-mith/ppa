<?php
function biblio_pm_views_data() {

  $data['biblio_pubmed']['table']['group']  = t('Biblio');
  $data['biblio_pubmed']['table']['join'] = array(
      'node' => array(
          'left_field' => 'nid',
          'field' => 'nid',
      ),
  );
  $data['biblio_pubmed']['biblio_pubmed_id'] = array(
      'title' => t('PubMed ID'),
      'help' => t('The PubMed ID'),
      'field' => array(
          'handler' => 'views_handler_field_string',
          'click sortable' => TRUE,
      ),
      'sort' => array(
          'handler' => 'views_handler_sort',
      ),
      'filter' => array(
          'handler' => 'views_handler_filter_string',
      ),
      'argument' => array(
          'handler' => 'views_handler_argument',
      ),

  );

  return $data;
}
