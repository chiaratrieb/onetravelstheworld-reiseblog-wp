<?php
if (!defined('ABSPATH')) exit;
get_header();
?>

<main id="site-main">

  <!-- HERO -->
  <section class="hero">
    <div class="hero__image" role="img" aria-label="Headerbild"></div>

    <div class="hero__overlay container">
      <div class="hero__badge">REISEBLOG</div>
      <div class="hero__script">onetravelstheworld</div>
    </div>
  </section>

  <!-- STATS -->
  <section class="stats">
    <div class="container stats__inner">
      <div class="stat"><div class="stat__num">34</div><div class="stat__label">Bereiste Länder</div></div>
      <div class="stat"><div class="stat__num">880</div><div class="stat__label">Tipps & Guides</div></div>
      <div class="stat"><div class="stat__num">230</div><div class="stat__label">Unterkünfte</div></div>
      <div class="stat"><div class="stat__num">143</div><div class="stat__label">Wanderungen</div></div>
    </div>
  </section>

  <!-- CAROUSEL TOP (serverseitig) -->
  <?php
  set_query_var('kicker', 'REISEPLANUNG LEICHT GEMACHT');
  set_query_var('title',  'Top Reiseziele 2026');
  set_query_var('query',  ['context' => 'frontpage_top']);
  get_template_part('parts/carousel');
  ?>

  <!-- SPLIT 1 (bleibt JS-mount) -->
  <div class="split-mount"
       data-component="split"
       data-template="tpl-split-section-left"
       data-key="split_winter"></div>

  <!-- RECOMMENDATIONS (bleibt JS-mount) -->
  <div class="recommendations-mount"
       data-component="recommendations"
       data-template="tpl-recommendations"
       data-key="recs_frontpage1"></div>

  <!-- CAROUSEL MIDDLE (serverseitig) -->
  <?php
  set_query_var('kicker', 'KALTE JAHRESZEIT');
  set_query_var('title',  'Die besten Winterdestinations in Europa');
  set_query_var('query',  ['context' => 'frontpage_middle']);
  get_template_part('parts/carousel');
  ?>

  <!-- SPLIT 2 -->
  <div class="split-mount"
       data-component="split"
       data-template="tpl-split-section-right"
       data-key="split_unique"></div>

  <!-- CAROUSEL BOTTOM (serverseitig) -->
  <?php
  set_query_var('kicker', 'FERNE WELTEN');
  set_query_var('title',  'Best Off Südostasien');
  set_query_var('query',  ['context' => 'frontpage_bottom']);
  get_template_part('parts/carousel');
  ?>

  <!-- RECOMMENDATIONS 2 -->
  <div class="recommendations-mount"
       data-component="recommendations"
       data-template="tpl-recommendations"
       data-key="recs_frontpage2"></div>

  <!-- NEWSLETTER -->
  <div class="newsletter-mount"
       data-component="newsletter"
       data-template="tpl-newsletter"
       data-key="newsletter_frontpage"></div>

  <!-- Templates (Split/Recommendations/Newsletter werden weiter per JS geklont) -->
  <?php get_template_part('templates'); ?>

</main>

<?php get_footer(); ?>
