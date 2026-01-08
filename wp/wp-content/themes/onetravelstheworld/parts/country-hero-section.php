<?php
if (!defined('ABSPATH')) exit;

$post_id = (int) ($args['post_id'] ?? get_the_ID());

$headline = (string) get_post_meta($post_id, '_otw_country_hero_headline', true);
$intro    = (string) get_post_meta($post_id, '_otw_country_hero_intro', true);
$image_id = (int) get_post_meta($post_id, '_otw_country_hero_image_id', true);

if (!$headline) $headline = get_the_title($post_id);

// Bild-URL: 1) Metabox Bild 2) Featured Image 3) Placeholder
$img_url = '';
if ($image_id) {
  $img_url = wp_get_attachment_image_url($image_id, 'large');
}
if (!$img_url && has_post_thumbnail($post_id)) {
  $img_url = get_the_post_thumbnail_url($post_id, 'large');
}
if (!$img_url) {
  $img_url = get_template_directory_uri() . '/assets/images/placeholder.jpg';
}
?>

<section class="country-hero-section">
  <div class="container country-hero-section__inner">

    <div class="country-hero-section__text">
      <h1 class="country-hero-section__title"><?php echo esc_html($headline); ?></h1>

      <?php if ($intro): ?>
        <div class="country-hero-section__intro">
          <?php echo wpautop(esc_html($intro)); ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="country-hero-section__media" aria-hidden="true">
      <div class="country-hero-section__circle">
        <img src="<?php echo esc_url($img_url); ?>" alt="" loading="lazy" decoding="async">
      </div>
    </div>

  </div>

  <div class="country-hero-section__divider" aria-hidden="true"></div>
</section>
