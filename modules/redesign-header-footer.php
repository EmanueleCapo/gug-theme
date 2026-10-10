<?php
/**
 * Redesign 2026: header e footer Blocksy come da grafica, solo se il redesign è attivo.
 *
 * La struttura del Customizer non viene modificata: i valori di header_placements
 * e footer_placements sono filtrati SOLO durante la stampa di header e footer,
 * così il CSS dinamico di Blocksy (uploads/blocksy/css/global.css) resta invariato.
 *
 * @package gugpiemonte
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL del gestionale Federnuoto (pulsante header).
 */
const GUG_REDESIGN_GESTIONALE_URL = 'https://portale.federnuoto.it/';

/**
 * Codici settore usati nelle classi dei menu (menu-xx) e nei modificatori CSS (gug-sector--xx).
 */
const GUG_REDESIGN_SECTORS = array( 'nu', 'pn', 'sa', 'sy', 'tu' );

/**
 * Indica se applicare le modifiche a header e footer nella richiesta corrente.
 *
 * Esclude admin, la finestra di anteprima del Customizer, AJAX e REST per non toccare
 * il salvataggio delle opzioni. L'anteprima di una bozza del Customizer aperta nel
 * frontend (?customize_changeset_uuid=… senza canale del Customizer) è invece inclusa.
 *
 * @return bool True se si è nel frontend con redesign attivo.
 */
function gug_redesign_is_frontend(): bool {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- sola lettura del contesto di anteprima.
	$in_customizer_frame = is_customize_preview() && isset( $_GET['customize_messenger_channel'] );

	return gug_redesign_is_active()
		&& ! is_admin()
		&& ! $in_customizer_frame
		&& ! wp_doing_ajax()
		&& ! ( defined( 'REST_REQUEST' ) && REST_REQUEST );
}

/**
 * Sostituisce le colonne di una riga del builder Blocksy.
 *
 * @param array  $rows      Righe del dispositivo (desktop o mobile).
 * @param string $row_id    ID della riga (es. 'top-row').
 * @param array  $columns   Mappa id colonna => elenco item.
 * @return array Righe aggiornate.
 */
function gug_redesign_set_row_items( array $rows, string $row_id, array $columns ): array {
	foreach ( $rows as &$row ) {
		if ( $row_id !== $row['id'] ) {
			continue;
		}
		foreach ( $row['placements'] as &$placement ) {
			$placement['items'] = $columns[ $placement['id'] ] ?? array();
		}
		unset( $placement );
	}
	unset( $row );

	return $rows;
}

/**
 * Imposta (o aggiunge) i valori di un item del builder Blocksy.
 *
 * @param array  $items   Elenco item della sezione.
 * @param string $item_id ID dell'item (es. 'logo').
 * @param array  $values  Valori da sovrascrivere.
 * @return array Item aggiornati.
 */
function gug_redesign_set_item_values( array $items, string $item_id, array $values ): array {
	foreach ( $items as &$item ) {
		if ( $item_id === $item['id'] ) {
			$item['values'] = array_merge( (array) $item['values'], $values );
			return $items;
		}
	}
	unset( $item );

	$items[] = array(
		'id'     => $item_id,
		'values' => $values,
	);

	return $items;
}

/**
 * Struttura header della grafica: settori nella riga top, logo + titolo, menu e pulsante.
 *
 * @param mixed $placements Valore del theme mod header_placements.
 * @return mixed Valore filtrato.
 */
