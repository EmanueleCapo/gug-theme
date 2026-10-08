<?php
/*
Gestito con Pagina Calendari
*/
add_shortcode('listing-calendari', function ($param) {
    $html = '';
    $settore_slug = $param["settore"];
    $stagione = $param["stagione"];
    $args = array(
        'post_type' => 'calendari',
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'settori',
                'field'    => 'slug',
                'terms' => $settore_slug
            ),
            array(
                'taxonomy' => 'stagioni',
                'field'    => 'slug',
                'terms' => $stagione
            )
        )
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) :
        while ($query->have_posts()) :
            $query->the_post();
            $html .= '<h2 class="stagione-title blu-title">Stagione ' . $stagione . '</h2>';
            $html .= get_the_content();
        endwhile;
    else :
        $html .= '<div class="no-man-wrapper">';
        $html .= '<span class="no-results">Nessun calendario</span>';
        $html .= '</div>';
    endif;
    wp_reset_query();
    return $html;
});
