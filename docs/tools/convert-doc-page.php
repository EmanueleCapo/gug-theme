<?php
/**
 * Strumento CLI: converte una pagina documenti attuale (accordion Qubely + blocchi ACF "listing")
 * nella struttura del redesign (hero di settore + core/details + elenco gug-doclist).
 *
 * Uso:
 *   php convert-doc-page.php <pagina.json> <media.json> > bozza.json
 *
 * pagina.json: risposta REST /wp/v2/pages/ID?context=edit (id, slug, title.raw, content.raw).
 * media.json:  mappa { "ID allegato": "URL" } degli allegati usati nei blocchi listing.
 *
 * Su STDERR stampa il riepilogo (categorie, documenti, documenti senza link) per la verifica.
 *
 * @package gugpiemonte
 */

if ( 'cli' !== PHP_SAPI ) {
	exit( 1 );
}

const GUG_SECTOR_BY_SLUG = array(
	'pallanuoto' => 'pn',
	'salvamento' => 'sa',
	'artistico'  => 'sy',
	'tuffi'      => 'tu',
	'nuoto'      => 'nu',
);

/**
 * Esegue l'escape del testo per il markup dei blocchi (come fa l'editor).
 *
 * @param string $text Testo.
 * @return string Testo con &, < e > codificati.
 */
