<?php
if (!defined('ABSPATH')) exit;

$tax_continent = 'continent';
$tax_country   = 'country';

// ✅ EINMALIG holen (statt in jeder Kontinent-Schleife)

$continents = get_terms([
  'taxonomy'   => $tax_continent,
  'hide_empty' => true,
]);

$countries = get_terms([
  'taxonomy'   => $tax_country,
  'hide_empty' => true,
]);


// Falls noch kein Content da ist: Dropdown trotzdem rendern, aber leer/mit Hinweis.
?>
<div class="dropdown dropdown--mega" role="menu" aria-label="Reiseziele">
  <div class="mega">
    <div class="mega__head">Wohin möchtest du reisen?</div>

    <?php if (empty($continents) || is_wp_error($continents)): ?>
      <div style="opacity:.75; font-size:18px; padding: 8px 0 6px;">
        Noch keine Reiseziele vorhanden.
      </div>
    <?php else: ?>

      <div class="mega__grid">

        <!-- LEFT: Kontinente -->
        <div class="mega__left">
          <?php foreach ($continents as $i => $continent): ?>
            <button
              type="button"
              class="mega__tab <?php echo $i === 0 ? 'is-active' : ''; ?>"
              data-continent="<?php echo esc_attr($continent->slug); ?>">
              <?php echo esc_html($continent->name); ?>
            </button>
          <?php endforeach; ?>
        </div>

        <!-- RIGHT: Panels -->
        <div class="mega__right">
          <?php 
            foreach ($continents as $i => $continent): ?>

            <?php
            
            // Länder filtern: nur Länder, die Posts MIT diesem Kontinent haben
            $valid = [];

            foreach ($countries as $country) {
              $q = new WP_Query([
                'post_type'      => 'post',
                'posts_per_page' => 1,
                'no_found_rows'  => true,
                'tax_query'      => [
                  [
                    'taxonomy' => $tax_continent,
                    'field'    => 'slug',
                    'terms'    => $continent->slug,
                  ],
                  [
                    'taxonomy' => $tax_country,
                    'field'    => 'slug',
                    'terms'    => $country->slug,
                  ],
                ],
              ]);
              if ($q->have_posts()) $valid[] = $country;
              wp_reset_postdata();
            }

            if (empty($valid)) continue;

            usort($valid, fn($a, $b) => strcmp($a->name, $b->name));
            ?>

            <div class="mega__panel <?php echo $i === 0 ? 'is-active' : ''; ?>"
                 data-panel="<?php echo esc_attr($continent->slug); ?>">

              <div class="mega__right-head">
                <a class="mega__right-title" href="<?php echo esc_url(get_term_link($continent)); ?>">
                  <?php echo esc_html($continent->name); ?>
                </a>
              </div>

              <div class="mega__countries">
                <?php foreach ($valid as $country): ?>
                  <a class="mega__country" href="<?php echo esc_url(get_term_link($country)); ?>">
                    <?php echo esc_html($country->name); ?>
                  </a>
                <?php endforeach; ?>
              </div>

            </div>

          <?php endforeach; ?>
        </div>

      </div>

    <?php endif; ?>
  </div>
</div>
