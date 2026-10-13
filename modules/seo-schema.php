<?php
/**
 * SEO: dati strutturati (JSON-LD) di Rank Math senza autore personale.
 *
 * @package gugpiemonte
 */

defined( 'ABSPATH' ) || exit;

/**
 * Toglie l'autore dai dati strutturati di Rank Math: il sito è istituzionale e pagine e news
 * non devono risultare scritte da una persona (Rank Math free non ha un'opzione per farlo).
 *
 * @param array $data   Entità JSON-LD preparate da Rank Math.
 * @param mixed $jsonld Istanza JsonLD di Rank Math (non usata).
 * @return array Entità senza Person e senza la proprietà author.
 */
function gug_seo_remove_schema_author( $data, $jsonld ) {
	if ( ! is_array( $data ) ) {
		return $data;
	}

	foreach ( $data as $key => $entity ) {
		if ( ! is_array( $entity ) ) {
			continue;
		}
		if ( isset( $entity['@type'] ) && 'Person' === $entity['@type'] ) {
			unset( $data[ $key ] );
			continue;
		}
		unset( $data[ $key ]['author'] );
	}

	return $data;
}
add_filter( 'rank_math/json_ld', 'gug_seo_remove_schema_author', 99, 2 );
