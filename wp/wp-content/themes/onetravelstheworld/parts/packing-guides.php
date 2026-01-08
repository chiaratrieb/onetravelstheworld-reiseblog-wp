<?php
if (!defined('ABSPATH')) exit;

$theme_uri = get_template_directory_uri();

/* Content (später problemlos WP-Admin-fähig) */
$kicker        = 'PACKING GUIDES';
$title_main   = 'Packing';
$title_script = 'Guides';
$text          = "Not sure what to pack for your next trip? I’ve put together packing guides and outfit inspiration for everything from tropical vacations to multi-day treks!";
$btn_label     = 'READ MORE';
$btn_url       = home_url('/packing-guides/');

/* Bilder (vorerst Platzhalter) */
$image_main = $theme_uri . '/assets/images/Thailand_Featured.webp';
$image_side = $theme_uri . '/assets/images/Thailand_Featured.webp';
?>

<section class="promo-split">
  <div class="container promo-split__inner">

    <!-- LEFT: OVERLAPPING CARD -->
    <div class="promo-split__card">
      <div class="promo-split__card-inner">

        <!-- optionaler Stempel / Ornament -->
        <div class="promo-split__stamp" aria-hidden="true"></div>

        <div class="promo-split__kicker"><?php echo esc_html($kicker); ?></div>

        <h2 class="promo-split__title">
          <?php echo esc_html($title_main); ?>
          <span class="promo-split__title-script"><?php echo esc_html($title_script); ?></span>
        </h2>

        <p class="promo-split__text">
          <?php echo esc_html($text); ?>
        </p>

        <a class="promo-split__btn" href="<?php echo esc_url($btn_url); ?>">
          <?php echo esc_html($btn_label); ?>
        </a>

      </div>
    </div>

    <!-- RIGHT: IMAGE STAGE -->
    <div class="promo-split__stage">
      <div class="promo-split__frame">

        <div class="promo-split__images">

          <!-- MAIN IMAGE -->
          <div class="promo-split__img-main">
            <img src="<?php echo esc_url($image_main); ?>" alt="" loading="lazy" decoding="async">
          </div>

          <!-- SIDE IMAGE STRIP -->
          <div class="promo-split__img-side">
            <img src="<?php echo esc_url($image_side); ?>" alt="" loading="lazy" decoding="async">
          </div>

        </div>

        <!-- Slider dots -->
        <div class="promo-split__dots" aria-hidden="true">
          <span class="promo-split__dot is-active"></span>
          <span class="promo-split__dot"></span>
          <span class="promo-split__dot"></span>
        </div>

      </div>
    </div>

  </div>
</section>
