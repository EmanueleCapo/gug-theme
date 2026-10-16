<?php
/**
 * Tema figlio gugpiemonte (Blocksy).
 *
 * Tutto il codice del tema sta nei moduli in /modules, caricati qui in automatico:
 * asset e font in theme-assets.php, opzioni delle pagine in page-options.php.
 *
 * @package gugpiemonte
 */

defined( 'ABSPATH' ) || exit;

foreach ( glob( __DIR__ . '/modules/*.php' ) as $gug_module ) {
	require $gug_module;
}