function gug_text( string $text ): string {
	return htmlspecialchars( html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' ), ENT_NOQUOTES, 'UTF-8' );
}

/**
 * Ricava il codice settore dallo slug della pagina (es. regolamenti-pallanuoto → pn).
 *
 * @param string $slug Slug della pagina.
 * @return string Codice settore.
 */
function gug_sector_from_slug( string $slug ): string {
	foreach ( GUG_SECTOR_BY_SLUG as $suffix => $code ) {
		if ( str_ends_with( $slug, '-' . $suffix ) ) {
			return $code;
		}
	}
	fwrite( STDERR, "Settore non riconosciuto per lo slug {$slug}\n" );
	exit( 1 );
}

/**
 * Genera il blocco core/list con classe gug-doclist.
 *
 * @param array $documents Documenti: array di [ 'title' => string, 'url' => string ].
 * @return string Markup del blocco.
 */
function gug_doclist( array $documents ): string {
	$items = array();
	foreach ( $documents as $document ) {
		$title   = gug_text( $document['title'] );
		$inner   = '' !== $document['url']
			? '<a href="' . htmlspecialchars( $document['url'], ENT_QUOTES, 'UTF-8' ) . '" target="_blank" rel="noreferrer noopener">' . $title . '</a>'
			: '<span class="gug-doclist__static">' . $title . '</span>';
		$items[] = "<!-- wp:list-item -->\n<li>{$inner}</li>\n<!-- /wp:list-item -->";
	}

	return "<!-- wp:list {\"className\":\"gug-doclist\"} -->\n<ul class=\"wp-block-list gug-doclist\">" . implode( "\n\n", $items ) . "</ul>\n<!-- /wp:list -->";
}

list( , $page_file, $media_file ) = $argv + array( null, null, null );
if ( ! $page_file || ! $media_file ) {
	fwrite( STDERR, "Uso: php convert-doc-page.php <pagina.json> <media.json>\n" );
	exit( 1 );
}

$page    = json_decode( file_get_contents( $page_file ), true );
$media   = json_decode( file_get_contents( $media_file ), true );
$content = $page['content']['raw'];
$sector  = gug_sector_from_slug( $page['slug'] );
$title   = $page['title']['raw'];

// Ogni voce dell'accordion Qubely diventa un core/details.
preg_match_all( '#<!-- wp:qubely/accordion-item (\{.*?\}) -->(.*?)<!-- /wp:qubely/accordion-item -->#s', $content, $items, PREG_SET_ORDER );

$details   = array();
$summary   = array();
$total     = 0;
$no_link   = array();
foreach ( $items as $item ) {
	$attrs = json_decode( $item[1], true );
	$label = rtrim( trim( $attrs['heading'] ?? '' ), ':' );

	// Titoli e blocchi listing nell'ordine in cui compaiono.
	preg_match_all( '#<!-- wp:heading[^>]*-->\s*<h\d[^>]*>(.*?)</h\d>\s*<!-- /wp:heading -->|<!-- wp:acf/listing (\{.*?\}) /-->#s', $item[2], $parts, PREG_SET_ORDER );

	$inner   = array();
	$pending = array();
	$count   = 0;
	foreach ( $parts as $part ) {
		if ( ! empty( $part[1] ) ) {
			if ( $pending ) {
				$inner[] = gug_doclist( $pending );
				$pending = array();
			}
			$heading = rtrim( trim( strip_tags( $part[1] ) ), ':' );
			$inner[] = "<!-- wp:heading {\"level\":3,\"className\":\"gug-heading gug-heading--sm\"} -->\n<h3 class=\"wp-block-heading gug-heading gug-heading--sm\">" . gug_text( $heading ) . "</h3>\n<!-- /wp:heading -->";
			continue;
		}

		$block = json_decode( $part[2], true );
		$data  = $block['data'] ?? array();
		$rows  = (int) ( $data['listing'] ?? 0 );
		for ( $i = 0; $i < $rows; $i++ ) {
			$file = $data[ "listing_{$i}_file_link" ] ?? '';
			$url  = '';
			if ( '' !== $file && null !== $file ) {
				if ( empty( $media[ (string) $file ] ) ) {
					fwrite( STDERR, "Allegato {$file} non trovato (pagina {$page['id']})\n" );
					exit( 1 );
				}
				$url = $media[ (string) $file ];
			} elseif ( ! empty( $data[ "listing_{$i}_external_link" ] ) ) {
				$url = $data[ "listing_{$i}_external_link" ];
			}
			$doc_title = $data[ "listing_{$i}_title" ] ?? '';
			if ( '' === $url ) {
				$no_link[] = $doc_title;
			}
			$pending[] = array(
				'title' => $doc_title,
				'url'   => $url,
			);
			++$count;
		}
	}
	if ( $pending ) {
		$inner[] = gug_doclist( $pending );
	}

	$total    += $count;
	$summary[] = "{$label} ({$count})";
	$details[] = "<!-- wp:details {\"showContent\":true,\"className\":\"gug-accordion\"} -->\n<details class=\"wp-block-details gug-accordion\" open><summary>" . gug_text( $label ) . '</summary>' . implode( "\n\n", $inner ) . "</details>\n<!-- /wp:details -->";
}

$hero = "<!-- wp:group {\"tagName\":\"section\",\"className\":\"gug-hero gug-sector--{$sector}\",\"layout\":{\"type\":\"default\"}} -->\n"
	. "<section class=\"wp-block-group gug-hero gug-sector--{$sector}\"><!-- wp:heading {\"level\":1,\"className\":\"gug-hero__title\"} -->\n"
	. '<h1 class="wp-block-heading gug-hero__title">' . gug_text( $title ) . "</h1>\n<!-- /wp:heading --></section>\n<!-- /wp:group -->";

$section = "<!-- wp:group {\"tagName\":\"section\",\"className\":\"gug-section\",\"layout\":{\"type\":\"default\"}} -->\n"
	. '<section class="wp-block-group gug-section">' . implode( "\n\n", $details ) . "</section>\n<!-- /wp:group -->";

fwrite( STDERR, sprintf( "%d %s [%s]: %s — %d documenti%s\n", $page['id'], $page['slug'], $sector, implode( ', ', $summary ), $total, $no_link ? ' — senza link: ' . implode( '; ', $no_link ) : '' ) );

echo json_encode(
	array(
		'title'   => $title,
		'slug'    => $page['slug'] . '-redesign',
		'status'  => 'draft',
		'content' => $hero . "\n\n" . $section,
	),
	JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);
