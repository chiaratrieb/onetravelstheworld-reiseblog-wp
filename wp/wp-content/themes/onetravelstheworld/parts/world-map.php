<?php
if (!defined('ABSPATH')) exit;

$tree = otw_get_destinations_tree();
$theme_uri = get_template_directory_uri();

/**
 * ============================
 * CONTINENT MARKERS (Variante A: alles im Code)
 * Key = continent slug (taxonomy: continent)
 * x/y = Prozent (0–100)
 * img = Bild im Theme
 * label = optional (sonst Continent Name)
 *
 * Klick-Ziel: get_term_link($cont) (Taxonomy-Archiv)
 * ============================
 */
$continent_markers_config = [
  'northamerica' => [
    'x'     => 18.0,
    'y'     => 35.0,
    'img'   => $theme_uri . '/assets/images/Thailand_Featured.webp',
    'label' => 'NORTH AMERICA',
  ],
  'southamerica' => [
    'x'     => 27.0,
    'y'     => 68.0,
    'img'   => $theme_uri . '/assets/images/Thailand_Featured.webp',
    'label' => 'SOUTH AMERICA',
  ],
  'europe' => [
    'x'     => 47.5,
    'y'     => 25.0,
    'img'   => $theme_uri . '/assets/images/Thailand_Featured.webp',
    'label' => 'EUROPE',
  ],
  'africa' => [
    'x'     => 50,
    'y'     => 55,
    'img'   => $theme_uri . '/assets/images/Thailand_Featured.webp',
    'label' => 'AFRICA',
  ],
  'asia' => [
    'x'     => 72,
    'y'     => 35,
    'img'   => $theme_uri . '/assets/images/Thailand_Featured.webp',
    'label' => 'ASIA',
  ],
  'oceania' => [
    'x'     => 83.0,
    'y'     => 80.0,
    'img'   => $theme_uri . '/assets/images/Thailand_Featured.webp',
    'label' => 'OCEANIA',
  ],
  'antarctica' => [
    'x'     => 52.0,
    'y'     => 92.0,
    'img'   => $theme_uri . '/assets/images/Thailand_Featured.webp',
    'label' => 'ANTARCTICA',
  ],
];

// Payload bauen
$continent_markers_payload = [];
$counter = 1;

$continents = get_terms([
  'taxonomy'   => 'continent',
  'hide_empty' => false,
  'orderby'    => 'name',
  'order'      => 'ASC',
]);

if (!is_wp_error($continents) && !empty($continents)) {
  foreach ($continents as $cont) {
    $slug = $cont->slug;

    // nur wenn im Config vorhanden
    if (empty($continent_markers_config[$slug])) continue;

    $m = $continent_markers_config[$slug];

    // Ziel-URL: Kontinent Taxonomy Archiv
    $url = get_term_link($cont);
    if (is_wp_error($url)) continue;

    $continent_markers_payload[] = [
      'n'     => $counter++,
      'slug'  => $slug,
      'url'   => $url,
      'x'     => (float) ($m['x'] ?? 0),
      'y'     => (float) ($m['y'] ?? 0),
      'img'   => (string) ($m['img'] ?? ''),
      'label' => (string) ($m['label'] ?? mb_strtoupper($cont->name, 'UTF-8')),
    ];
  }
}
?>

<section class="section worldmap">
  <div class="container worldmap__inner">

    <!-- LEFT: Navigation (Accordion) -->
    <aside class="worldmap__nav" data-worldmap-nav>
      <div class="worldmap__nav-title">CLICK TO EXPAND</div>

      <?php if (!empty($tree)): ?>
        <ol class="worldmap__list">
          <?php foreach ($tree as $i => $node):
            $continent = $node['term'];
            $countries = $node['countries'];
          ?>
            <li class="worldmap__item" data-continent="<?php echo esc_attr($continent->slug); ?>">
              <button class="worldmap__toggle" type="button" aria-expanded="false">
                <span class="worldmap__num"><?php echo (int)($i + 1); ?></span>
                <span class="worldmap__diamond" aria-hidden="true">✦</span>
                <span class="worldmap__name"><?php echo esc_html(mb_strtoupper($continent->name, 'UTF-8')); ?></span>
              </button>

              <div class="worldmap__panel" hidden>
                <?php if (!empty($countries)): ?>
                  <ul class="worldmap__countries">
                    <?php foreach ($countries as $c): ?>
                      <li>
                        <a href="<?php echo esc_url($c['url']); ?>">
                          <?php echo esc_html(mb_strtoupper($c['term']->name, 'UTF-8')); ?>
                        </a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                <?php else: ?>
                  <div class="worldmap__empty">Noch keine Länder verknüpft.</div>
                <?php endif; ?>
              </div>
            </li>
          <?php endforeach; ?>
        </ol>
      <?php else: ?>
        <div class="worldmap__empty" style="opacity:.7; padding:14px 0;">
          Noch keine Country Pages verknüpft.
        </div>
      <?php endif; ?>
    </aside>

    <!-- RIGHT: Map + Continent Markers -->
    <div class="worldmap__stage">
      <div class="worldmap__map"
           data-worldmap-map
           data-markers="<?php echo esc_attr(wp_json_encode($continent_markers_payload)); ?>"
           style="background-image:url('<?php echo esc_url($theme_uri . '/assets/images/worldmap_beige.svg'); ?>');">
      </div>
    </div>

  </div>
</section>
