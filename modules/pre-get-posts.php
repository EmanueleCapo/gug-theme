<?php
/**
 * Archivio di un settore: il loop principale (ereditato dal pattern "Archivio settore")
 * mostra le news del settore nella stagione in corso.
 *
 * @package gugpiemonte
 */

defined( 'ABSPATH' ) || exit;

/**
 * Imposta la query principale dell'archivio settore: solo news pubblicate della stagione in corso.
 *
 * @param WP_Query $query Query in preparazione.
 * @return void
 */
function gug_sector_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_tax( 'settori' ) ) {
		return;
	}

	$current_year  = gmdate( 'Y' );
	$previous_year = gmdate( 'Y', strtotime( '-1 year' ) );

	$query->set( 'post_type', array( 'post' ) );
	// Prima era "any": avrebbe mostrato ai visitatori anche le news in bozza.
	$query->set( 'post_status', 'publish' );
	$query->set(
		'tax_query',
		array(
			array(
				'taxonomy' => 'stagioni',
				'field'    => 'slug',
				'terms'    => $previous_year . '-' . $current_year,
			),
		)
	);
}
add_action( 'pre_get_posts', 'gug_sector_archive_query', 10 );
