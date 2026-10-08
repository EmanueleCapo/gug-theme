<?php
/**
 * Redesign 2026: impostazioni Blocksy delle pagine costruite con i pattern GUG.
 *
 * Le pagine nuove (riconosciute dal blocco hero "gug-hero" nel contenuto) ricevono
 * le opzioni pagina richieste dalla grafica senza doverle impostare a mano:
 * titolo Blocksy disattivato, struttura a larghezza normale, area contenuto
 * senza spaziatura. Le pagine vecchie non vengono toccate.
 *
 * @package gugpiemonte
 */

defined( 'ABSPATH' ) || exit;

/**
 * Indica se un post è una pagina costruita con i pattern del redesign.
 *
 * @param int $post_id ID del post.
 * @return bool True se è una pagina con hero GUG.
 */
function gug_redesign_is_pattern_page( int $post_id ): bool {
	$post = get_post( $post_id );

	return $post
		&& 'page' === $post->post_type
		&& false !== strpos( $post->post_content, 'gug-hero' );
}

/**
 * Fornisce a Blocksy le opzioni pagina del redesign (meta blocksy_post_meta_options).
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
function gug_redesign_page_options( $value, $object_id, $meta_key, $single ) {
	static $running = false;

	if ( 'blocksy_post_meta_options' !== $meta_key || $running || ! gug_redesign_is_pattern_page( (int) $object_id ) ) {
		return $value;
	}

	$running = true;
	$saved   = get_post_meta( $object_id, 'blocksy_post_meta_options', true );
	$running = false;

	// Le chiavi del redesign prevalgono: l'editor Blocksy salva i propri default ('default', 'inherit').
	$options = array_merge(
		is_array( $saved ) ? $saved : array(),
		array(
			'has_hero_section'        => 'disabled',
			'page_structure_type'     => 'type-4',
			'content_style_source'    => 'custom',
			'content_style'           => 'wide',
			'vertical_spacing_source' => 'custom',
			'content_area_spacing'    => 'none',
		)
	);

	return $single ? $options : array( $options );
}
add_filter( 'get_post_metadata', 'gug_redesign_page_options', 10, 4 );

/**
 * Aggiunge la classe body "gug-pattern-page" alle pagine costruite con i pattern GUG.
 *
 * @param string[] $classes Classi del body.
 * @return string[] Classi aggiornate.
 */
function gug_redesign_pattern_page_body_class( array $classes ): array {
	if ( is_page() && gug_redesign_is_pattern_page( (int) get_queried_object_id() ) ) {
		$classes[] = 'gug-pattern-page';
	}

	return $classes;
}
add_filter( 'body_class', 'gug_redesign_pattern_page_body_class', 10 );
