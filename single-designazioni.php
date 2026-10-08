<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Blocksy
 */

// Redesign 2026: "Composizione della Giuria" con header/footer del sito (solo se il redesign è attivo).
if ( function_exists( 'gug_redesign_is_active' ) && gug_redesign_is_active() ) {
	get_header();
	while ( have_posts() ) {
		the_post();
		get_template_part( 'parts/single-designazione/composizione-giuria' );
	}
	get_footer();
	return;
}

get_header('designazioni');

if (
	! function_exists('elementor_theme_do_location')
	||
	! elementor_theme_do_location('single')
) {
	get_template_part('template-parts/single');
}



