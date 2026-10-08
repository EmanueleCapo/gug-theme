<?php
global $post;
$dettagli_giuria = get_field('giuria');
$note = get_field('note');
$settore = wp_get_post_terms($post->ID, 'settori');

if ($dettagli_giuria) : ?>
    <div class="giuria-container noprint">
        <div class="wp-block-columns head">
            <div class="wp-block-column mansioni">
                <strong>Mansioni</strong>
            </div>
            <div class="wp-block-column convocati">
                <strong>Convocati</strong>
            </div>
        </div>
        <?php foreach ($dettagli_giuria as $dettaglio) :
            $mansione = $dettaglio['mansioni_' . $settore[0]->slug];
            $rowspan = count($dettaglio["convocati"]);
            $convocati = array();
            foreach ($dettaglio["convocati"] as $convocato) :
                $convocati[] .= $convocato->post_title;
            endforeach;
            sort($convocati);
        ?>
            <div class="wp-block-columns">
                <div class="wp-block-column label">
                    <strong><?php echo $mansione; ?>:</strong>
                </div>
                <div class="wp-block-column value <?php echo ($settore[0]->slug == 'pn' ? 'pn' : ''); ?>">
                    <?php
                    foreach ($convocati as $convocato) :
                    ?>
                        <div class="convocato">
                            <?php
                            echo $convocato;
                            ?>
                        </div>
                    <?php
                    endforeach;
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Versione stampa -->
    <table class="giuria-container only-print">
        <tr class="row head">
            <td class="column mansioni">
                <strong>Mansioni</strong>
            </td>
            <td class="column convocati">
                <strong>Convocati</strong>
            </td>
        </tr>
        <?php foreach ($dettagli_giuria as $dettaglio) :
            $mansione = $dettaglio['mansioni_' . $settore[0]->slug];
            $rowspan = count($dettaglio["convocati"]);
            $convocati = array();
            foreach ($dettaglio["convocati"] as $convocato) :
                $convocati[] .= $convocato->post_title;
            endforeach;
            sort($convocati); ?>
            <tr class="row">
                <td class="column label" rowspan="<?php echo $rowspan; ?>">
                    <strong><?php echo $mansione; ?>:</strong>
                </td>
                <td class="column value <?php echo ($settore[0]->slug == 'pn' ? 'pn' : ''); ?>">
                    <?php
                    echo ($convocati[0]);
                    ?>
                </td>
            </tr>
            <?php
            for ($i = 1; $i < $rowspan; $i++) :
            ?>
                <tr class="row">
                    <td class="column value <?php echo ($settore[0]->slug == 'pn' ? 'pn' : ''); ?>">
                        <?php
                            echo $convocati[$i];
                        ?>
                    </td>
                </tr>
            <?php endfor; ?>
        <?php endforeach; ?>
    </table>
<?php
endif;
