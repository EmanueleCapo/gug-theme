<?php
/**
 * Strumento CLI: genera il JSON per creare/aggiornare una pagina via REST da un pattern del tema.
 *
 * Uso:
 *   php pattern-page.php <pattern.php> <titolo> <slug> [sostituzioni.json] > pagina.json
 *
 * sostituzioni.json è una mappa { "testo da cercare": "testo sostitutivo" } applicata
 * al markup del pattern (es. link segnaposto → URL reali dei documenti).
 * Ogni sostituzione deve trovare almeno un'occorrenza, altrimenti lo script si ferma.
 *
 * @package gugpiemonte
 */

if ( 'cli' !== PHP_SAPI ) {
	exit( 1 );
}

/**
 * Stub di esc_url() per eseguire il pattern fuori da WordPress.
 *
 * @param string $url URL.
 * @return string URL invariato.
 */
function esc_url( $url ) {
	return $url;
}

/**
 * Stub di get_stylesheet_directory_uri() con l'URL di produzione del tema.
 *
 * @return string URL del tema figlio.
 */
function get_stylesheet_directory_uri() {
	return 'https://www.gugpiemonte.it/wp-content/themes/gugpiemonte';
}

list( , $pattern_file, $title, $slug ) = $argv + array( null, null, null, null );
$replacements_file                       = $argv[4] ?? null;

if ( ! $pattern_file || ! $title || ! $slug ) {
	fwrite( STDERR, "Uso: php pattern-page.php <pattern.php> <titolo> <slug> [sostituzioni.json]\n" );
	exit( 1 );
}

ob_start();
include $pattern_file;
$content = trim( ob_get_clean() );

if ( $replacements_file ) {
	$replacements = json_decode( file_get_contents( $replacements_file ), true );
	foreach ( $replacements as $search => $replace ) {
		if ( false === strpos( $content, $search ) ) {
			fwrite( STDERR, "Sostituzione non trovata: {$search}\n" );
			exit( 1 );
		}
		$content = str_replace( $search, $replace, $content );
	}
}

$remaining = preg_match_all( '#/wp-content/uploads/[a-z0-9-]+\.(pdf|xlsx?|docx?)#i', $content, $matches );
if ( $remaining ) {
	fwrite( STDERR, 'Attenzione, link segnaposto rimasti: ' . implode( ', ', array_unique( $matches[0] ) ) . "\n" );
}

echo json_encode(
	array(
		'title'   => $title,
		'slug'    => $slug,
		'status'  => 'draft',
		'content' => $content,
	),
	JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);
