<?php
/**
 * Opzioni Blocksy delle pagine del sito.
 *
 * Tutte le pagine ricevono le opzioni richieste dalla grafica senza doverle impostare a mano:
 * struttura a larghezza normale, area contenuto senza spaziatura (le sezioni hanno i propri padding).
 * L'hero è quello nativo di Blocksy (Personalizza → Pagine); solo le pagine con un hero
 * nel contenuto (gruppo "gug-page-hero", es. la Home) disattivano quello di Blocksy.
 *
 * @package gugpiemonte
 */

defined( 'ABSPATH' ) || exit;

/**
 * Indica se un post è una pagina del sito (costruita con i blocchi e i pattern GUG).
 *
 * @param int $post_id ID del post.
 * @return bool True se è una pagina.
 */
function gug_is_pattern_page( int $post_id ): bool {
	return 'page' === get_post_type( $post_id );
}

/**
 * Indica se la pagina ha un hero proprio nel contenuto (gruppo "gug-page-hero").
 *
 * @param int $post_id ID della pagina.
 * @return bool True se l'hero è nel contenuto.
 */
function gug_has_content_hero( int $post_id ): bool {
	$post = get_post( $post_id );

	return $post && false !== strpos( $post->post_content, 'gug-page-hero' );
}

/**
 * Fornisce a Blocksy le opzioni pagina della grafica (meta blocksy_post_meta_options).
 *
 * Usa get_post_metadata perché blocksy_get_post_options() non applica i propri
 * filtri quando la pagina non ha meta salvati. Le altre opzioni salvate
 * nell'editor vengono mantenute.
 *
 * @param mixed  $value     Valore già calcolato (null se non filtrato).
 * @param int    $object_id ID del post.
 * @param string $meta_key  Chiave meta richiesta.
 * @param bool   $single    Se è richiesto un singolo valore.
 * @return mixed Valore meta filtrato.
 */
function gug_page_options( $value, $object_id, $meta_key, $single ) {
	static $running = false;

	if ( 'blocksy_post_meta_options' !== $meta_key || $running || ! gug_is_pattern_page( (int) $object_id ) ) {
		return $value;
	}

	$running = true;
	$saved   = get_post_meta( $object_id, 'blocksy_post_meta_options', true );
	$running = false;

	$saved = is_array( $saved ) ? $saved : array();

	// Hero: 'default' = quello del Customizer; resta possibile un hero personalizzato
	// ('enabled') dall'editor della singola pagina. I vecchi 'disabled' salvati non valgono più.
	$hero = 'enabled' === ( $saved['has_hero_section'] ?? '' ) ? 'enabled' : 'default';

	// Le chiavi della grafica prevalgono: l'editor Blocksy salva i propri default ('default', 'inherit').
	$options = array_merge(
		$saved,
		array(
			'has_hero_section'        => gug_has_content_hero( (int) $object_id ) ? 'disabled' : $hero,
			'page_structure_type'     => 'type-4',
			'content_style_source'    => 'custom',
			'content_style'           => 'wide',
			'vertical_spacing_source' => 'custom',
			'content_area_spacing'    => 'none',
		)
	);

	return $single ? $options : array( $options );
}
add_filter( 'get_post_metadata', 'gug_page_options', 10, 4 );

/**
 * Aggiunge le classi body della grafica:
 * "gug-pattern-page" alle pagine, alla "Composizione della Giuria" e agli archivi dei settori;
 * "gug-page-sector-xx" alle pagine di un settore (riconosciute dalla fine dello slug,
 * es. regolamenti-pallanuoto) per colorare l'hero nativo con la sfumatura del settore.
 *
 * @param string[] $classes Classi del body.
 * @return string[] Classi aggiornate.
 */
function gug_pattern_page_body_class( array $classes ): array {
	if ( is_singular( 'designazioni' ) || is_tax( 'settori' ) || is_page() ) {
		$classes[] = 'gug-pattern-page';
	}

	if ( is_page() ) {
		$sectors = array(
			'nuoto'      => 'nu',
			'pallanuoto' => 'pn',
			'salvamento' => 'sa',
			'artistico'  => 'sy',
			'tuffi'      => 'tu',
		);
		$slug    = (string) get_post_field( 'post_name', get_queried_object_id() );
		$suffix  = substr( strrchr( '-' . $slug, '-' ), 1 );

		if ( isset( $sectors[ $suffix ] ) ) {
			$classes[] = 'gug-page-sector-' . $sectors[ $suffix ];
		}
	}

	return $classes;
}
add_filter( 'body_class', 'gug_pattern_page_body_class', 10 );
