/* Base font size */
html {
  font-size: <?php print theme_get_setting('base_font_size'); ?>;
}

/* Layout width */
body.two-sidebars #navigation,
body.two-sidebars #header,
body.two-sidebars #main-columns {
  width: <?php print theme_get_setting('layout_3_width'); ?>;
  min-width:<?php print theme_get_setting('layout_3_min_width'); ?>;
  max-width: <?php print theme_get_setting('layout_3_max_width'); ?>;
}
body.one-sidebar #navigation,
body.one-sidebar #header,
body.one-sidebar #main-columns {
  width: <?php print theme_get_setting('layout_2_width'); ?>;
  min-width: <?php print theme_get_setting('layout_2_min_width'); ?>;
  max-width: <?php print theme_get_setting('layout_2_max_width'); ?>;
}
body.no-sidebars #navigation,
body.no-sidebars #header,
body.no-sidebars #main-columns {
  width: <?php print theme_get_setting('layout_1_width'); ?>;
  min-width: <?php print theme_get_setting('layout_1_min_width'); ?>;
  max-width: <?php print theme_get_setting('layout_1_max_width'); ?>;
}
