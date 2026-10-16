<?php
/**
 * Archivio di un settore (/settori/xx/), costruito con il pattern sincronizzato "Archivio settore"
 * (Aspetto → Pattern), modificabile dall'editor. Senza il pattern si usa l'archivio standard di Blocksy.
 *
 * @package gugpiemonte
 */

$gug_archive_pattern = get_page_by_path( 'archivio-settore', OBJECT, 'wp_block' );

get_header();

if ( $gug_archive_pattern ) {
	// Stessa struttura delle pagine di Blocksy: le larghezze dei blocchi sono definite su .entry-content.
	echo '<div class="ct-container-full" data-content="normal"><article class="gug-sector-archive"><div class="entry-content is-layout-constrained">';
	// do_shortcode: fuori da the_content gli shortcode del pattern non verrebbero eseguiti.
	echo do_shortcode( shortcode_unautop( do_blocks( '<!-- wp:block {"ref":' . (int) $gug_archive_pattern->ID . '} /-->' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup dei blocchi del pattern.
	echo '</div></article></div>';
} else {
	get_template_part( 'template-parts/archive' );
}

get_footer();