function gug_redesign_filter_header_placements( $placements ) {
	if ( empty( $placements['sections'] ) ) {
		return $placements;
	}

	foreach ( $placements['sections'] as &$section ) {
		if ( ( $placements['current_section'] ?? 'type-1' ) !== $section['id'] ) {
			continue;
		}

		$section['settings']['has_transparent_header'] = 'no';

		$section['desktop'] = gug_redesign_set_row_items( $section['desktop'], 'top-row', array( 'start' => array( 'menu-secondary' ) ) );
		$section['desktop'] = gug_redesign_set_row_items(
			$section['desktop'],
			'middle-row',
			array(
				'start' => array( 'logo' ),
				'end'   => array( 'menu', 'button' ),
			)
		);
		$section['mobile']  = gug_redesign_set_row_items( $section['mobile'], 'top-row', array() );
		$section['mobile']  = gug_redesign_set_row_items(
			$section['mobile'],
			'middle-row',
			array(
				'start' => array( 'logo' ),
				'end'   => array( 'trigger' ),
			)
		);

		$section['items'] = gug_redesign_set_item_values(
			$section['items'],
			'logo',
			array(
				'has_site_title'  => 'yes',
				'logo_position'   => 'left',
				'has_tagline'     => 'yes',
				'blogname'        => __( 'Gruppo Ufficiali Gara', 'gugpiemonte' ),
				// La tagline di Blocksy non ha link: lo inseriamo per tornare alla home come il titolo.
				'blogdescription' => sprintf( '<a href="%s" rel="home">%s</a>', esc_url( home_url( '/' ) ), esc_html__( "Piemonte e Valle d'Aosta", 'gugpiemonte' ) ),
			)
		);
		$section['items'] = gug_redesign_set_item_values(
			$section['items'],
			'button',
			array(
				'header_button_text'   => __( 'Gestionale Federnuoto', 'gugpiemonte' ),
				'header_button_link'   => GUG_REDESIGN_GESTIONALE_URL,
				'header_button_target' => 'yes',
			)
		);
	}
	unset( $section );

	return $placements;
}

/**
 * Struttura footer della grafica: loghi, una sola colonna centrale (pattern footer-info), copyright.
 *
 * @param mixed $placements Valore del theme mod footer_placements.
 * @return mixed Valore filtrato.
 */
function gug_redesign_filter_footer_placements( $placements ) {
	if ( empty( $placements['sections'] ) ) {
		return $placements;
	}

	foreach ( $placements['sections'] as &$section ) {
		if ( ( $placements['current_section'] ?? 'type-1' ) !== $section['id'] ) {
			continue;
		}

		foreach ( $section['rows'] as &$row ) {
			if ( 'middle-row' === $row['id'] ) {
				$row['columns'] = array( array( 'widget-area-1' ) );
			}
		}
		unset( $row );

		$section['items'] = gug_redesign_set_item_values(
			$section['items'],
			'copyright',
			array(
				/* translators: {current_year} è sostituito da Blocksy con l'anno corrente. */
				'copyright_text' => __( "© {current_year} G.U.G. Piemonte e Valle d'Aosta — Federazione Italiana Nuoto", 'gugpiemonte' ),
			)
		);
	}
	unset( $section );

	return $placements;
}

/**
 * Svuota la cache interna dei placements di un builder Blocksy (header o footer).
 *
 * Blocksy memorizza i placements in proprietà private al primo accesso (già in wp_head):
 * senza azzerarle il filtro sul theme mod non avrebbe effetto.
 *
 * @param string $builder 'header_builder' o 'footer_builder'.
 * @return void
 */
function gug_redesign_reset_builder_cache( string $builder ) {
	if ( ! function_exists( 'blocksy_manager' ) || empty( blocksy_manager()->{$builder} ) ) {
		return;
	}

	$instance = blocksy_manager()->{$builder};
	$reset    = Closure::bind(
		function () {
			$this->section_value   = null;
			$this->current_section = null;
		},
		$instance,
		get_class( $instance )
	);
	$reset();
}

/**
 * Attiva il filtro sull'header subito prima che Blocksy lo stampi (dopo wp_head).
 *
 * @return void
 */
function gug_redesign_header_start() {
	if ( gug_redesign_is_frontend() ) {
		add_filter( 'theme_mod_header_placements', 'gug_redesign_filter_header_placements', 10 );
		gug_redesign_reset_builder_cache( 'header_builder' );
	}
}
add_action( 'blocksy:head:end', 'gug_redesign_header_start', 99 );

/**
 * Disattiva il filtro sull'header dopo la sua stampa.
 *
 * @return void
 */
function gug_redesign_header_end() {
	if ( has_filter( 'theme_mod_header_placements', 'gug_redesign_filter_header_placements' ) ) {
		remove_filter( 'theme_mod_header_placements', 'gug_redesign_filter_header_placements', 10 );
		gug_redesign_reset_builder_cache( 'header_builder' );
	}
}
add_action( 'wp_body_open', 'gug_redesign_header_end', 0 );

/**
 * Attiva il filtro sul footer subito prima che Blocksy lo stampi.
 *
 * @return void
 */
