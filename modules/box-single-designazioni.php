<?php

add_action('blocksy:single:top', function () {
    if (is_singular('designazioni')) :
        get_template_part('parts/single-designazione/header');
    endif;
});

add_action('blocksy:single:content:top', function () {
    if (is_singular('designazioni')) :
        get_template_part('parts/single-designazione/info');
    endif;
});

add_action('blocksy:single:content:bottom', function () {
    if (is_singular('designazioni')) :
        get_template_part('parts/single-designazione/giuria');
    endif;
});

add_action('blocksy:single:bottom', function () {
    if (is_singular('designazioni')) :
        get_template_part('parts/single-designazione/print');
    endif;
});
