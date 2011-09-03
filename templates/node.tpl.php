<?php
// $Id: node.tpl.php,v 1.2.4.13 2010/12/03 06:15:05 jmburnz Exp $
?>
<article id="article-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <div class="article-inner">

    <?php print $unpublished; ?>

    <?php if ($title || $display_submitted): ?>
      <header>
        <?php print render($title_prefix); ?>
        <?php if ($title): ?>
          <h1<?php print $title_attributes; ?>>
            <a href="<?php print $node_url; ?>" rel="bookmark"><?php print $title; ?></a>
          </h1>
        <?php endif; ?>
        <?php print render($title_suffix); ?>
        <?php if ($display_submitted): ?>
          <p class="submitted"><?php print $submitted; ?></p>
        <?php endif; ?>
      </header>
    <?php endif; ?>

    <div<?php print $content_attributes; ?>>
    <?php print $user_picture; ?>
    <?php
      hide($content['comments']);
      hide($content['links']);
      print render($content);
    ?>
    </div>

    <?php if (!empty($content['links'])): ?>
      <nav class="clearfix"><?php print render($content['links']); ?></nav>
    <?php endif; ?>

    <?php print render($content['comments']); ?>

  </div>
</article>
