<?php
add_shortcode('designazioni-home', function () {
    //global $post;
    $html = '';
    $monday = date('Ymd', strtotime('monday this week'));
    $sunday = date('Ymd', strtotime('sunday this week'));
    $html .= '<div class="designazioni-wrapper">';
    $settori = get_terms([
        'taxonomy' => 'settori',
        'order' => 'ASC',
        'hide_empty' => false,
    ]);
    foreach ($settori as $settore) :
        $html .= '  <div class="entries-box box-' . $settore->slug . '" data-layout="classic" data-cards="boxed">';
        $html .= '<span class="settore-name">' . $settore->name . '</span>';
        $args = array(
            'post_type' => 'designazioni',
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'settori',
                    'field'    => 'slug',
                    'terms' => $settore->slug
                )
            ),
            'meta_query' => array(
                array(
                    'key' => 'dettagli_data',
                    'value'   => array($monday, $sunday),
                    'type'    => 'numeric',
                    'compare' => 'BETWEEN',
                ),
            )
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) :
            $date_print = '';
            $title = '';
            $html .= '<a class="no-style" href="/designazioni-' . strtolower($settore->name) . '">';
            if ($settore->slug == 'pn') :
                //$html .= '<a class="no-style" href="/new-site/designazioni-' . $settore->slug . '">';
                $html .= '<span class="man-title">Tutte le partite in programma:</span>';
                $html .= '</a>';
            else :
                while ($query->have_posts()) :
                    $query->the_post();
                    $date = get_field('dettagli_data', get_the_ID());
                    $title_summary = get_field('dettagli_titolo_riepilogo', get_the_ID());
                    if ($date != $date_print) :
                        $html .= '<span class="man-date">' . $date . '</span>';
                        $date_print = $date;
                        $title = '';
                    endif;
                    if (!empty($title_summary)) :
                        if ($title_summary != $title) : // Raggruppo per Titolo se la manifestazione ha più giurie
                            $html .= '<span class="man-title">- ' . $title_summary . '</span>';
                            $title = $title_summary;
                        endif;
                    else :
                        $html .= '<span class="man-title">- ' . get_the_title() . '</span>';
                    endif;
                endwhile;
                $html .= '</a>';
            endif;
        else :
            if ($settore->slug == 'pn') :
                $html .= '<span class="no-man">Nessuna partita</span>';
            else :
                $html .= '<span class="no-man">Nessuna manifestazione</span>';
            endif;
        endif;
        //$html .= '<span class="man-date">12/12/2021</span>';
        //$html .= '<span class="man-title">- Manifestazione Regionale Assoluti</span>';
        $html .= '</div>';
        wp_reset_query();
    endforeach;
    $html .= '</div>';
    return $html;
});
