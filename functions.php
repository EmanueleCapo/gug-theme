<?php
/*Questo file è parte di gugpiemonte, blocksy child theme.

Tutte le funzioni di questo file saranno caricate prima delle funzioni del tema genitore.
Per saperne di più https://codex.wordpress.org/Child_Themes.

Nota: questa funzione carica prima il foglio di stile genitore, poi il foglio di stile figlio
(non toccare se non sai cosa stai facendo)
*/

if (!function_exists('suffice_child_enqueue_child_styles')) {
	function gugpiemonte_enqueue_child_styles()
	{
		define("PARENT_THEME_VERSION", wp_get_theme()->parent()->get('Version'));
		define("GUG_VERSION", filemtime(get_stylesheet_directory() . '/css/style.min.css'));

	    // loading Blocksy style
		wp_register_style('parent-style', get_template_directory_uri() . '/style.css', false, PARENT_THEME_VERSION, 'all');
		wp_enqueue_style('parent-style');

		// loading letsfun style
		wp_register_style('gug', get_stylesheet_directory_uri() . '/css/style.min.css', [], GUG_VERSION, 'all');
		wp_enqueue_style('gug');
		
		// loading fontello style
		wp_register_style('fontello', get_stylesheet_directory_uri() . '/fontello/css/fontello.css');
		wp_enqueue_style('fontello');

		wp_register_style('owlcarousel-css', get_stylesheet_directory_uri() . '/libs/owl-carousel2/assets/owl.carousel.min.css');
		wp_enqueue_style('owlcarousel-css');

		wp_register_script('owlcarousel-js', get_stylesheet_directory_uri() . '/libs/owl-carousel2/owl.carousel.min.js', array('jquery'), false, true);
		wp_enqueue_script('owlcarousel-js');

		wp_register_script('main', get_stylesheet_directory_uri() . '/js/main.js', array('jquery'), false, true);
		wp_enqueue_script('main');
	}
}
add_action('wp_enqueue_scripts', 'gugpiemonte_enqueue_child_styles');

/*Scrivi qui le tue funzioni */
$lib_dir = dirname(__FILE__) . '/modules/';
if (is_readable($lib_dir)) {
	foreach (glob($lib_dir . "*.php", GLOB_NOSORT) as $file) {
		if (file_exists($file)) {
			require($file);
		}
	}
}
