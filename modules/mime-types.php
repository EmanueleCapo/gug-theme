<?php
add_filter('upload_mimes', function ($file_types) {
    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $new_filetypes['webp'] = 'image/webp';

    $file_types = array_merge($file_types, $new_filetypes);
    return $file_types;
}, 999999999);
