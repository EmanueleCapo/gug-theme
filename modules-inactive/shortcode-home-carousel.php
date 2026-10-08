<?php
/*
Gestito con Plugin Essenzial Block.
Se dovesse servire il codice è pronto ma bisogna rivedere il mobile
*/
add_shortcode('home-carousel', function () {

    $rows = get_fields('option');
    //var_show($rows);
    $html = '';

    if ($rows) {
        $html .= '<div class="ct-container custom-header-block-text responsiveClass breakpoint">';
        $html .= '<div class="owl-carousel owl-theme home-carousel">';
        foreach ($rows["homephoto"] as $row) :
            $html .= '<div class="item">';
            if (!empty($row["foto"])) :
                $html .= '<img src ="' . $row["foto"] . '">';
            endif;
            $html .= '</div>';
        endforeach;
        $html .= '</div>';
        $html .= '</div>';
    }
    return $html;
});
