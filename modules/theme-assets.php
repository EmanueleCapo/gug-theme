<?php
/**
 * Asset del tema: CSS, font locali, classi body, categorie dei block pattern.
 *
 * @package gugpiemonte
 */

defined( 'ABSPATH' ) || exit;

/**
 * Restituisce la versione di un file del tema basata sulla data di modifica.
 *
 * @param string $relative_path Percorso relativo alla cartella del tema figlio.
 * @return string|false Timestamp di modifica o false se il file non esiste.
 */
function gug_asset_version( string $relative_path ) {
	$file = get_stylesheet_directory() . '/' . $relative_path;

	return file_exists( $file ) ? (string) filemtime( $file ) : false;
}

/**
 * Disattiva il caricamento dei Google Fonts da CDN di Blocksy:
 * Barlow e Barlow Condensed sono serviti in locale dal tema figlio (sass/_fonts.scss).
 *
 * @return bool Sempre false.
 */
function gug_disable_remote_fonts() {
	return false;
}
add_filter( 'blocksy:typography:google:use-remote', 'gug_disable_remote_fonts', 10 );

/**
 * Carica il CSS del tema e, sulla singola designazione, il CSS di stampa della giuria.
 *
 * @return void
 */
function gug_enqueue_assets() {
	wp_enqueue_style(
		'gug-theme',
		get_stylesheet_directory_uri() . '/css/gug.min.css',
		array(),
		gug_asset_version( 'css/gug.min.css' )
	);

	if ( is_singular( 'designazioni' ) ) {
		wp_enqueue_style(
			'gug-print',
			get_stylesheet_directory_uri() . '/css/gug-print.min.css',
			array( 'gug-theme' ),
			gug_asset_version( 'css/gug-print.min.css' ),
			'print'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'gug_enqueue_assets', 20 );

/**
 * Toglie lo script di Page Links To: serve solo ai link "apri in nuova scheda", non usati,
 * ma il plugin lo carica su ogni pagina (pesava sul mobile).
 *
 * @return void
 */
function gug_dequeue_unused_scripts() {
	wp_dequeue_script( 'page-links-to' );
}
add_action( 'wp_enqueue_scripts', 'gug_dequeue_unused_scripts', 100 );

/**
 * Precarica i font usati in cima alla pagina (testo, titolo del logo, titolo dell'hero):
 * senza, il testo si ridisegna quando arriva Barlow e la pagina "salta" (CLS su mobile).
 *
 * @return void
 */
function gug_preload_fonts() {
	foreach ( array( 'barlow-400-latin', 'barlow-condensed-700-latin', 'barlow-condensed-800-latin' ) as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_stylesheet_directory_uri() . '/assets/fonts/' . $font . '.woff2' )
		);
	}
}
add_action( 'wp_head', 'gug_preload_fonts', 1 );

/**
 * Aggiunge la classe body "gug-redesign", su cui si basano i selettori del CSS del tema.
 *
 * @param string[] $classes Classi del body.
 * @return string[] Classi aggiornate.
 */
function gug_body_class( array $classes ): array {
	$classes[] = 'gug-redesign';

	return $classes;
}
add_filter( 'body_class', 'gug_body_class', 10 );

/**
 * Registra le categorie dei block pattern.
 *
 * I pattern in /patterns sono registrati automaticamente da WordPress.
 *
 * @return void
 */
function gug_register_pattern_categories() {
	register_block_pattern_category( 'gug-pagine', array( 'label' => __( 'GUG — Pagine', 'gugpiemonte' ) ) );
	register_block_pattern_category( 'gug-layout', array( 'label' => __( 'GUG — Layout', 'gugpiemonte' ) ) );
}
add_action( 'init', 'gug_register_pattern_categories', 10 );

/**
 * Svuota la cache dei pattern del tema quando i file in /patterns cambiano.
 *
 * WordPress memorizza l'elenco dei pattern del tema e lo aggiorna solo al
 * cambio di tema: senza questo controllo i pattern caricati via FTP non compaiono.
 *
 * @return void
 */
function gug_refresh_pattern_cache() {
	$files = glob( get_stylesheet_directory() . '/patterns/*.php' );

	if ( empty( $files ) ) {
		return;
	}

	$signature = '';
	foreach ( $files as $file ) {
		$signature .= basename( $file ) . filemtime( $file );
	}
	$signature = md5( $signature );

	if ( get_option( 'gug_patterns_signature' ) === $signature ) {
		return;
	}

	wp_get_theme()->delete_pattern_cache();
	update_option( 'gug_patterns_signature', $signature, false );
}
add_action( 'init', 'gug_refresh_pattern_cache', 1 );

/**
 * Carica font e CSS del tema nell'editor a blocchi.
 *
 * @return void
 */
function gug_editor_styles() {
	add_theme_support( 'editor-styles' );
	add_editor_style( 'css/gug.min.css' );
}
add_action( 'after_setup_theme', 'gug_editor_styles', 20 );

/**
 * Impedisce ai visitatori di vedere l'anteprima delle bozze del Customizer.
 *
 * WordPress mostra a chiunque abbia il link (?customize_changeset_uuid=…) la bozza del
 * Customizer: chi non può personalizzare il tema viene rimandato alla pagina normale.
 *
 * @return void
 */
function gug_block_public_changeset_preview() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- sola lettura del parametro.
	if ( is_admin() || ! isset( $_GET['customize_changeset_uuid'] ) || current_user_can( 'customize' ) ) {
		return;
	}

	wp_safe_redirect( remove_query_arg( array( 'customize_changeset_uuid', 'customize_autosaved', 'customize_messenger_channel' ) ), 302 );
	exit;
}
add_action( 'init', 'gug_block_public_changeset_preview', 1 );
