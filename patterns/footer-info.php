<?php
/**
 * Title: Footer — intestazione e contatti
 * Slug: gug/footer-info
 * Categories: gug-layout
 * Description: Da inserire nel widget della riga "middle" del Footer Builder di Blocksy.
 */
?>
<!-- wp:group {"className":"gug-footer","layout":{"type":"default"}} -->
<div class="wp-block-group gug-footer"><!-- wp:group {"className":"gug-footer__col","layout":{"type":"default"}} -->
<div class="wp-block-group gug-footer__col"><!-- wp:group {"className":"gug-brand","layout":{"type":"default"}} -->
<div class="wp-block-group gug-brand"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"gug-brand__logo"} -->
<figure class="wp-block-image size-full gug-brand__logo"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/logo-gug.png" alt="G.U.G. Piemonte e Valle d'Aosta"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"gug-brand__name"} -->
<p class="gug-brand__name">G.U.G. Piemonte<br>e Valle d'Aosta</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"gug-footer__text"} -->
<p class="gug-footer__text">Gruppo Ufficiali Gara della Federazione Italiana Nuoto<br>Comitato Regionale Piemonte e Valle d'Aosta</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"gug-footer__col","layout":{"type":"default"}} -->
<div class="wp-block-group gug-footer__col"><!-- wp:paragraph {"className":"gug-footer__title"} -->
<p class="gug-footer__title">Contatti</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"gug-footer__text"} -->
<p class="gug-footer__text">Via Giordano Bruno 191 — Palazzina 1<br>10134 Torino<br><a href="mailto:gugpiemonte@gmail.com">gugpiemonte@gmail.com</a><br><a href="tel:+390113040686">011 304 0686</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"gug-footer__text"} -->
<p class="gug-footer__text">Segreteria: mercoledì dalle 19.00 alle 22.30</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"gug-footer__col gug-footer__col--social","layout":{"type":"default"}} -->
<div class="wp-block-group gug-footer__col gug-footer__col--social"><!-- wp:paragraph {"className":"gug-footer__title"} -->
<p class="gug-footer__title">Seguici</p>
<!-- /wp:paragraph -->

<!-- wp:shortcode -->
[icone-social]
<!-- /wp:shortcode --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
