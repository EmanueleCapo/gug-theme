<?php
add_action('acf/init', function () {
    // Check function exists.
    if (function_exists('acf_register_block_type')) {
        /*acf_register_block_type(array(
            'name'              => 'carousel-text',
            'title'             => __('Carousel Text'),
            'description'       => __('A custom carousel text block.'),
            'render_template'   => 'blocks/carousel-text.php',
            'category'          => 'formatting',
            'icon'              => 'text',
            'keywords'          => array('carousel', 'text'),
        ));*/

        acf_register_block_type(array(
            'name'              => 'listing',
            'title'             => __('Listing'),
            'description'       => __('A Custom block for Listing.'),
            'render_template'   => 'blocks/listing.php',
            'category'          => 'formatting',
            'icon'              => 'text',
            'keywords'          => array('listing', 'text'),
        ));
    }
});
