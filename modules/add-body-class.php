<?php
add_filter('body_class', function ($classes) {
    global $post;

    // Pagine del redesign: niente classe con lo slug, il vecchio CSS (style.min.css) la usa
    // per stili ormai superati (es. .designazioni-nuoto article, .regolamenti-nuoto a).
    if (isset($post) && ! (function_exists('gug_redesign_is_pattern_page') && gug_redesign_is_pattern_page((int) $post->ID))) {
        $classes[] =  $post->post_name;
    }
    return $classes;
});
