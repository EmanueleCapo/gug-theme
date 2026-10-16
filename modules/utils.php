<?php
/**
 * Il tipo di contenuto "Ufficiali Gara" non ha pagine pubbliche: la singola scheda rimanda alla home.
 *
 * @package gugpiemonte
 */

defined( 'ABSPATH' ) || exit;

/**
 * Reindirizza alla home chi apre la scheda di un singolo Ufficiale Gara.
 *
 * @return void
 */
function gug_redirect_single_ufficiale() {
	if ( is_singular( 'ufficiali-gara' ) ) {
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}
}
add_action( 'wp', 'gug_redirect_single_ufficiale', 10 );
