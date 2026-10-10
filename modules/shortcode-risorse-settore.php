<?php
/**
 * Shortcode [risorse-settore]: collegamenti alle pagine del settore (archivio /settori/xx/).
 *
 * Mostra una card per Designazioni, Regolamenti, Formazione Ufficiali Gara e Modulistica
 * del settore aperto, solo se la pagina corrispondente esiste ed è pubblicata.
 *
 * @package gugpiemonte
 */

defined( 'ABSPATH' ) || exit;

/**
 * Restituisce le sezioni del settore con titolo, descrizione e prefisso dello slug della pagina.
 *
 * @return array[] Elenco di [ prefisso slug, titolo, descrizione ].
 */
function gug_risorse_settore_sections(): array {
	return array(
		array( 'designazioni', __( 'Designazioni', 'gugpiemonte' ), __( 'Giurie e partite della settimana', 'gugpiemonte' ) ),
		array( 'regolamenti', __( 'Regolamenti', 'gugpiemonte' ), __( 'Norme tecniche e regolamenti di gara', 'gugpiemonte' ) ),
		array( 'formazione-ufficiali-gara', __( 'Formazione', 'gugpiemonte' ), __( 'Materiali per gli Ufficiali Gara', 'gugpiemonte' ) ),
		array( 'modulistica', __( 'Modulistica', 'gugpiemonte' ), __( 'Moduli e documenti da scaricare', 'gugpiemonte' ) ),
	);
}

/**
 * Render dello shortcode [risorse-settore].
 *
 * @param array|string $atts Attributi (settore: slug del termine, facoltativo nell'archivio).
 * @return string Markup delle card o stringa vuota.
 */
function gug_shortcode_risorse_settore( $atts ): string {
	$atts   = shortcode_atts( array( 'settore' => '' ), $atts, 'risorse-settore' );
	$sector = sanitize_key( $atts['settore'] );
	$term   = $sector ? get_term_by( 'slug', $sector, 'settori' ) : ( is_tax( 'settori' ) ? get_queried_object() : null );

	if ( ! $term instanceof WP_Term ) {
		return '';
	}

	$suffix = sanitize_title( $term->name );
	$cards  = '';

	foreach ( gug_risorse_settore_sections() as list( $prefix, $title, $text ) ) {
		$page = get_page_by_path( $prefix . '-' . $suffix );
		if ( ! $page || 'publish' !== $page->post_status ) {
			continue;
		}
		$cards .= sprintf(
			'<a class="gug-sector-links__item" href="%1$s"><span class="gug-sector-links__title">%2$s</span><span class="gug-sector-links__text">%3$s</span></a>',
			esc_url( get_permalink( $page ) ),
			esc_html( $title ),
			esc_html( $text )
		);
	}

	if ( '' === $cards ) {
		return '';
	}

	return '<nav class="gug-sector-links gug-sector--' . esc_attr( $term->slug ) . '" aria-label="' . esc_attr( sprintf( /* translators: %s: nome del settore. */ __( 'Pagine del settore %s', 'gugpiemonte' ), $term->name ) ) . '">' . $cards . '</nav>';
}
add_shortcode( 'risorse-settore', 'gug_shortcode_risorse_settore' );
