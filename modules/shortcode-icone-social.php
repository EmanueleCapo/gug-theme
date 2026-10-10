<?php
/**
 * Shortcode [icone-social]: icone social del sito (colonna "Seguici" del footer).
 *
 * Usa l'helper di Blocksy blocksy_social_icons(): i link sono quelli impostati nel
 * Customizer (Impostazioni generali → Account social) e l'elenco dei social è lo stesso
 * dell'elemento "Socials" dell'header, così restano sempre allineati.
 *
 * @package gugpiemonte
 */

defined( 'ABSPATH' ) || exit;

/**
 * Restituisce l'elenco dei social configurati nell'elemento "Socials" dell'header Blocksy.
 *
 * @return array Elenco nel formato di Blocksy: [ [ 'id' => 'facebook', 'enabled' => true ], ... ].
 */
function gug_social_icons_list(): array {
	$fallback   = array(
		array( 'id' => 'facebook', 'enabled' => true ),
		array( 'id' => 'instagram', 'enabled' => true ),
		array( 'id' => 'email', 'enabled' => true ),
		array( 'id' => 'phone', 'enabled' => true ),
	);
	$placements = get_theme_mod( 'header_placements' );

	foreach ( (array) ( $placements['sections'] ?? array() ) as $section ) {
		foreach ( (array) ( $section['items'] ?? array() ) as $item ) {
			if ( 'socials' === ( $item['id'] ?? '' ) && ! empty( $item['values']['header_socials'] ) ) {
				return $item['values']['header_socials'];
			}
		}
	}

	return $fallback;
}

/**
 * Render dello shortcode [icone-social].
 *
 * @return string Markup delle icone social (vuoto se Blocksy non è attivo).
 */
function gug_shortcode_icone_social(): string {
	if ( ! function_exists( 'blocksy_social_icons' ) ) {
		return '';
	}

	// Scelta cliente: email e telefono sono già nella colonna Contatti del footer.
	$socials = array_values(
		array_filter(
			gug_social_icons_list(),
			static function ( $social ) {
				return ! in_array( $social['id'] ?? '', array( 'email', 'phone' ), true );
			}
		)
	);

	// Contenitore proprio: la classe ct-social-box di Blocksy non va sovrascritta.
	return '<div class="gug-social">' . blocksy_social_icons(
		$socials,
		array(
			'icons-color'  => 'custom',
			'type'         => 'simple',
			'size'         => 'custom',
			'links_target' => '_blank',
			'links_rel'    => 'noopener noreferrer',
		)
	) . '</div>';
}
add_shortcode( 'icone-social', 'gug_shortcode_icone_social' );
