<?php
if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title'     => 'Caricamento Foto/Loghi',
        'menu_title'    => 'Caricamento Foto/Loghi',
        'menu_slug'     => 'caricamento-foto-loghi',
        'capability'    => 'edit_posts',
        'redirect'        => false
    ));

    /*acf_add_options_sub_page(array(
		'page_title' 	=> 'Sezione Artisti',
		'menu_title'	=> 'Artisti',
		'parent_slug'	=> 'theme-general-settings',
	));*/
}