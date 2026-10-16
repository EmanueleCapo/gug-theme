<?php
/**
 * Singola designazione: "Composizione della Giuria" con header e footer del sito.
 *
 * @package gugpiemonte
 */

get_header();

while ( have_posts() ) {
	the_post();
	get_template_part( 'parts/single-designazione/composizione-giuria' );
}

get_footer();
