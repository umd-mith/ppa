<?php
// $Id$

/**
 * @file
 * Default theme implementation to display a single Drupal page while offline.
 *
 * All the available variables are mirrored in page.tpl.php. Some may be left
 * blank but they are provided for consistency.
 *
 * @see template_preprocess()
 * @see template_preprocess_maintenance_page()
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">

<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
  <script type="text/javascript"><?php /* Needed to avoid Flash of Unstyled Content in IE */ ?> </script>
</head>
<body class="<?php print $classes; ?>">

  <div id="header-wrapper">
    <div id="header">
      <div id="branding" class="clearfix <?php if (!$site_name): ?> site-name-disabled<?php endif; ?><?php if (!$site_slogan): ?> site-slogan-disabled<?php endif; ?>">

        <?php if ($logo): ?>
          <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
            <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
          </a>
        <?php endif; ?>

        <div id="name-and-slogan">
          <?php if ($site_name): ?>
            <?php if ($title): ?>
              <div id="site-name"><strong>
                <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><?php print $site_name; ?></a>
              </strong></div>
            <?php else: /* Use h1 when the content title is empty */ ?>
              <h1 id="site-name">
                <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><?php print $site_name; ?></a>
              </h1>
            <?php endif; ?>
          <?php endif; ?>

          <?php if ($site_slogan): ?>
            <div id="site-slogan"><?php print $site_slogan; ?></div>
          <?php endif; ?>
        </div> <!-- /#name-and-slogan -->

      </div> <!-- /#branding -->
    </div> <!-- /#header -->
  </div> <!-- /#header-wrapper -->

  <div id="main-wrapper">
    <div id="main">

      <div id="main-columns" class="clearfix">
        <div id="content-wrapper">
          <div id="content">
            <?php if ($breadcrumb): ?><div id="breadcrumb" class="clearfix"><?php print $breadcrumb; ?></div><?php endif; ?>
            <?php if ($messages): ?><div id="messages"><?php print $messages; ?></div><?php endif; ?>
            <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>

            <a id="main-content"></a>
            <?php print $feed_icons; ?>

            <?php print render($page['help']); ?>

            <?php if ($title && !isset($node)): ?>
              <h1 class="page-title"><?php print $title ?></h1>
            <?php endif; ?>

            <?php print render($page['content']); ?>
          </div> <!-- /#content -->
        </div> <!-- /#content-wrapper --> 

    </div> <!-- /#main -->
  </div> <!-- /#main-warpper -->

</body>
