<?php
if (!defined('ABSPATH')) exit;

$kicker = $kicker ?? '';
$title  = $title  ?? '';
$query  = $query  ?? ['context' => 'frontpage_top'];

$context      = $query['context'] ?? 'frontpage_top';
$slot         = isset($query['slot']) ? sanitize_key($query['slot']) : '';
$country_id   = isset($query['country_id']) ? (int) $query['country_id'] : 0;
$continent_id = isset($query['continent_id']) ? (int) $query['continent_id'] : 0;


/**
 * =====================================================
 * Carousel Configs (hier skalierst du später!)
 * =====================================================
 * Neue Landingpage/Slots = nur neuen Key hinzufügen.
 */
$CAROUSEL_CONFIGS = [
  // Frontpage Beispiele
  'frontpage_carousel' => [
    'posts_per_page' => 8,
  ],

  // Country: "Meine Top Reisetipps"
  'country_top' => [
    'posts_per_page' => 8,
    'tag_slug'       => 'top-reisetipps',
    'use_country'    => true,
    'fallback_tag_only' => true,
  ],
];

/**
 * Base Args (Default)
 */
$args = [
  'post_type'      => 'post',
  'posts_per_page' => 8,
  'post_status'    => 'publish',
];

/**
 * Config anwenden (wenn vorhanden)
 */
$config = $CAROUSEL_CONFIGS[$context] ?? [];

// Sonderfall: Frontpage-Carousels unterscheiden sich nur über Tag (slot = tag-slug)
if ($context === 'frontpage_carousel' && $slot) {
  $config['tag_slug'] = $slot;
}


if (isset($config['posts_per_page'])) {
  $args['posts_per_page'] = (int) $config['posts_per_page'];
}

/**
 * Tag Filter (optional)
 */
$has_tag_filter = !empty($config['tag_slug']);
if ($has_tag_filter) {
  $args['tax_query'] = [
    'relation' => 'AND',
    [
      'taxonomy' => 'post_tag',
      'field'    => 'slug',
      'terms'    => [(string) $config['tag_slug']],
    ],
  ];
}

/**
 * Country Filter (optional)
 */
$wants_country = !empty($config['use_country']);
if ($wants_country && $country_id) {
  if (!isset($args['tax_query'])) {
    $args['tax_query'] = ['relation' => 'AND'];
  }
  $args['tax_query'][] = [
    'taxonomy' => 'country',
    'field'    => 'term_id',
    'terms'    => [$country_id],
  ];
}

/**
 * Optional: Continent Filter (falls du es später brauchst)
 * Aktivieren, wenn du in configs z.B. 'use_continent' => true setzt.
 */
$wants_continent = !empty($config['use_continent']);
if ($wants_continent && $continent_id) {
  if (!isset($args['tax_query'])) {
    $args['tax_query'] = ['relation' => 'AND'];
  }
  $args['tax_query'][] = [
    'taxonomy' => 'continent',
    'field'    => 'term_id',
    'terms'    => [$continent_id],
  ];
}

/**
 * Query ausführen
 */
$q = new WP_Query($args);

/**
 * Fallback: Wenn (Tag + Country) keine Treffer liefert → nur Tag global
 */
if (
  !$q->have_posts()
  && !empty($config['fallback_tag_only'])
  && $has_tag_filter
  && $wants_country
  && $country_id
) {
  wp_reset_postdata();

  $args_fallback = $args;
  $args_fallback['tax_query'] = [
    'relation' => 'AND',
    [
      'taxonomy' => 'post_tag',
      'field'    => 'slug',
      'terms'    => [(string) $config['tag_slug']],
    ],
  ];

  $q = new WP_Query($args_fallback);
}
?>

<section class="section carousel-section" data-carousel-section>
  <div class="container">
    <?php if ($kicker): ?>
      <div class="section__kicker" data-carousel-kicker><?php echo esc_html($kicker); ?></div>
    <?php endif; ?>

    <?php if ($title): ?>
      <h2 class="section__title" data-carousel-title><?php echo wp_kses_post($title); ?></h2>
    <?php endif; ?>

    <div class="carousel" data-carousel>
      <button class="carousel__btn carousel__btn--left" data-carousel-prev aria-label="Zurück">
        <span class="arrow arrow--left"></span>
      </button>

      <div class="carousel__viewport">
        <div class="carousel__track" data-carousel-track>
          <?php if ($q->have_posts()): ?>
            <?php while ($q->have_posts()): $q->the_post(); ?>
            <article class="card card--carousel">
  <a class="card__link" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">

    <div class="card__paper">
      <div class="card__inner">

        <div class="card__img">
          <?php if (has_post_thumbnail()): ?>
            <?php the_post_thumbnail('medium_large', ['loading'=>'lazy', 'decoding'=>'async']); ?>
          <?php else: ?>
            <img
              src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/placeholder.jpg"
              alt=""
              loading="lazy"
              decoding="async"
            >
          <?php endif; ?>
        </div>

        <h3 class="card__title"><?php the_title(); ?></h3>

        <div class="card__divider" aria-hidden="true"></div>

        <div class="card__cta">LEARN MORE</div>

      </div>
    </div>

  </a>
</article>
            <?php endwhile; wp_reset_postdata(); ?>
          <?php else: ?>
            <p style="opacity:.7">Noch keine Beiträge vorhanden.</p>
          <?php endif; ?>
        </div>
      </div>

      <button class="carousel__btn carousel__btn--right" data-carousel-next aria-label="Weiter">
        <span class="arrow arrow--right"></span>
      </button>
    </div>
  </div>
</section>
