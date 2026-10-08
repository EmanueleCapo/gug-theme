<?php
function add_admin_column($column_title, $post_type, $cb)
{

    // Column Header
    add_filter('manage_' . $post_type . '_posts_columns', function ($columns) use ($column_title) {
        $columns[sanitize_title($column_title)] = $column_title;
        return $columns;
    });

    // Column Content
    add_action('manage_' . $post_type . '_posts_custom_column', function ($column, $post_id) use ($column_title, $cb) {

        if (sanitize_title($column_title) === $column) {
            $cb($post_id);
        }
    }, 10, 2);
}

add_admin_column(__('Data'), 'designazioni', function ($post_id) {
    $date = date_create(get_post_meta($post_id, 'dettagli_data', true));
    echo date_format($date, 'd/m/Y');
});


add_filter('manage_posts_columns', function ($columns) {
    $admin_columns = array();
    $title = 'title';
    foreach ($columns as $key => $value) :
        $admin_columns[$key] = $value;
        if ($key == $title) :
            $admin_columns['data'] = '';   // Move date column before title column
            $admin_columns['taxonomy-stagioni'] = '';   // Move author column before title column
            $admin_columns['taxonomy-settori'] = '';   // Move author column before title column
            $admin_columns['date'] = '';   // Move tags column before title column
        endif;
    endforeach;
    return $admin_columns;
});
