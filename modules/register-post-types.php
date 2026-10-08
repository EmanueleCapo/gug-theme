<?php
/* CPT Designazioni */
add_action('init', function () {
    $labels = array(
        'name' => __('Designazioni', ''),
        'singular_name' => __('Designazione'),
        'add_new' => __('Aggiungi Nuova Designazione', ''),
        'add_new_item' => __('Aggiungi Nuova Designazione'),
        'edit_item' => __('Modifica Designazione'),
        'new_item' => __('Nuova Designazione'),
        'view_item' => __('Visualizza Designazione'),
        'search_items' => __('Cerca Designazione'),
        'not_found' => __('Nessuna Designazione Trovata'),
        'not_found_in_trash' => __('Nessuna Designazione Trovata nel Cestino'),
        'parent_item_colon' => '',
        'menu_name' => 'Designazioni'
    );

    $supports = array(
        'title',
        'editor',
        'author',
        'thumbnail',
        'excerpt',
        'trackbacks',
        'custom-fields',
        'comments',
        'revisions',
        'page-attributes',
        'post-formats'
    );

    $args = array(
        'labels' => $labels,
        'supports' => $supports,
        'description' => '',
        'public' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-clipboard',
        'capability_type' => 'post',
        'hierarchical' => true,
        'taxonomies' => array(''),
        'has_archive' => false,
        'rewrite' => array(
            'slug' => '',
            'with_front' => true,
            'feeds' => true,
            'pages' => true
        ),
        'query_var' => true,
        'can_export' => true,
        'show_in_nav_menus' => true,
        '_edit_link' => 'post.php?post=%d'
    );

    register_post_type('designazioni', $args);
});

/* CPT Ufficiali Gara */
add_action('init', function () {
    $labels = array(
        'name' => __('Ufficiali Gara', ''),
        'singular_name' => __('Ufficiale Gara'),
        'add_new' => __('Aggiungi Nuovo Ufficiale Gara', ''),
        'add_new_item' => __('Aggiungi Nuovo Ufficiale Gara'),
        'edit_item' => __('Modifica Ufficiale Gara'),
        'new_item' => __('Nuovo Ufficiale Gara'),
        'view_item' => __('Visualizza Ufficiale Gara'),
        'search_items' => __('Cerca Ufficiale Gara'),
        'not_found' => __('Nessun Ufficiale Gara Trovato'),
        'not_found_in_trash' => __('Nessun Ufficiale Gara Trovato nel Cestino'),
        'parent_item_colon' => '',
        'menu_name' => 'Ufficiali Gara'
    );

    $supports = array(
        'title',
        'editor',
        'author',
        'thumbnail',
        'excerpt',
        'trackbacks',
        'custom-fields',
        'comments',
        'revisions',
        'page-attributes',
        'post-formats'
    );

    $args = array(
        'labels' => $labels,
        'supports' => $supports,
        'description' => '',
        'public' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-groups',
        'capability_type' => 'page',
        'hierarchical' => true,
        'taxonomies' => array(''),
        'has_archive' => false,
        'rewrite' => array(
            'slug' => '',
            'with_front' => true,
            'feeds' => true,
            'pages' => true
        ),
        'query_var' => true,
        'can_export' => true,
        'show_in_nav_menus' => true,
        '_edit_link' => 'post.php?post=%d'
    );

    register_post_type('ufficiali-gara', $args);
});

/* CPT Calendari */
/*
Al momento questo CPT lo disattivo perchè i calendatri sono stati uniti in una pagina con un toggle
*/
/*add_action('init', function () {
    $labels = array(
        'name' => __('Calendari', ''),
        'singular_name' => __('Calendario'),
        'add_new' => __('Aggiungi Nuovo Calendario', ''),
        'add_new_item' => __('Aggiungi Nuovo Calendario'),
        'edit_item' => __('Modifica Calendario'),
        'new_item' => __('Nuovo Calendario'),
        'view_item' => __('Visualizza Calendario'),
        'search_items' => __('Cerca Calendario'),
        'not_found' => __('Nessun Calendario Trovato'),
        'not_found_in_trash' => __('Nessun Calendario Trovato nel Cestino'),
        'parent_item_colon' => '',
        'menu_name' => 'Calendari'
    );

    $supports = array(
        'title',
        'editor',
        'author',
        'thumbnail',
        'excerpt',
        'trackbacks',
        'custom-fields',
        'comments',
        'revisions',
        'page-attributes',
        'post-formats'
    );

    $args = array(
        'labels' => $labels,
        'supports' => $supports,
        'description' => '',
        'public' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-calendar-alt',
        'capability_type' => 'page',
        'hierarchical' => true,
        'taxonomies' => array(''),
        'has_archive' => false,
        'rewrite' => array(
            'slug' => '',
            'with_front' => true,
            'feeds' => true,
            'pages' => true
        ),
        'query_var' => true,
        'can_export' => true,
        'show_in_nav_menus' => true,
        '_edit_link' => 'post.php?post=%d'
    );

    register_post_type('calendari', $args);
});*/

