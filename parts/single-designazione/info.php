<?php
global $post;
$dettagli_designazione = get_field('dettagli');
$settore = wp_get_post_terms($post->ID, 'settori');
$dettagli = array(
    'Data' => 'data',
    'Città' => 'citta',
    'Piscina' => 'piscina',
    'Orario di inizio' => 'orario',
);
$info_nome = 'Nome Manifestazione';
switch ($settore[0]->slug):
    case 'nu':
    case 'sa':
        $dettagli += ["Cronometraggio" => 'cronometraggio'];
        break;
    case 'pn':
        $info_nome = 'Nome Partita:';
        $more = array(
            'Serie / Categoria' => 'serie-categoria',
        );
        $dettagli = array_merge($more, $dettagli);
        break;
endswitch;
?>
<div class="sd-info-wrapper noprint">
    <div class="wp-block-columns">
        <div class="wp-block-column label">
            <strong><?php echo $info_nome; ?></strong>
        </div>
        <div class="wp-block-column value">
            <?php the_title(); ?>
        </div>
    </div>
    <?php
    foreach ($dettagli as $key => $value) : ?>
        <div class="wp-block-columns">
            <div class="wp-block-column label">
                <strong><?php echo $key; ?>:</strong>
            </div>
            <div class="wp-block-column value">
                <?php echo $dettagli_designazione[$value]; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Versione stampa -->
<table class="sd-info-wrapper only-print">
    <tr class="row">
        <td class="column label">
            <strong><?php echo $info_nome; ?></strong>
        </td>
        <td class="column value">
            <?php the_title(); ?>
        </td>
    </tr>
    <?php
    foreach ($dettagli as $key => $value) : ?>
        <tr class="row">
            <td class="column label">
                <strong><?php echo $key; ?>:</strong>
            </td>
            <td class="column value">
                <?php echo $dettagli_designazione[$value]; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>