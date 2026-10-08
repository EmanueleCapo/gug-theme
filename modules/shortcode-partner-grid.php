<?php
add_shortcode('partner-grid', function () {
    $rows = get_fields('option');
    $html = '';
    $image = '';
    
    if ($rows["loghi_partner"]) {
        if (!wp_is_mobile()) :
            $html .= '<div class="ct-container partner-grid-wrapper">';
            foreach ($rows["loghi_partner"] as $row) :
                $html .= '<div class="item">';
                if (!empty($row["logo"])) :
                    $image = '<img src="' . esc_url($row["logo"]) . '" loading="eager" alt="">';
                endif;
                if (!empty($row["url"])) :
                    $html .= '<a href="' . esc_url($row["url"]) . '" target="_blank">' . $image . '</a>';
                else :
                    $html .= $image;
                endif;
                $html .= '</div>';
            endforeach;
            $html .= '</div>';
        else :
            $html .= '<div class="ct-container partner-carousel-wrapper">';
            $html .= '<div class="owl-carousel owl-theme partner-carousel">';
            foreach ($rows["loghi_partner"] as $row) :
                $html .= '<div class="item">';
                if (!empty($row["logo"])) :
                    $image = '<img src="' . esc_url($row["logo"]) . '" loading="eager" alt="">';
                endif;
                if (!empty($row["url"])) :
                    $html .= '<a href="' . $row["url"] . '" target="_blank">' . $image . '</a>';
                else :
                    $html .= $image;
                endif;
                $html .= '</div>';
            endforeach;
            $html .= '</div>';
            $html .= '</div>';
        endif;
    }
    return $html;
});
