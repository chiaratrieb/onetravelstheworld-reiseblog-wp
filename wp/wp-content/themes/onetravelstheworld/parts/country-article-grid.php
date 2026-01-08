<?php
if (!defined('ABSPATH')) exit;

/**
 * Erwartete Args:
 * - page_id
 * - continent_id
 * - country_id
 */

$page_id      = (int) ($args['page_id'] ?? 0);
$continent_id = (int) ($args['continent_id'] ?? 0);
$country_id   = (int) ($args['country_id'] ?? 0);

if (!$continent_id || !$country_id) {
  return;
}

// Aktuelle Seite
$page = isset($_GET['pg']) ? max(1, (int) $_GET['pg']) : 1;

// Query
$q = new WP_Query([
  'post_type'      => 'post',
  'post_status'    => 'publish',
  'posts_per_page' => 16,
  'paged'          => $page,
  'tax_query'      => [
    'relation' => 'AND',
    [
      'taxonomy' => 'continent',
      'field'    => 'term_id',
      'terms'    => $continent_id,
    ],
    [
      'taxonomy' => 'country',
      'field'    => 'term_id',
      'terms'    => $country_id,
    ],
  ],
]);

if (!$q->have_posts()) {
  wp_reset_postdata();
  return;
}

// Country Label
$country_term  = get_term($country_id, 'country');
$country_label = ($country_term && !is_wp_error($country_term))
  ? strtoupper($country_term->name)
  : '';

// Pagination Basis
$total_pages = max(1, (int) $q->max_num_pages);
$base_url    = $page_id ? get_permalink($page_id) : get_permalink();

// Prev / Next URLs
$prev_url = ($page > 1)
  ? add_query_arg('pg', $page - 1, $base_url)
  : '';

$next_url = ($page < $total_pages)
  ? add_query_arg('pg', $page + 1, $base_url)
  : '';

// Seitenzahlen nur bei mehr als 1 Seite (optional)
$links = [];
if ($total_pages > 1) {
  $links = paginate_links([
    'base'      => add_query_arg('pg', '%#%', $base_url),
    'format'    => '',
    'current'   => $page,
    'total'     => $total_pages,
    'type'      => 'array',
    'prev_text' => '‹',
    'next_text' => '›',
  ]);
}
?>

<section class="section country-articles">
  <div class="container">

    <div class="section__kicker">REISETIPPS</div>
    <h2 class="section__title">Artikel & Reisetipps</h2>

    <div class="article-grid">
      <?php while ($q->have_posts()) : $q->the_post(); ?>
        <article class="grid-card">
          <a class="grid-card__link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">

            <div class="grid-card__paper">
              <div class="grid-card__inner">

                <div class="grid-card__img">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('medium_large', ['loading' => 'lazy', 'decoding' => 'async']); ?>
                  <?php else : ?>
                    <img
                      src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/placeholder.jpg"
                      alt=""
                      loading="lazy"
                      decoding="async"
                    >
                  <?php endif; ?>
                </div>

                <h3 class="grid-card__title"><?php the_title(); ?></h3>

                <div class="grid-card__divider" aria-hidden="true"></div>

                <div class="grid-card__cta">LEARN MORE</div>

                <?php if ($country_label) : ?>
                  <div class="grid-card__meta"><?php echo esc_html($country_label); ?></div>
                <?php endif; ?>

              </div>
            </div>

          </a>
        </article>
      <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrap">
      <nav class="pagination pagination--arrows" aria-label="Artikel Seiten">

        <?php if ($prev_url) : ?>
          <a class="page-numbers prev" href="<?php echo esc_url($prev_url); ?>" aria-label="Vorherige Seite">←</a>
        <?php endif; ?>

        <span class="page-numbers current" aria-current="page">
          <?php echo (int) $page; ?>
        </span>

        <?php if ($next_url) : ?>
          <a class="page-numbers next" href="<?php echo esc_url($next_url); ?>" aria-label="Nächste Seite">→</a>
        <?php endif; ?>

      </nav>

      <?php if (!empty($links)) : ?>
        <nav class="pagination pagination--numbers" aria-label="Seitenzahlen">
          <?php echo implode("\n", $links); ?>
        </nav>
      <?php endif; ?>
    </div>

  </div>
</section>

<?php wp_reset_postdata(); ?>
