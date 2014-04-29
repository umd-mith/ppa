<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <div class="node-inner">
    <?php print $unpublished; ?>

    <?php print render($title_prefix); ?>
    <?php if ($title || $display_submitted): ?>
      <header<?php print $header_attributes; ?>>

        <?php if ($title): ?>
          <h1<?php print $title_attributes; ?>>
            <?php if (!$page): ?>
              <a href="<?php print $node_url; ?>" rel="bookmark"><?php print $title; ?></a>
            <?php elseif ($page): ?>
              <?php print $title; ?>
            <?php endif; ?>
          </h1>
        <?php endif; ?>

        <?php if ($display_submitted): ?>
          <p class="submitted"><?php print $submitted; ?></p>
        <?php endif; ?>

      </header>
    <?php endif; ?>
    <?php print render($title_suffix); ?>

    <div<?php print $content_attributes; ?>>
    <?php print $user_picture; ?>
      <script data-main="app/config" src="/sites/default/files/solr-search/require.js"></script>
      <div id="searchapp"></div>
    </div>

    <?php if ($links = render($content['links'])): ?>
      <nav<?php print $links_attributes; ?>><?php print $links; ?></nav>
    <?php endif; ?>

    <?php print render($content['comments']); ?>

  </div>
</article>
