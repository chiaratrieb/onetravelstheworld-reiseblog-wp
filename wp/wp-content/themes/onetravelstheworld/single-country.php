<?php
if (!defined('ABSPATH')) exit;

get_header();

if (have_posts()) :
  while (have_posts()) : the_post();

    // ✅ Diese Zeile ist der Beweis, dass DU in single-country.php bist:
    echo '<div class="container" style="padding:18px 0; opacity:.7;">single-country.php aktiv ✅</div>';

    // Zuordnung aus deiner Country-Metabox
    $continent_id = (int) get_post_meta(get_the_ID(), '_otw_continent_term_id', true);
    $country_id   = (int) get_post_meta(get_the_ID(), '_otw_country_term_id', true);

    // Titel der Country Page
    echo '<main id="site-main" class="container" style="padding:10px 0 30px;">';
    echo '<h1 style="margin:0 0 18px;">' . esc_html(get_the_title()) . '</h1>';

    // Artikelgrid nur, wenn Zuordnung vorhanden
    if ($continent_id && $country_id) {
      get_template_part(
        'parts/country-article-grid',
        null,
        [
          'continent_id' => $continent_id,
          'country_id'   => $country_id,
        ]
      );
    } else {
      echo '<p style="opacity:.75;">Bitte in der Country Page rechts Kontinent + Land auswählen (Metabox „Ziel-Zuordnung“).</p>';
    }

    echo '</main>';

  endwhile;
endif;

get_footer();
