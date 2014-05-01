<?php
module_load_include('inc', 'biblio', 'includes/biblio.import.export');

$dir = file_scan_directory('/home/drupal/code/projects/prosody/output', '/records-\d\d\d\d\.mrc/');
ksort($dir);

foreach ($dir as $file) {
  $contents = file_get_contents($file->uri);
  $file = file_save_data($contents);

  $context = array();

  biblio_import($file, 'biblio_marc', 1, NULL, FALSE, NULL, $context);

  file_delete($file);
}

?>
