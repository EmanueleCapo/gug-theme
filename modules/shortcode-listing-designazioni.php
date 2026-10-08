<?php
add_shortcode('listing-designazioni', function ($param) {
    //global $post;
    $html = '';
    $settore_slug = $param["settore"];
    $monday = date('Ymd', strtotime('monday this week'));
    $sunday = date('Ymd', strtotime('sunday this week'));
    $args = array(
        'post_type' => 'designazioni',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => 'settori',
                'field'    => 'slug',
                'terms' => $settore_slug
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
        //if (!wp_is_mobile()) :
        // Versione Desktop
        $html .= '<table class="listing-designazioni only-desktop">';
        $html .= '<tr class="head-row">';
        $html .= '<td class="head col-data">Data</td>';
        if ($settore_slug == 'pn') :
            $html .= '<td class="head col-serie">Serie/Categoria</td>';
            $html .= '<td class="head col-nome">Nome Partita</td>';
        else :
            $html .= '<td class="head col-nome">Nome Manifestazione</td>';
        endif;
        $html .= '<td class="head col-citta">Città</td>';
        $html .= '<td class="head col-piscina">Piscina</td>';
        $html .= '<td class="head col-giuria">Giuria</td>';
        if ($settore_slug <> 'pn') :
            $html .= '<td class="head col-regolamento">Regolamento</td>';
        endif;
        $html .= '</tr>';
        while ($query->have_posts()) :
            $html .= '  <tr class="row">';
            $query->the_post();
            $data = get_field('dettagli_data', get_the_ID());
            $serie_categoria = get_field('dettagli_serie-categoria', get_the_ID());
            $citta = get_field('dettagli_citta', get_the_ID());
            $piscina = get_field('dettagli_piscina', get_the_ID());
            $giuria = get_field('giuria', get_the_ID());
            $regolamento = get_field('dettagli_regolamento_manifestazione', get_the_ID());
            $html .= '<td class="col-data">' . $data . '</td>';
            if ($settore_slug == 'pn') :
                $html .= '<td class="col-serie">' . $serie_categoria . '</td>';
            endif;
            $html .= '<td class="col-nome">' . get_the_title() . '</td>';
            $html .= '<td class="col-citta">' . $citta . '</td>';
            $html .= '<td class="col-piscina">' . $piscina . '</td>';
            $html .= '<td class="col-giuria">';
            if (!empty($giuria)) :
                $html .= '<a href="' . get_the_permalink() . '" target=_blank><span class="icon icon-users"></a>';
            endif;
            $html .= '</td>';
            if ($settore_slug <> 'pn') :
                if (!empty($regolamento)) :
                    $html .= '<td class="col-regolamento"><a href="' . $regolamento . '" target=_blank><span class="icon icon-book"></a></td>';
                else :
                    $html .= '<td class="col-regolamento"></td>';
                endif;
            endif;
            $html .= '</tr>';
        endwhile;
        $html .= '</table>';
        //else :
        // Versione Tablet-Cell
        $html .= '<div class="listing-designazioni mobile only-cell">';
        while ($query->have_posts()) :
            $query->the_post();
            $data = get_field('dettagli_data', get_the_ID());
            $serie_categoria = get_field('dettagli_serie-categoria', get_the_ID());
            $citta = get_field('dettagli_citta', get_the_ID());
            $piscina = get_field('dettagli_piscina', get_the_ID());
            $regolamento = get_field('dettagli_regolamento_manifestazione', get_the_ID());
            $giuria = get_field('giuria', get_the_ID());
            // Data / Serie-Categoria
            if ($settore_slug == 'pn') :
                $html .= '<div class="first-row-pn">';
                $html .= '<div class="first-column">';
                $html .= '<div class="head col-data">Data</div>';
                $html .= '<div class="col-data">' . $data . '</div>';
                $html .= '</div>';
                $html .= '<div class="second-column">';
                $html .= '<div class="head col-serie">Serie/Categoria</div>';
                $html .= '<div class="col-serie">' . $serie_categoria . '</div>';
                $html .= '</div>';
            else :
                $html .= '<div class="first-row">';
                $html .= '<div class="head col-data">Data</div>';
                $html .= '<div class="col-data">' . $data . '</div>';
            endif;
            $html .= '</div>';
            // Nome
            $html .= '<div class="second-row">';
            if ($settore_slug == 'pn') :
                $html .= '<div class="head col-nome">Nome Partita</div>';
            else :
                $html .= '<div class="head col-nome">Nome Manifestazione</div>';
            endif;
            $html .= '<div class="col-nome">' . get_the_title() . '</div>';
            $html .= '</div>';
            // Città / Piscina
            $html .= '<div class="third-row">';
            $html .= '<div class="first-column">';
            $html .= '<div class="head col-citta">Città</div>';
            $html .= '<div class="col-citta">' . $citta . '</div>';
            $html .= '</div>';
            $html .= '<div class="second-column">';
            $html .= '<div class="head col-piscina">Piscina</div>';
            $html .= '<div class="col-piscina">' . $piscina . '</div>';
            $html .= '</div>';
            $html .= '</div>';
            // Giuria / Regolamento
            $html .= '<div class="last-row">';
            if ($settore_slug <> 'pn') :
                $html .= '<div class="first-column">';
                $html .= '<div class="head col-giuria">Giuria</div>';
                $html .= '<div class="col-giuria">';
                if (!empty($giuria)) :
                    $html .= '<a href="' . get_the_permalink() . '" target=_blank><span class="icon icon-users"></a>';
                endif;
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="second-column">';
                $html .= '<div class="head col-regolamento">Regolamento</div>';
                if (!empty($regolamento)) :
                    $html .= '<div class="col-regolamento"><a href="' . $regolamento . '" target=_blank><span class="icon icon-book"></a></div>';
                else :
                    $html .= '<div class="col-regolamento"></div>';
                endif;
                $html .= '</div>';
            else :
                $html .= '<div class="head col-giuria">Giuria</div>';
                $html .= '<div class="col-giuria"><a href="' . get_the_permalink() . '" target=_blank><span class="icon icon-users"></a></div>';
            endif;
            $html .= '</div>';
        endwhile;
        $html .= '</div>';
    //endif;
    else :
        $html .= '<div class="no-man-wrapper">';
        $html .= '<span class="no-results">Nessuna manifestazione</span>';
        $html .= '</div>';
    endif;
    wp_reset_query();
    return $html;
});
