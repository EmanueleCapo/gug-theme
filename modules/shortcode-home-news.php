<?php
add_shortcode('home-news', function ($param) {
    $html = '';
    $limit = $param["limit"];
    $stagione = $param["stagione"];
    $args = array(
        'post_type' => 'post',
        'order'   => 'DESC',
        'posts_per_page' => $limit,
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'stagioni',
                'field'    => 'slug',
                'terms' => $stagione
            )
        )
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) :
        $html .= '<ul class="wp-block-latest-posts__list is-grid columns-2 wp-block-latest-posts">';
        while ($query->have_posts()) :
            $query->the_post();
            $settore = get_the_terms(get_the_ID(), 'settori');
            if (!empty($settore[0]->slug)) :
                $settore = $settore[0]->slug;
            else :
                $settore = 'general';
            endif;
            $html .= '<li><a class="wp-block-latest-posts__post-title latest-post ' . $settore . '" href="' . get_the_permalink() . '">' . get_the_title() . '</a></li>';
        endwhile;
        $html .= '</ul>';
    else :
        $html .= '<div class="no-man-wrapper">';
        $html .= '<span class="no-results">Nessuna News</span>';
        $html .= '</div>';
    endif;
    wp_reset_query();
    return $html;
});
