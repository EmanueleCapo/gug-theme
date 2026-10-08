<?php
/**
 * Redesign 2026: "Composizione della Giuria" (singola designazione).
 *
 * Stessa marcatura per schermo e stampa (css/gug-print.min.css, media print).
 * Dati: gruppo ACF "dettagli" e repeater "giuria" (mansioni_{settore}, convocati).
 *
 * @package gugpiemonte
 */

defined( 'ABSPATH' ) || exit;

$gug_post_id  = get_the_ID();
$gug_terms    = wp_get_post_terms( $gug_post_id, 'settori' );
$gug_sector   = ( ! is_wp_error( $gug_terms ) && ! empty( $gug_terms ) ) ? $gug_terms[0]->slug : '';
$gug_is_pn    = 'pn' === $gug_sector;
$gug_details  = (array) get_field( 'dettagli', $gug_post_id );
$gug_jury     = get_field( 'giuria', $gug_post_id );
$gug_title    = wptexturize( get_post_field( 'post_title', $gug_post_id ) );

// Righe della scheda manifestazione: etichetta => chiave del gruppo ACF "dettagli" (come il template precedente).
$gug_info = array( __( 'Data', 'gugpiemonte' ) => 'data' );
if ( $gug_is_pn ) {
	$gug_info = array( __( 'Serie / Categoria', 'gugpiemonte' ) => 'serie-categoria' ) + $gug_info;
}
$gug_info += array(
	__( 'Città', 'gugpiemonte' )            => 'citta',
	__( 'Piscina', 'gugpiemonte' )          => 'piscina',
	__( 'Orario di inizio', 'gugpiemonte' ) => 'orario',
);
if ( in_array( $gug_sector, array( 'nu', 'sa' ), true ) ) {
	$gug_info[ __( 'Cronometraggio', 'gugpiemonte' ) ] = 'cronometraggio';
}
?>
<article class="gug-giuria">
	<header class="gug-hero<?php echo $gug_sector ? ' gug-sector--' . esc_attr( $gug_sector ) : ''; ?>">
		<h1 class="gug-hero__title"><?php esc_html_e( 'Composizione della Giuria', 'gugpiemonte' ); ?></h1>
	</header>

	<div class="gug-section">
		<header class="gug-giuria__print-header">
			<p class="gug-giuria__org"><?php esc_html_e( 'FEDERAZIONE ITALIANA NUOTO', 'gugpiemonte' ); ?><br><?php esc_html_e( "Gruppo Ufficiali Gara Piemonte e Valle d'Aosta", 'gugpiemonte' ); ?></p>
			<p class="gug-giuria__doc-title"><?php esc_html_e( 'Composizione della Giuria', 'gugpiemonte' ); ?></p>
		</header>

		<div class="gug-giuria__layout">
			<section class="gug-giuria__info">
				<h2 class="gug-heading gug-heading--sm"><?php echo $gug_is_pn ? esc_html__( 'Dati della partita', 'gugpiemonte' ) : esc_html__( 'Manifestazione', 'gugpiemonte' ); ?></h2>
				<dl class="gug-datalist">
					<div class="gug-datalist__row">
						<dt><?php echo $gug_is_pn ? esc_html__( 'Nome Partita', 'gugpiemonte' ) : esc_html__( 'Nome manifestazione', 'gugpiemonte' ); ?></dt>
						<dd><?php echo esc_html( $gug_title ); ?></dd>
					</div>
					<?php foreach ( $gug_info as $gug_label => $gug_key ) : ?>
						<div class="gug-datalist__row">
							<dt><?php echo esc_html( $gug_label ); ?></dt>
							<dd><?php echo esc_html( (string) ( $gug_details[ $gug_key ] ?? '' ) ); ?></dd>
						</div>
					<?php endforeach; ?>
				</dl>
				<a class="gug-print-link" href="javascript:window.print()"><?php esc_html_e( 'Stampa la designazione', 'gugpiemonte' ); ?></a>
			</section>

			<?php if ( ! empty( $gug_jury ) ) : ?>
				<section class="gug-giuria__crew">
					<h2 class="gug-heading gug-heading--sm"><?php esc_html_e( 'Convocati', 'gugpiemonte' ); ?></h2>
					<dl class="gug-datalist">
						<div class="gug-datalist__row gug-datalist__row--head">
							<dt><?php esc_html_e( 'Mansioni', 'gugpiemonte' ); ?></dt>
							<dd><?php esc_html_e( 'Convocati', 'gugpiemonte' ); ?></dd>
						</div>
						<?php
						foreach ( $gug_jury as $gug_role ) :
							$gug_names = array();
							foreach ( (array) ( $gug_role['convocati'] ?? array() ) as $gug_person ) {
								$gug_names[] = is_object( $gug_person ) ? $gug_person->post_title : get_the_title( $gug_person );
							}
							sort( $gug_names );
							?>
							<div class="gug-datalist__row">
								<dt><?php echo esc_html( (string) ( $gug_role[ 'mansioni_' . $gug_sector ] ?? '' ) ); ?></dt>
								<dd>
									<ul class="gug-datalist__names">
										<?php foreach ( $gug_names as $gug_name ) : ?>
											<li><?php echo esc_html( $gug_name ); ?></li>
										<?php endforeach; ?>
									</ul>
								</dd>
							</div>
						<?php endforeach; ?>
					</dl>
				</section>
			<?php endif; ?>
		</div>
	</div>
</article>
