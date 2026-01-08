<?php
/* Template Name: Legal – Sitemap */
if (!defined('ABSPATH')) exit;
get_header();
?>

<main id="site-main">
  <section class="section" style="padding:80px 0;">
    <div class="container" style="max-width:900px;">
      <h1>Sitemap</h1>

      <h2>Country Pages</h2>
      <ul>
        <?php
        $q = new WP_Query([
          'post_type'      => 'country_page',
          'posts_per_page' => -1,
          'post_status'    => 'publish',
          'orderby'        => 'title',
          'order'          => 'ASC',
        ]);

        while ($q->have_posts()) : $q->the_post(); ?>
          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php endwhile; wp_reset_postdata(); ?>
      </ul>

      <h2>Letzte Beiträge</h2>
      <ul>
        <?php
        $p = new WP_Query([
          'post_type'      => 'post',
          'posts_per_page' => 30,
          'post_status'    => 'publish',
        ]);
        while ($p->have_posts()) : $p->the_post(); ?>
          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php endwhile; wp_reset_postdata(); ?>
      </ul>
    </div>
  </section>
</main>

<?php get_footer(); ?>
