<?php
/**
 * Shortcode [elenco-designazioni settore="xx"]: designazioni della settimana per settore.
 *
 * Redesign 2026: sostituisce [listing-designazioni]. Stessa query (settimana corrente,
 * ordinamento menu_order, campi ACF "dettagli_*"), markup unico responsive
 * (tabella che diventa card su mobile grazie agli attributi data-label).
 *
 * @package gugpiemonte
 */

defined( 'ABSPATH' ) || exit;

/**
 * Calcola lunedì e domenica (formato Ymd) della settimana da mostrare.
 *
 * Gli utenti che possono modificare i contenuti possono vedere un'altra settimana
 * con il parametro ?gug_settimana=AAAAMMGG (utile per verificare il layout).
 *
 * @return string[] Array [ lunedì, domenica ] in formato Ymd.
 */
function gug_designazioni_week_range(): array {
	$reference = 'now';

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- sola lettura, riservata agli editor.
	if ( isset( $_GET['gug_settimana'] ) && current_user_can( 'edit_posts' ) ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$date = preg_replace( '/\D/', '', sanitize_text_field( wp_unslash( $_GET['gug_settimana'] ) ) );
		if ( 8 === strlen( $date ) ) {
			$reference = $date;
		}
	}

	$timestamp = strtotime( $reference );

	return array(
		gmdate( 'Ymd', strtotime( 'monday this week', $timestamp ) ),
		gmdate( 'Ymd', strtotime( 'sunday this week', $timestamp ) ),
	);
}

/**
 * Restituisce le designazioni della settimana per un settore.
 *
 * @param string $sector Slug del termine "settori" (nu, pn, sa, sy, tu).
 * @return WP_Query Query delle designazioni.
 */
function gug_designazioni_week_query( string $sector ): WP_Query {
	list( $monday, $sunday ) = gug_designazioni_week_range();

	return new WP_Query(
		array(
			'post_type'      => 'designazioni',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'no_found_rows'  => true,
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'settori',
					'field'    => 'slug',
					'terms'    => $sector,
				),
			),
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => 'dettagli_data',
					'value'   => array( $monday, $sunday ),
					'type'    => 'NUMERIC',
					'compare' => 'BETWEEN',
				),
			),
		)
	);
}

/**
 * Genera il link azione (Giuria / Regolamento) di una riga.
 *
 * @param string $url   URL di destinazione.
 * @param string $label Testo del link.
 * @return string Markup del link.
 */
function gug_designazioni_action_link( string $url, string $label ): string {
	return sprintf(
		'<a class="gug-table__action gug-arrow-link" href="%s" target="_blank" rel="noopener">%s</a>',
		esc_url( $url ),
		esc_html( $label )
	);
}

/**
 * Render dello shortcode [elenco-designazioni].
 *
 * @param array|string $atts Attributi dello shortcode (settore).
 * @return string Markup della tabella o messaggio vuoto.
 */
function gug_shortcode_elenco_designazioni( $atts ): string {
	$atts   = shortcode_atts( array( 'settore' => '' ), $atts, 'elenco-designazioni' );
	$sector = sanitize_key( $atts['settore'] );

	if ( '' === $sector ) {
		return '';
	}

	$query = gug_designazioni_week_query( $sector );

	if ( ! $query->have_posts() ) {
		return '<p class="gug-empty">' . esc_html__( 'Nessuna manifestazione', 'gugpiemonte' ) . '</p>';
	}

	$is_water_polo = 'pn' === $sector;
	$rows          = '';

	while ( $query->have_posts() ) {
		$query->the_post();
		$post_id     = get_the_ID();
		$date        = (string) get_field( 'dettagli_data', $post_id );
		$city        = (string) get_field( 'dettagli_citta', $post_id );
		$pool        = (string) get_field( 'dettagli_piscina', $post_id );
		$jury        = get_field( 'giuria', $post_id );
		$jury_cell   = ! empty( $jury ) ? gug_designazioni_action_link( get_permalink(), __( 'Giuria', 'gugpiemonte' ) ) : '';

		if ( $is_water_polo ) {
			$series = (string) get_field( 'dettagli_serie-categoria', $post_id );
			$rows  .= '<tr class="gug-table__row">'
				. '<td class="gug-table__date">' . esc_html( $date ) . '</td>'
				. '<td data-label="' . esc_attr__( 'Serie/Cat.', 'gugpiemonte' ) . '">' . ( '' !== $series ? '<span class="gug-table__badge">' . esc_html( $series ) . '</span>' : '' ) . '</td>'
				. '<td class="gug-table__name">' . esc_html( get_the_title() ) . '</td>'
				. '<td data-label="' . esc_attr__( 'Città', 'gugpiemonte' ) . '">' . esc_html( $city ) . '</td>'
				. '<td data-label="' . esc_attr__( 'Piscina', 'gugpiemonte' ) . '">' . esc_html( $pool ) . '</td>'
				. '<td>' . $jury_cell . '</td>'
				. '</tr>';
			continue;
		}

		$rules = (string) get_field( 'dettagli_regolamento_manifestazione', $post_id );
		$place = implode( ' — ', array_filter( array( $city, $pool ) ) );
		$rows .= '<tr class="gug-table__row">'
			. '<td class="gug-table__date">' . esc_html( $date ) . '</td>'
			. '<td class="gug-table__name">' . esc_html( get_the_title() ) . ( '' !== $place ? '<span class="gug-table__place">' . esc_html( $place ) . '</span>' : '' ) . '</td>'
			. '<td>' . $jury_cell . '</td>'
			. '<td>' . ( '' !== $rules ? gug_designazioni_action_link( $rules, __( 'Regolamento', 'gugpiemonte' ) ) : '' ) . '</td>'
			. '</tr>';
	}
	wp_reset_postdata();

	if ( $is_water_polo ) {
		$head = '<th scope="col">' . esc_html__( 'Data', 'gugpiemonte' ) . '</th>'
			. '<th scope="col">' . esc_html__( 'Serie/Cat.', 'gugpiemonte' ) . '</th>'
			. '<th scope="col">' . esc_html__( 'Nome Partita', 'gugpiemonte' ) . '</th>'
			. '<th scope="col">' . esc_html__( 'Città', 'gugpiemonte' ) . '</th>'
			. '<th scope="col">' . esc_html__( 'Piscina', 'gugpiemonte' ) . '</th>'
			. '<th scope="col">' . esc_html__( 'Giuria', 'gugpiemonte' ) . '</th>';
		$type = 'partite';
	} else {
		$head = '<th scope="col">' . esc_html__( 'Data', 'gugpiemonte' ) . '</th>'
			. '<th scope="col">' . esc_html__( 'Nome manifestazione', 'gugpiemonte' ) . '</th>'
			. '<th scope="col">' . esc_html__( 'Giuria', 'gugpiemonte' ) . '</th>'
			. '<th scope="col">' . esc_html__( 'Regolamento', 'gugpiemonte' ) . '</th>';
		$type = 'designazioni';
	}

	return '<table class="gug-table gug-table--' . $type . '"><thead class="gug-table__head"><tr>' . $head . '</tr></thead><tbody>' . $rows . '</tbody></table>';
}
add_shortcode( 'elenco-designazioni', 'gug_shortcode_elenco_designazioni' );
