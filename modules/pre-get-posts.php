<?php
add_filter('pre_get_posts', function ($query) {
    if ($query->is_tax('settori') && $query->is_main_query()) :
        //$terms = get_terms('settori', array('fields' => 'ids'));
        $query->set('post_type', array('post'));
        $query->set('post_status', 'any');
        $anno_attuale = date("Y");
        $anno_passato = date("Y", strtotime("-1 year"));
        $taxquery = array(
            array(
                'taxonomy' => 'stagioni',
                'field'    => 'slug',
                'terms' => $anno_passato . '-' . $anno_attuale,
                'operator' => 'IN'
            )
        );
        $query->set('tax_query', $taxquery);
    endif;
    //var_show($query); die();
    return $query;
});
