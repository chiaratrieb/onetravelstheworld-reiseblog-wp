<?php
if (!defined('ABSPATH')) exit;

/**
 * Destination Tree:
 * Kontinent -> Länder (Links auf country_page)
 *
 * Quelle: country_page (Meta _otw_continent_term_id + _otw_country_term_id)
 * => kundenfähig, weil Zuordnung im WP-Admin gepflegt wird.
 */

function otw_country_page_url_by_country_term(int $country_term_id): string {
  $q = new WP_Query([
    'post_type'      => 'country_page',
    'post_status'    => 'publish',
    'posts_per_page' => 1,
    'no_found_rows'  => true,
    'meta_query'     => [
      [
        'key'     => '_otw_country_term_id',
        'value'   => $country_term_id,
        'compare' => '=',
        'type'    => 'NUMERIC',
      ],
    ],
  ]);

  if ($q->have_posts()) {
    $q->the_post();
    $url = get_permalink(get_the_ID());
    wp_reset_postdata();
    return $url;
  }

  wp_reset_postdata();
  return '';
}

/**
 * @return array<int, array{term: WP_Term, countries: array<int, array{term: WP_Term, url: string}>}>
 */
function otw_get_destinations_tree(): array {
  $continents = get_terms([
    'taxonomy'   => 'continent',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
  ]);

  if (is_wp_error($continents) || empty($continents)) return [];

  // Alle Country Pages holen => mapping continent_id => [country_term_id => WP_Term]
  $pages = get_posts([
    'post_type'      => 'country_page',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
  ]);

  $map = []; // continent_id => [country_term_id => WP_Term]

  foreach ($pages as $pid) {
    $continent_id = (int) get_post_meta($pid, '_otw_continent_term_id', true);
    $country_id   = (int) get_post_meta($pid, '_otw_country_term_id', true);
    if (!$continent_id || !$country_id) continue;

    $country_term = get_term($country_id, 'country');
    if (!$country_term || is_wp_error($country_term)) continue;

    if (!isset($map[$continent_id])) $map[$continent_id] = [];
    $map[$continent_id][$country_term->term_id] = $country_term; // de-dupe
  }

  $tree = [];
  foreach ($continents as $cont) {
    $countries_terms = array_values($map[$cont->term_id] ?? []);

    usort($countries_terms, function($a, $b){
      return strcasecmp($a->name, $b->name);
    });

    $countries = [];
    foreach ($countries_terms as $ctry) {
      $url = otw_country_page_url_by_country_term((int)$ctry->term_id);
      if (!$url) continue;

      $countries[] = [
        'term' => $ctry,
        'url'  => $url,
      ];
    }

    $tree[] = [
      'term'      => $cont,
      'countries' => $countries,
    ];
  }

  return $tree;
}
