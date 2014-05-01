<?php
/**
* @file
* Contains all item related functions
*/

function biblio_get_ht_items_by_nid($nid) {
  static $items = array();

  $result = db_query(
    'SELECT biblio_ht_item.htid, biblio_ht_item.enumcron FROM biblio_ht_item WHERE biblio_ht_item.nid = :nid ORDER BY biblio_ht_item.enumcron, biblio_ht_item.iid',
    array(':nid' => $nid)
  );

  while ($record = $result->fetchObject()) {
    $items[$record->htid] = $record->enumcron;
  }

  return $items;
}