function gug_redesign_footer_start() {
	if ( gug_redesign_is_frontend() ) {
		add_filter( 'theme_mod_footer_placements', 'gug_redesign_filter_footer_placements', 10 );
		gug_redesign_reset_builder_cache( 'footer_builder' );
	}
}
add_action( 'blocksy:footer:before', 'gug_redesign_footer_start', 99 );

/**
 * Disattiva il filtro sul footer dopo la sua stampa.
 *
 * @return void
 */
function gug_redesign_footer_end() {
	if ( has_filter( 'theme_mod_footer_placements', 'gug_redesign_filter_footer_placements' ) ) {
		remove_filter( 'theme_mod_footer_placements', 'gug_redesign_filter_footer_placements', 10 );
		gug_redesign_reset_builder_cache( 'footer_builder' );
	}
}
add_action( 'blocksy:footer:after', 'gug_redesign_footer_end', 0 );

/**
 * Sostituisce i widget del footer: aree 1 e 2 con il pattern gug/footer-info,
 * area 3 (riga dei loghi) con il pattern sincronizzato "Loghi federazioni".
 *
 * Il pattern viene stampato al posto del primo widget dell'area 1; gli altri widget
 * delle aree 1 e 2 non vengono mostrati.
 *
 * @param array|false $instance Impostazioni del widget.
 * @param WP_Widget   $widget   Istanza del widget.
 * @param array       $args     Argomenti della sidebar.
 * @return array|false False per non stampare il widget originale.
 */
function gug_redesign_footer_widgets( $instance, $widget, $args ) {
	static $printed = false;

	if ( ! gug_redesign_is_frontend() || ! in_array( $args['id'] ?? '', array( 'ct-footer-sidebar-1', 'ct-footer-sidebar-2', 'ct-footer-sidebar-3' ), true ) ) {
		return $instance;
	}

	// Riga alta: pattern sincronizzato "Loghi federazioni" al posto del widget con [partner-grid].
	if ( 'ct-footer-sidebar-3' === $args['id'] ) {
		$logos = get_page_by_path( 'loghi-federazioni', OBJECT, 'wp_block' );
		if ( $logos ) {
			echo do_blocks( '<!-- wp:block {"ref":' . (int) $logos->ID . '} /-->' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup dei blocchi del pattern.
		}
		return false;
	}

	if ( ! $printed && 'ct-footer-sidebar-1' === $args['id'] ) {
		$pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( 'gug/footer-info' );
		if ( $pattern ) {
			// do_shortcode: fuori da the_content gli shortcode del pattern (es. [icone-social]) non verrebbero eseguiti.
			echo do_shortcode( do_blocks( $pattern['content'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup dei blocchi del pattern del tema.
		}
		$printed = true;
	}

	return false;
}
add_filter( 'widget_display_callback', 'gug_redesign_footer_widgets', 10, 3 );

/**
 * Aggiunge alle voci dei menu settore (classe menu-xx) il modificatore gug-sector--xx.
 *
 * @param string[] $classes Classi della voce di menu.
 * @return string[] Classi aggiornate.
 */
function gug_redesign_menu_sector_class( array $classes ): array {
	if ( ! gug_redesign_is_frontend() ) {
		return $classes;
	}

	foreach ( GUG_REDESIGN_SECTORS as $sector ) {
		if ( in_array( 'menu-' . $sector, $classes, true ) ) {
			$classes[] = 'gug-sector--' . $sector;
		}
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'gug_redesign_menu_sector_class', 10 );

/**
 * Rimuove dal menu principale la voce "Gestionale Federnuoto", sostituita dal pulsante.
 *
 * @param WP_Post[] $items Voci del menu.
 * @param stdClass  $args  Argomenti di wp_nav_menu().
 * @return WP_Post[] Voci filtrate.
 */
function gug_redesign_menu_remove_gestionale( array $items, $args ): array {
	if ( ! gug_redesign_is_frontend() || 'menu_1' !== ( $args->theme_location ?? '' ) ) {
		return $items;
	}

	return array_filter(
		$items,
		static function ( $item ) {
			return false === strpos( (string) $item->url, 'portale.federnuoto.it' );
		}
	);
}
add_filter( 'wp_nav_menu_objects', 'gug_redesign_menu_remove_gestionale', 10, 2 );
