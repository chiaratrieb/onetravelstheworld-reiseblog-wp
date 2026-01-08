<?php
if (!defined('ABSPATH')) exit;

$post_id = (int) ($args['post_id'] ?? get_the_ID());

$light_title = (string) get_post_meta($post_id, '_otw_country_light_title', true);
$light_html  = (string) get_post_meta($post_id, '_otw_country_light_html', true);

$beige_title = (string) get_post_meta($post_id, '_otw_country_beige_title', true);
$beige_html  = (string) get_post_meta($post_id, '_otw_country_beige_html', true);

// Falls Titel leer: lieber nichts ausgeben als leere Headline
$has_light = ($light_title || trim(strip_tags($light_html)));
$has_beige = ($beige_title || trim(strip_tags($beige_html)));

if (!$has_light && !$has_beige) return;
?>

<?php if ($has_light): ?>
<section class="country-info-section country-info-section--light">
  <div class="container">
    <?php if ($light_title): ?>
      <h2 class="country-info-section__title"><?php echo esc_html($light_title); ?></h2>
    <?php endif; ?>

    <div class="country-info-section__content wysiwyg">
      <?php echo $light_html; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($has_beige): ?>
<section class="country-info-section country-info-section--beige">
  <div class="container">
    <?php if ($beige_title): ?>
      <h2 class="country-info-section__title"><?php echo esc_html($beige_title); ?></h2>
    <?php endif; ?>

    <div class="country-info-section__content wysiwyg">
      <?php echo $beige_html; ?>
    </div>
  </div>
</section>
<?php endif; ?>
