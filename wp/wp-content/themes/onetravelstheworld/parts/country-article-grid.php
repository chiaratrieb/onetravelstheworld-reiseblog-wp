<?php
if (!defined('ABSPATH')) exit;

/**
 * Erwartet:
 * $continent_id (int)
 * $country_id   (int)
 */

$paged = max(1, get_query_var('paged'));

$args = [
  'post_type'      => 'post',
  'posts_per_page' => 16,
  'paged'          => $paged,
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
];

$q = new WP_Query($args);

// ❗ Wenn keine Beiträge → Section NICHT rendern
if (!$q->have_posts()) {
  return;
}
?>

<section class="section country-articles">
  <div class="container">

    <h2 class="section__title">Artikel & Reisetipps</h2>

    <div class="article-grid">
      <?php while ($q->have_posts()): $q->the_post(); ?>
        <article class="article-card">
          <a href="<?php the_permalink(); ?>" class="article-card__link">
            <?php if (has_post_thumbnail()): ?>
              <div class="article-card__image">
                <?php the_post_thumbnail('medium_large'); ?>
              </div>
            <?php endif; ?>

            <h3 class="article-card__title"><?php the_title(); ?></h3>
          </a>
        </article>
      <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
      <?php
      echo paginate_links([
        'total'   => $q->max_num_pages,
        'current' => $paged,
        'prev_text' => '←',
        'next_text' => '→',
      ]);
      ?>
    </div>

  </div>
</section>

<?php wp_reset_postdata(); ?>
