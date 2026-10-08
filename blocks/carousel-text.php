<?php

/**
 * Carousel Text Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'carousel-text-' . $block['id'];
if (!empty($block['anchor'])) {
  $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'carousel-text';
if (!empty($block['className'])) {
  $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
  $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$slides = get_field('text_slide');
$html = '';
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
  <div class="owl-carousel owl-theme carousel-text__container">
    <?php foreach ($slides as $slide) :
      $html = '<div class="item">';
      $html .= $slide["text"];
      $html .= '</div>';
      echo $html;
    endforeach;
    ?>
  </div>
</div>