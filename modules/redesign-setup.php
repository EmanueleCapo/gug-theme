<?php
/**
 * Redesign 2026: interruttore, asset e categorie dei block pattern.
 *
 * Fino al go-live il redesign è visibile solo agli utenti loggati.
 * Al go-live: definire GUG_REDESIGN_LIVE a true (o rimuovere la condizione).
 *
 * @package gugpiemonte
 */

defined( 'ABSPATH' ) || exit;

/**
 * Indica se il redesign è attivo per la richiesta corrente.
 *
 * @return bool True se live per tutti o se l'utente è loggato.
 */
function gug_redesign_is_active(): bool {
	if ( defined( 'GUG_REDESIGN_LIVE' ) && GUG_REDESIGN_LIVE ) {
		return true;
	}

	return is_user_logged_in();
}

/**
 * Restituisce la versione di un file del tema basata sulla data di modifica.
 *
 * @param string $relative_path Percorso relativo alla cartella del tema figlio.
 * @return string|false Timestamp di modifica o false se il file non esiste.
 */
function gug_redesign_asset_version( string $relative_path ) {
	$file = get_stylesheet_directory() . '/' . $relative_path;

	return file_exists( $file ) ? (string) filemtime( $file ) : false;
}

/**
 * URL dei Google Fonts usati dal redesign (Barlow e Barlow Condensed).
 *
 * @return string URL del foglio di stile Google Fonts.
 */
function gug_redesign_fonts_url(): string {
	return 'https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&family=Barlow+Condensed:wght@700;800&display=swap';
}

/**
 * Carica font e fogli di stile del redesign nel frontend.
 *
 * Dipende dallo stile 'gug' (vecchio CSS del tema) per essere caricato dopo
 * e poterlo sovrascrivere finché i due convivono.
 *
 * @return void
 */
function gug_redesign_enqueue_assets() {
	if ( ! gug_redesign_is_active() ) {
		return;
	}

	wp_enqueue_style( 'gug-fonts', gug_redesign_fonts_url(), array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Google Fonts non accetta versioni.

	wp_enqueue_style(
		'gug-redesign',
		get_stylesheet_directory_uri() . '/css/gug.min.css',
		array( 'gug', 'gug-fonts' ),
		gug_redesign_asset_version( 'css/gug.min.css' )
	);

	if ( is_singular( 'designazioni' ) ) {
		wp_enqueue_style(
			'gug-redesign-print',
			get_stylesheet_directory_uri() . '/css/gug-print.min.css',
			array( 'gug-redesign' ),
			gug_redesign_asset_version( 'css/gug-print.min.css' ),
			'print'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'gug_redesign_enqueue_assets', 20 );

/**
 * Aggiunge la classe body "gug-redesign" quando il redesign è attivo.
 *
 * @param string[] $classes Classi del body.
 * @return string[] Classi aggiornate.
 */
function gug_redesign_body_class( array $classes ): array {
	if ( gug_redesign_is_active() ) {
		$classes[] = 'gug-redesign';
	}

	return $classes;
}
add_filter( 'body_class', 'gug_redesign_body_class', 10 );

/**
 * Registra le categorie dei block pattern.
 *
 * I pattern in /patterns sono registrati automaticamente da WordPress.
 *
 * @return void
 */
function gug_redesign_register_pattern_categories() {
	register_block_pattern_category( 'gug-pagine', array( 'label' => __( 'GUG — Pagine', 'gugpiemonte' ) ) );
	register_block_pattern_category( 'gug-layout', array( 'label' => __( 'GUG — Layout', 'gugpiemonte' ) ) );
}
add_action( 'init', 'gug_redesign_register_pattern_categories', 10 );

/**
 * Svuota la cache dei pattern del tema quando i file in /patterns cambiano.
 *
 * WordPress memorizza l'elenco dei pattern del tema e lo aggiorna solo al
 * cambio di tema: senza questo controllo i pattern caricati via FTP non compaiono.
 *
 * @return void
 */
function gug_redesign_refresh_pattern_cache() {
	$files = glob( get_stylesheet_directory() . '/patterns/*.php' );

	if ( empty( $files ) ) {
		return;
	}

	$signature = '';
	foreach ( $files as $file ) {
		$signature .= basename( $file ) . filemtime( $file );
	}
	$signature = md5( $signature );

	if ( get_option( 'gug_redesign_patterns_signature' ) === $signature ) {
		return;
	}

	wp_get_theme()->delete_pattern_cache();
	update_option( 'gug_redesign_patterns_signature', $signature, false );
}
add_action( 'init', 'gug_redesign_refresh_pattern_cache', 1 );

/**
 * Carica font e CSS del redesign nell'editor a blocchi.
 *
 * @return void
 */
function gug_redesign_editor_styles() {
	add_theme_support( 'editor-styles' );
	add_editor_style( array( gug_redesign_fonts_url(), 'css/gug.min.css' ) );
}
add_action( 'after_setup_theme', 'gug_redesign_editor_styles', 20 );
