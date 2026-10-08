<?php
/**
 * Title: Pagina settore (Nuoto)
 * Slug: gug/settore-nuoto
 * Categories: gug-pagine
 * Description: Duplicare per gli altri settori cambiando gug-sector--xx, titolo e categoria della query.
 */
?>
<!-- wp:group {"tagName":"section","className":"gug-hero gug-sector--nu","layout":{"type":"default"}} -->
<section class="wp-block-group gug-hero gug-sector--nu"><!-- wp:heading {"level":1,"className":"gug-hero__title"} -->
<h1 class="wp-block-heading gug-hero__title">Nuoto</h1>
<!-- /wp:heading --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"gug-section","layout":{"type":"default"}} -->
<section class="wp-block-group gug-section"><!-- wp:heading {"className":"gug-heading"} -->
<h2 class="wp-block-heading gug-heading">News e comunicazioni</h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":2,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false,"taxQuery":{"category":[0]}},"className":"gug-newslist gug-newslist--grid"} -->
<div class="wp-block-query gug-newslist gug-newslist--grid"><!-- wp:post-template -->
<!-- wp:group {"className":"gug-newscard","layout":{"type":"default"}} -->
<div class="wp-block-group gug-newscard"><!-- wp:post-date {"format":"d.m.Y","className":"gug-newscard__date"} /-->

<!-- wp:post-title {"level":3,"isLink":true,"className":"gug-newscard__title"} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></section>
<!-- /wp:group -->
