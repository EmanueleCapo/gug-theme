<?php
add_filter('body_class', function ($classes) {
    global $post;

    if (isset($post)) {
        $classes[] =  $post->post_name;
    }
    return $classes;
});
