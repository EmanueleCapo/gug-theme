<?php
add_action('restrict_manage_posts', function () {
    global $typenow;
    $post_types = array('designazioni', 'ufficiali-gara', 'calendari'); 
    $taxonomy  = 'settori'; 
    foreach ($post_types as $post_type) :
        if ($typenow == $post_type) :
            $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
            $info_taxonomy = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' => sprintf(__('Tutti i %s', 'textdomain'), $info_taxonomy->label),
                'taxonomy'        => $taxonomy,
                'name'            => $taxonomy,
                'orderby'         => 'name',
                'selected'        => $selected,
                'show_count'      => false,
                'hide_empty'      => true,
            ));
        endif;
    endforeach;
});

add_filter('parse_query', function ($query) {
    global $pagenow;
    $post_types = array('designazioni', 'ufficiali-gara', 'calendari');
    $taxonomy  = 'settori';
    $q_vars    = &$query->query_vars;
    foreach ($post_types as $post_type) :
        if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) :
            $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
            $q_vars[$taxonomy] = $term->slug;
        endif;
    endforeach;
});

add_action('restrict_manage_posts', function () {
    global $typenow;
    $post_types = array('designazioni',  'calendari'); 
    $taxonomy  = 'stagioni'; 
    foreach ($post_types as $post_type) :
        if ($typenow == $post_type) :
            $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
            $info_taxonomy = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' => sprintf(__('Tutte le %s', 'textdomain'), $info_taxonomy->label),
                'taxonomy'        => $taxonomy,
                'name'            => $taxonomy,
                'orderby'         => 'name',
                'selected'        => $selected,
                'show_count'      => false,
                'hide_empty'      => true,
            ));
        endif;
    endforeach;
});

add_filter('parse_query', function ($query) {
    global $pagenow;
    $post_types = array('designazioni', 'calendari');
    $taxonomy  = 'stagioni';
    $q_vars    = &$query->query_vars;
    foreach ($post_types as $post_type) :
        if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) :
            $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
            $q_vars[$taxonomy] = $term->slug;
        endif;
    endforeach;
});