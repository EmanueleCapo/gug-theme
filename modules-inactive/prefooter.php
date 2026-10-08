<?php

function get_the_content_with_formatting()
{
    $content = get_the_content(1690);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
}

add_action('blocksy:footer:before', function () {
    //echo apply_filters( 'the_content', get_post_field('post_content', 1690) );
});

        /*add_action('blocksy:footer:before', function () {
            $content_post = get_post(1690);
            $content = $content_post->post_content;
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
        ?>
            <div class="ct-container">
                <?php return $content;
                ?>
            </div><?php
                });*/