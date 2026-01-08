<?php
if (!defined('ABSPATH')) exit;

/**
 * Args (optional):
 * - country_name (string)
 * - country_code (string) z.B. "ES"
 * - intro_html (string) HTML allowed (wp_kses_post)
 * - destinations (array of ['label' => 'Ávila', 'url' => '#'])
 * - destinations_view_all_url (string)
 * - highlight (array: ['title','url','img'])
 *
 * Bilder/Assets: passe die Pfade in $assets an dein Theme an.
 */

$theme_uri = get_template_directory_uri();

$country_name = $args['country_name'] ?? 'SPAIN';
$country_code = $args['country_code'] ?? 'ES';

$intro_html = $args['intro_html'] ?? "
<p>As you guys might have guessed, I adore Spain. Spain was the first destination on my first international trip...</p>
<p>With endless cultural festivals, world-class beaches, and renowned nightlife, Spain is impressive 365 days a year...</p>
";

$destinations = $args['destinations'] ?? [
  ['label' => 'ÁVILA', 'url' => '#'],
  ['label' => 'BARCELONA', 'url' => '#'],
  ['label' => 'CADIZ', 'url' => '#'],
  ['label' => 'CANARY ISLANDS', 'url' => '#'],
  ['label' => 'CÓRDOBA', 'url' => '#'],
  ['label' => 'GRANADA', 'url' => '#'],
];

$destinations_view_all_url = $args['destinations_view_all_url'] ?? '#';

$highlight = $args['highlight'] ?? [
  'title' => '10 THINGS YOU MUST DO IN BARCELONA',
  'url'   => '#',
  'img'   => $theme_uri . '/assets/images/Thailand_Featured.webp',
];

/** Collage Assets (Platzhalter) */
$assets = [
  'tag'        => $theme_uri . '/assets/images/placeholder.jpg', // z.B. Ticket/Tag Grafik
  'photo1'     => $theme_uri . '/assets/images/Thailand_Featured.webp',
  'photo2'     => $theme_uri . '/assets/images/Thailand_Featured.webp',
  'photo3'     => $theme_uri . '/assets/images/Thailand_Featured.webp',
  'stamp'      => $theme_uri . '/assets/images/placeholder.jpg', // z.B. "Know before you go" Stempel
  'miniCard'   => $theme_uri . '/assets/images/Thailand_Featured.webp', // kleines Polaroid rechts unten
];

?>

<section class="country-hero">
  <div class="container country-hero__inner">

    <!-- LEFT -->
    <div class="country-hero__left">

      <!-- Collage -->
      <div class="country-hero__collage" aria-hidden="true">

        <div class="country-hero__tag">
          <img src="<?php echo esc_url($assets['tag']); ?>" alt="">
          <div class="country-hero__tag-text">
            <div class="country-hero__tag-kicker">EXPLORE</div>
            <div class="country-hero__tag-code"><?php echo esc_html($country_code); ?></div>
            <div class="country-hero__tag-country"><?php echo esc_html($country_name); ?></div>
          </div>
        </div>

        <div class="country-hero__photo country-hero__photo--a">
          <img src="<?php echo esc_url($assets['photo1']); ?>" alt="">
        </div>

        <div class="country-hero__photo country-hero__photo--b">
          <img src="<?php echo esc_url($assets['photo2']); ?>" alt="">
        </div>

        <div class="country-hero__stamp">
          <img src="<?php echo esc_url($assets['stamp']); ?>" alt="">
        </div>

        <div class="country-hero__polaroid">
          <img src="<?php echo esc_url($assets['miniCard']); ?>" alt="">
          <div class="country-hero__polaroid-cap">THE ULTIMATE ITINERARY</div>
        </div>

      </div>

      <!-- Text -->
      <div class="country-hero__copy">
        <div class="country-hero__copy-inner">
          <?php echo wp_kses_post($intro_html); ?>
        </div>
      </div>

    </div>

    <!-- RIGHT -->
    <aside class="country-hero__right">

      <div class="country-hero__side-block">
        <h3 class="country-hero__side-title">DESTINATIONS</h3>

        <ul class="country-hero__destinations">
          <?php foreach ($destinations as $d): ?>
            <li class="country-hero__destinations-item">
              <a href="<?php echo esc_url($d['url']); ?>" class="country-hero__destinations-link">
                <span class="country-hero__pin" aria-hidden="true"></span>
                <span class="country-hero__destinations-label"><?php echo esc_html($d['label']); ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>

        <a class="country-hero__viewall" href="<?php echo esc_url($destinations_view_all_url); ?>">
          VIEW ALL
        </a>
      </div>

      <div class="country-hero__side-block">
        <h3 class="country-hero__side-title">HIGHLIGHT</h3>

        <a class="country-hero__highlight" href="<?php echo esc_url($highlight['url']); ?>">
          <div class="country-hero__highlight-img">
            <img src="<?php echo esc_url($highlight['img']); ?>" alt="" loading="lazy" decoding="async">
          </div>
          <div class="country-hero__highlight-overlay">
            <div class="country-hero__highlight-title"><?php echo esc_html($highlight['title']); ?></div>
          </div>
        </a>
      </div>

    </aside>

  </div>
</section>
