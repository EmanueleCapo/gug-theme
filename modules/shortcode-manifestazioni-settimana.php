<?php
/**
 * Shortcode [manifestazioni-settimana]: manifestazioni della settimana per settore (Home).
 *
 * Redesign 2026: sostituisce [designazioni-home]. Una card per ogni settore (sempre tutte),
 * manifestazioni raggruppate per giorno; se presente usa il "titolo riepilogo" e raggruppa
 * le designazioni con lo stesso riepilogo (più giurie della stessa manifestazione).
 * La pallanuoto mostra solo il link alle partite in programma.
 *
 * @package gugpiemonte
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL della pagina Designazioni di un settore (es. /designazioni-nuoto/).
 *
 * @param WP_Term $sector Termine della tassonomia "settori".
 * @return string URL della pagina.
 */
function gug_manifestazioni_sector_url( WP_Term $sector ): string {
	return home_url( '/designazioni-' . sanitize_title( $sector->name ) . '/' );
}

/**
 * Raggruppa le designazioni della settimana per giorno.
 *
 * @param WP_Query $query Designazioni del settore.
 * @return array Mappa Ymd => elenco titoli (senza duplicati consecutivi del riepilogo).
 */
function gug_manifestazioni_group_by_day( WP_Query $query ): array {
	$days = array();

	foreach ( $query->posts as $post ) {
		$day     = (string) get_post_meta( $post->ID, 'dettagli_data', true );
		$summary = (string) get_field( 'dettagli_titolo_riepilogo', $post->ID );
		$title   = '' !== $summary ? $summary : $post->post_title;

		if ( ! isset( $days[ $day ] ) ) {
			$days[ $day ] = array();
		}
		// Più giurie della stessa manifestazione: un solo titolo per giorno.
		if ( ! in_array( $title, $days[ $day ], true ) ) {
			$days[ $day ][] = $title;
		}
	}

	ksort( $days );

	return $days;
}

/**
 * Render dello shortcode [manifestazioni-settimana].
 *
 * @return string Markup delle card dei settori.
 */
function gug_shortcode_manifestazioni_settimana(): string {
	$sectors = get_terms(
		array(
			'taxonomy'   => 'settori',
			'order'      => 'ASC',
			'hide_empty' => false,
		)
	);

	if ( is_wp_error( $sectors ) || empty( $sectors ) ) {
		return '';
	}

	$cards = '';

	foreach ( $sectors as $sector ) {
		$query = gug_designazioni_week_query( $sector->slug );
		$url   = gug_manifestazioni_sector_url( $sector );
		$body  = '';

		if ( 'pn' === $sector->slug ) {
			$body = $query->have_posts()
				? '<a class="gug-event-card__cta gug-arrow-link" href="' . esc_url( $url ) . '">' . esc_html__( 'Visualizza partite in programma', 'gugpiemonte' ) . '</a>'
				: '<p class="gug-event-card__empty">' . esc_html__( 'Nessuna partita in programma', 'gugpiemonte' ) . '</p>';
		} elseif ( ! $query->have_posts() ) {
			$body = '<p class="gug-event-card__empty">' . esc_html__( 'Nessuna manifestazione', 'gugpiemonte' ) . '</p>';
		} else {
			foreach ( gug_manifestazioni_group_by_day( $query ) as $day => $titles ) {
				$timestamp = strtotime( $day );
				$label     = $timestamp ? ucfirst( wp_date( 'l j F', $timestamp ) ) : $day;
				$items     = '';
				foreach ( $titles as $title ) {
					$items .= '<li class="gug-event-card__item"><a class="gug-event-card__link" href="' . esc_url( $url ) . '">' . esc_html( wptexturize( $title ) ) . '</a></li>';
				}
				$body .= '<div class="gug-event-card__day"><p class="gug-event-card__date">' . esc_html( $label ) . '</p><ul class="gug-event-card__list">' . $items . '</ul></div>';
			}
		}

		$cards .= '<article class="gug-event-card gug-sector--' . esc_attr( $sector->slug ) . '">'
			. '<h2 class="gug-event-card__title">' . esc_html( $sector->name ) . '</h2>'
			. $body
			. '</article>';
	}

	return '<div class="gug-events">' . $cards . '</div>';
}
add_shortcode( 'manifestazioni-settimana', 'gug_shortcode_manifestazioni_settimana' );
