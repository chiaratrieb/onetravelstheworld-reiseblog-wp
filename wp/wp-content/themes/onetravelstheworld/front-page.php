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
  set_query_var('query',  [
  'context' => 'frontpage_carousel',
  'slot'    => 'top-reiseziele-global', // nur zur Identifikation, optional
    ]);
  get_template_part('parts/carousel');
  ?>

  <!-- SPLIT 1 (bleibt JS-mount, Inhalt über content-components.static) -->
  <div class="split-mount"
       data-component="split"
       data-template="tpl-split-section-left"
       data-key="split_winter"></div>

  <!-- RECOMMENDATIONS (bleibt JS-mount, Inhalt über content-components.static) -->
  <div class="recommendations-mount"
       data-component="recommendations"
       data-template="tpl-recommendations"
       data-key="recs_frontpage1"></div>

  <!-- CAROUSEL MIDDLE (serverseitig) -->
  <?php
  set_query_var('kicker', 'REISEPLANUNG LEICHT GEMACHT');
  set_query_var('title',  'Top Reiseziele 2026');
  set_query_var('query',  [
  'context' => 'frontpage_carousel',
  'slot'    => 'winter-reiseziele', // nur zur Identifikation, optional
  ]);
  get_template_part('parts/carousel');
  ?>

  <!-- SPLIT 2 (bleibt JS-mount, Inhalt über content-components.static) -->
  <div class="split-mount"
       data-component="split"
       data-template="tpl-split-section-right"
       data-key="split_unique"></div>

  <!-- WORLD MAP -->
  <?php get_template_part('parts/world-map'); ?>

  <!-- CAROUSEL BOTTOM (serverseitig) -->
  <?php
  set_query_var('kicker', 'FERNE WELTEN');
  set_query_var('title',  'Best Off Südostasien');
  set_query_var('query',  [
  'context' => 'frontpage_carousel',
  'slot'    => 'aussergewoehnliche-reiseziele', // nur zur Identifikation, optional
  ]);
  get_template_part('parts/carousel');
  ?>

  <!-- PACKING GUIDES -->
  <?php get_template_part('parts/packing-guides'); ?>

  <!-- RECOMMENDATIONS 2 (bleibt JS-mount, Inhalt über content-components.static) -->
  <div class="recommendations-mount"
       data-component="recommendations"
       data-template="tpl-recommendations"
       data-key="recs_frontpage2"></div>

  <!-- NEWSLETTER (bleibt JS-mount, Inhalt über content-components.static) -->
  <div class="newsletter-mount"
       data-component="newsletter"
       data-template="tpl-newsletter"
       data-key="newsletter_frontpage"></div>



</main>

<?php get_footer(); ?>
