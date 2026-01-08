<?php
if (!defined('ABSPATH')) exit;

get_header();

$post_id = get_the_ID();

// Metabox-Werte (Term IDs)
$continent_id = (int) get_post_meta($post_id, '_otw_continent_term_id', true);
$country_id   = (int) get_post_meta($post_id, '_otw_country_term_id', true);

// Term-Objekte (optional)
$continent = $continent_id ? get_term($continent_id, 'continent') : null;
$country   = $country_id   ? get_term($country_id, 'country') : null;

function otw_term_name($term) {
  return ($term && !is_wp_error($term)) ? $term->name : '';
}
function otw_term_slug($term) {
  return ($term && !is_wp_error($term)) ? $term->slug : '';
}
?>

<main id="site-main">

  <!-- HERO (Component: country-hero-section) -->
  <?php
  get_template_part('parts/country-hero-section', null, [
    'post_id'      => $post_id,
    'continent_id' => $continent_id,
    'country_id'   => $country_id,
  ]);
  ?>

  <?php
  // Artikel-Grid als Part (nur wenn Zuordnung gesetzt)
  if ($continent_id && $country_id) {
    get_template_part('parts/country-article-grid', null, [
      'page_id'      => $post_id,        // wichtig für Pagination-Base
      'continent_id' => $continent_id,
      'country_id'   => $country_id,
    ]);
  } else {
    echo '<section class="section"><div class="container"><p style="opacity:.75">Bitte Kontinent + Land in der Metabox setzen.</p></div></section>';
  }
  ?>

  <!-- RECOMMENDATIONS (bleibt JS-mount, Inhalt über content-components.static) -->
<div class="recommendations-mount"
     data-component="recommendations"
     data-template="tpl-recommendations"
     data-key="recs_country_articles"></div>


     <!-- CAROUSEL: Häufig gelesen / Meine Top Reisetipps -->
<?php
set_query_var('kicker', 'HÄUFIG GELESEN');
set_query_var('title',  'Meine Top Reisetipps');
set_query_var('query',  [
  'context'      => 'country_top',
  'continent_id' => $continent_id ?? 0,
  'country_id'   => $country_id ?? 0,
]);
get_template_part('parts/carousel');
?>

<?php
get_template_part('parts/country-info-sections', null, [
  'post_id' => $post_id,
]);
?>


</main>

<?php get_footer(); ?>
