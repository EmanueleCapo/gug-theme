<?php
if (!wp_next_scheduled('expire_posts')) {
    if (is_post_type('post')) :
        wp_schedule_event(time(), 'hourly', 'expire_posts'); // this can be hourly, twicedaily, or daily
    endif;
}

add_action('expire_posts', function () {
    $today = date('Ymd');
    $args = array(
        'post_type' => array('post'), // post types you want to check
        'posts_per_page' => -1
    );
    $posts = get_posts($args);
    foreach ($posts as $p) {
        $expiredate = get_field('valid_end_date', $p->ID, false, false); // get the raw date from the db
        if ($expiredate) {
            if ($expiredate < $today) {
                $postdata = array(
                    'ID' => $p->ID,
                    'post_status' => 'draft'
                );
                wp_update_post($postdata);
            }
        }
    }
});

function is_post_type($type)
{
    global $wp_query;
    if ($type == get_post_type($wp_query->post->ID))
        return true;
    return false;
}
