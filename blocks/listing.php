<?php

/**
 * Listing Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'listing-' . $block['id'];
if (!empty($block['anchor'])) {
  $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'listing-block';
if (!empty($block['className'])) {
  $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
  $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$documents = get_field('listing');
$html = '';
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
  <ul class="listing__container">
    <?php foreach ($documents as $document) :
      $html = '<li>';
      if (!empty($document["file_link"])) :
        $link = $document["file_link"];
      elseif (!empty($document["external_link"])) :
        $link = $document["external_link"];
      else :
        $link = '';
        $class = '';
      endif;
      if (!empty($link)) :
        $html .= '<a class="icon-' . $document["extension"]  . '" href="' . $link . '" target="_blank">' . $document["title"] . '</a>';
      else :
        $html .= $document["title"];
      endif;

      /*if (!empty($link) && !empty($class)) :
        $html .= '<a class="icon-'.$document["extension"]  . '" href="' . $link . '" target="_blank">' . $document["title"] . '</a>';
      else :
        $html .= '<h4 class="listing-title">' . $document["title"] . '</h4>';
      endif;*/
      //$html .= '<p class="listing-subtitle">' . $document["subtitle"] . '</p>';
      $html .= '</li>';
      echo $html;
    endforeach;
    ?>
  </ul>
</div>