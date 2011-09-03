<?php

function get_gwf_font_families() {
  return array (
    "Allan", "Allerta", "Allerta Stencil", "Amaranth", "Angkor", "Annie Use Your Telescope", "Anonymous Pro", "Anton", "Architects Daughter", "Arimo", "Arvo", "Astloch", "Bangers", "Battambang", "Bayon", "Bentham", "Bevan", "Bokor", "Buda", "Cabin", "Cabin Sketch", "Calligraffitti", "Candal", "Cantarell", "Cardo", "Chenla", "Cherry Cream Soda", "Chewy", "Coda", "Coda Caption", "Coming Soon", "Content", "Copse", "Corben", "Cousine", "Covered By Your Grace", "Crafty Girls", "Crimson Text", "Crushed", "Cuprum", "Dancing Script", "Dangrek", "Dawning of a New Day", "Droid Sans", "Droid Sans Mono", "Droid Serif", "EB Garamond", "Expletus Sans", "Fontdiner Swanky", "Freehand", "GFS Didot", "GFS Neohellenic", "Geo", "Goudy Bookletter 1911", "Gruppo", "Hanuman", "Homemade Apple", "IM Fell DW Pica", "IM Fell DW Pica SC", "IM Fell Double Pica", "IM Fell Double Pica SC", "IM Fell English", "IM Fell English SC", "IM Fell French Canon", "IM Fell French Canon SC", "IM Fell Great Primer", "IM Fell Great Primer SC", "Inconsolata", "Indie Flower", "Irish Grover", "Josefin Sans", "Josefin Slab", "Just Another Hand", "Just Me Again Down Here", "Kenia", "Khmer", "Koulen", "Kranky", "Kreon", "Kristi", "Lato", "League Script", "Lekton", "Lobster", "Luckiest Guy", "Maiden Orange", "Meddon", "MedievalSharp", "Merriweather", "Metal", "Michroma", "Miltonian", "Miltonian Tattoo", "Molengo", "Moul", "Moulpali", "Mountains of Christmas", "Neucha", "Neuton", "Nobile", "Nova Cut", "Nova Flat", "Nova Mono", "Nova Oval", "Nova Round", "Nova Script", "Nova Slim", "Nova Square", "OFL Sorts Mill Goudy TT", "Odor Mean Chey", "Old Standard TT", "Orbitron", "Oswald", "PT Sans", "PT Sans Caption", "PT Sans Narrow", "PT Serif", "PT Serif Caption", "Pacifico", "Permanent Marker", "Philosopher", "Preahvihear", "Puritan", "Quattrocento", "Radley", "Raleway", "Reenie Beanie", "Rock Salt", "Schoolbell", "Siamreap", "Six Caps", "Slackey", "Sniglet", "Sue Ellen Francisco", "Sunshiney", "Suwannaphum", "Syncopate", "Tangerine", "Taprom", "Terminal Dosis Light", "Tinos", "Ubuntu", "UnifrakturCook", "UnifrakturMaguntia", "Unkempt", "VT323", "Vibur", "Vollkorn", "Waiting for the Sunrise", "Walter Turncoat", "Yanone Kaffeesatz" 
  );
}

function get_gwf_font_families_options() {
  global $_gwf_font_families;
  if ($_gwf_font_families) {
    return $_gwf_font_families;
  }
  $_gwf_font_families = array();
  $font_families = get_gwf_font_families();
  foreach($font_families as $ff) {
    $_gwf_font_families[$ff] = $ff;
  }
  return $_gwf_font_families;
}

function get_style_name($key, $font_type, $font_value) {
  $font_value = trim($font_value);
  $font_value = strtolower($font_value);
  $font_value = str_replace(' ', '-', $font_value);
  return $key . (!empty($font_type) ? '-' . $font_type :  "") . "-" . $font_value;
}