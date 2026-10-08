<?php
/**
 * Title: Home
 * Slug: gug/home
 * Categories: gug-pagine
 * Inserter: true
 */
?>
<!-- wp:group {"tagName":"section","className":"gug-hero gug-hero--home","layout":{"type":"default"}} -->
<section class="wp-block-group gug-hero gug-hero--home"><!-- wp:heading {"level":1,"className":"gug-hero__title"} -->
<h1 class="wp-block-heading gug-hero__title">Manifestazioni in programma</h1>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[gug_manifestazioni]
<!-- /wp:shortcode --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"gug-section","layout":{"type":"default"}} -->
<section class="wp-block-group gug-section"><!-- wp:columns {"className":"gug-split"} -->
<div class="wp-block-columns gug-split"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"className":"gug-heading"} -->
<h2 class="wp-block-heading gug-heading">News e comunicazioni</h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":1,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"className":"gug-newslist"} -->
<div class="wp-block-query gug-newslist"><!-- wp:post-template -->
<!-- wp:group {"className":"gug-newscard","layout":{"type":"default"}} -->
<div class="wp-block-group gug-newscard"><!-- wp:post-date {"format":"d.m.Y","className":"gug-newscard__date"} /-->

<!-- wp:post-title {"level":3,"isLink":true,"className":"gug-newscard__title"} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"gug-promo","layout":{"type":"default"}} -->
<div class="wp-block-group gug-promo"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"gug-promo__media"} -->
<figure class="wp-block-image size-full gug-promo__media"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/banner-ug.png" alt="Scopri come diventare Ufficiale di Gara"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3,"className":"gug-promo__title"} -->
<h3 class="wp-block-heading gug-promo__title">Diventa Ufficiale di Gara</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"gug-promo__text"} -->
<p class="gug-promo__text">Corsi per nuoto, pallanuoto, salvamento, artistico e tuffi. Nessuna esperienza agonistica richiesta.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"gug-promo__link"} -->
<p class="gug-promo__link"><a class="gug-arrow-link" href="/diventa-un-ufficiale-gara/">Scopri come</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
