<?php
if (!defined('ABSPATH')) exit;

$kicker = $kicker ?? '';
$title  = $title  ?? '';
$query  = $query  ?? ['context' => 'frontpage_top'];

/**
 * TODO: Hier später deine echten Regeln rein.
 * Für jetzt: nimm einfach die neuesten Posts, damit du überhaupt was siehst.
 */
$args = [
  'post_type'      => 'post',
  'posts_per_page' => 8,
  'post_status'    => 'publish',
];

// Beispiel: je nach context andere Query (später kannst du Tags/Kategorien nutzen)
if (($query['context'] ?? '') === 'frontpage_middle') {
  $args['posts_per_page'] = 8;
}
if (($query['context'] ?? '') === 'frontpage_bottom') {
  $args['posts_per_page'] = 8;
}

$q = new WP_Query($args);
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
                  <div class="card__media">
                    <?php if (has_post_thumbnail()): ?>
                      <?php the_post_thumbnail('medium_large', ['class' => 'card__imgtag', 'loading'=>'lazy', 'decoding'=>'async']); ?>
                    <?php else: ?>
                      <div class="card__img"></div>
                    <?php endif; ?>

                    <div class="card__meta">
                      <?php echo esc_html(get_the_date('M Y')); ?>
                    </div>
                  </div>

                  <h3 class="card__title"><?php the_title(); ?></h3>
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
