<?php
function var_show(...$var)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}

// Redirect alla home dal Singolo Ufficiale Gara
add_action('wp', function () {
    if (is_singular('ufficiali-gara')) :
        wp_redirect(home_url());
        exit;
    endif;
});
