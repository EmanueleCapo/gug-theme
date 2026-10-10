<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blocksy
 */

// Redesign 2026: archivio del settore costruito con il pattern sincronizzato "Archivio settore"
// (Aspetto → Pattern), modificabile dall'editor. Solo se il redesign è attivo.
if ( function_exists( 'gug_redesign_is_active' ) && gug_redesign_is_active() ) {
	$gug_archive_pattern = get_page_by_path( 'archivio-settore', OBJECT, 'wp_block' );

	if ( $gug_archive_pattern ) {
		get_header();
		// Stessa struttura delle pagine di Blocksy: le larghezze dei blocchi sono definite su .entry-content.
		echo '<div class="ct-container-full" data-content="normal"><article class="gug-sector-archive"><div class="entry-content is-layout-constrained">';
		// do_shortcode: fuori da the_content gli shortcode del pattern non verrebbero eseguiti.
		echo do_shortcode( shortcode_unautop( do_blocks( '<!-- wp:block {"ref":' . (int) $gug_archive_pattern->ID . '} /-->' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup dei blocchi del pattern.
		echo '</div></article></div>';
		get_footer();
		return;
	}
}

get_header();

if (
	! function_exists('elementor_theme_do_location')
	||
	! elementor_theme_do_location('archive')
) {
	get_template_part('template-parts/archive');
}

get_footer();
