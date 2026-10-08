<?php
add_action('init', function () {

    /*----- SETTORI -----*/
    $labels = array(
        'name' => __('Settori'),
        'singular_name' => __('Settore'),
        'search_items' => __('Cerca Settore'),
        'all_items' => __('Tutte i Settori'),
        'parent_item' => __('Settore Padre'),
        'parent_item_colon' => __('Settore Padre:'),
        'edit_item' => __('Modifica Settore'),
        'update_item' => __('Aggiorna Settore'),
        'add_new_item' => __('Aggiungi nuovo Settore'),
        'new_item_name' => __('Nuovo Settore'),
        'menu_name' => __('Settori')
    );

    $args = array(
        'hierarchical' => true,
        'show_admin_column' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'settori'
        )
    );

    register_taxonomy('settori', array('designazioni', 'ufficiali-gara', 'post', 'page'), $args); // 'calendari', (se serve si rimette)

    /*----- STAGIONE -----*/
    $labels = array(
        'name' => __('Stagioni'),
        'singular_name' => __('Stagione'),
        'search_items' => __('Cerca Stagione'),
        'all_items' => __('Tutte le Stagioni'),
        'parent_item' => __('Stagione Padre'),
        'parent_item_colon' => __('Stagione Padre:'),
        'edit_item' => __('Modifica Stagione'),
        'update_item' => __('Aggiorna Stagione'),
        'add_new_item' => __('Aggiungi nuova Stagione'),
        'new_item_name' => __('Nuova Stagione'),
        'menu_name' => __('Stagioni')
    );

    $args = array(
        'hierarchical' => true,
        'show_admin_column' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'stagioni'
        )
    );

    register_taxonomy('stagioni', array('designazioni', 'post'), $args); // 'calendari', (se serve si rimette)
});
