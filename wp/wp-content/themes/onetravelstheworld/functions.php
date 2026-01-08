<?php
if (!defined('ABSPATH')) exit;

add_action('after_setup_theme', function () {
  add_theme_support('post-thumbnails');

//Destination Menü 
require_once get_template_directory() . '/inc/destinations.php';

//Registrierung Footer Menüs
register_nav_menus([
    'footer_recommendations' => 'Footer: Empfehlungen',
    'footer_infos'           => 'Footer: Infos',
    'footer_popular'         => 'Footer: Beliebte Reiseziele',
    'footer_legal'           => 'Footer: Legal Links (Impressum etc.)',
    'footer_social'          => 'Footer: Social Links',
  ]);
});


add_action('wp_enqueue_scripts', function () {
  $uri = get_template_directory_uri();

  // CSS
  wp_enqueue_style('otw-base',   $uri . '/css/base.css', [], null);
  wp_enqueue_style('otw-layout', $uri . '/css/layout.css', ['otw-base'], null);

  // layout
  wp_enqueue_style('otw-header', $uri . '/css/layout/header.css', ['otw-layout'], null);
  wp_enqueue_style('otw-footer', $uri . '/css/layout/footer.css', ['otw-layout'], null);

  // components
  wp_enqueue_style('otw-carousel', $uri . '/css/components/carousel-section.css', ['otw-layout'], null);
  wp_enqueue_style('otw-mega',     $uri . '/css/components/mega-dropdown.css', ['otw-layout'], null);
  wp_enqueue_style('otw-split',    $uri . '/css/components/split-section.css', ['otw-layout'], null);
  wp_enqueue_style('otw-recs',     $uri . '/css/components/recommendations.css', ['otw-layout'], null);
  wp_enqueue_style('otw-news',     $uri . '/css/components/newsletter.css', ['otw-layout'], null);
  wp_enqueue_style('otw-stats',    $uri . '/css/components/stats-bar.css', ['otw-layout'], null);
  wp_enqueue_style('otw-article-grid', $uri . '/css/components/article-grid.css', ['otw-layout'], null);
  wp_enqueue_style('otw-country-hero', $uri . '/css/components/country-hero-section.css', ['otw-layout'], null);
  wp_enqueue_style('otw-country-info-sections', $uri . '/css/components/country-info-sections.css', ['otw-layout'], null);
  wp_enqueue_style('otw-worldmap', $uri . '/css/components/worldmap.css', ['otw-layout'], null);


  // pages
  wp_enqueue_style('otw-country-page',   $uri . '/css/pages/country-page.css',  ['otw-layout'],  null);


  /// JS (als Module!)
  wp_enqueue_script('otw-script', $uri . '/js/script.js', [], null, true);

  // wichtig: type="module"
  add_filter('script_loader_tag', function ($tag, $handle, $src) {
    if ($handle === 'otw-script') {
      return '<script type="module" src="' . esc_url($src) . '"></script>';
    }
    return $tag;
  }, 10, 3);
});

add_action('wp_head', function () {
  echo '<script>window.OTW_THEME_URI = ' . json_encode(get_template_directory_uri()) . ';</script>' . "\n";
}, 1);


// ================================
// CPT/Taxonomies: Continent + Country (für Posts)
// ================================
add_action('init', function () {

  // 1) CONTINENT (hierarchisch)
  register_taxonomy('continent', ['post'], [
    'labels' => [
      'name'              => 'Kontinente',
      'singular_name'     => 'Kontinent',
      'search_items'      => 'Kontinente durchsuchen',
      'all_items'         => 'Alle Kontinente',
      'parent_item'       => 'Übergeordneter Kontinent',
      'parent_item_colon' => 'Übergeordneter Kontinent:',
      'edit_item'         => 'Kontinent bearbeiten',
      'update_item'       => 'Kontinent aktualisieren',
      'add_new_item'      => 'Neuen Kontinent hinzufügen',
      'new_item_name'     => 'Neuer Kontinent-Name',
      'menu_name'         => 'Kontinente',
    ],
    'public'            => true,
    'hierarchical'      => true,
    'show_ui'           => true,
    'show_admin_column' => true,
    'show_in_rest'      => true, // Gutenberg/REST
    'rewrite'           => [
      'slug' => 'kontinent',
      'with_front' => false,
    ],
  ]);

  // 2) COUNTRY (hierarchisch + eigene Meta-Box mit Suche)
register_taxonomy('country', ['post'], [
  'labels' => [
    'name'          => 'Länder',
    'singular_name' => 'Land',
    'menu_name'     => 'Länder',
    'add_new_item'  => 'Neues Land hinzufügen',
  ],
  'public'            => true,
  'hierarchical'      => true,       // ✅ Checkbox-Liste
  'show_ui'           => true,
  'show_admin_column' => true,
  'show_in_rest'      => true,       // Gutenberg bleibt an
  'rewrite'           => ['slug' => 'land', 'with_front' => false],

  // ⭐ unsere eigene Checkbox+Search Metabox
  'meta_box_cb'       => 'otw_country_metabox',
]);

}, 0);

/**
 * Länder-Metabox (Checkboxen) + Suchfeld (nur Länder)
 * Rendered als klassische Metabox (unten im Editor), funktioniert ohne ACF Pro.
 */
function otw_country_metabox($post, $box) {
  $taxonomy = 'country';

  // vorhandene Terms + ausgewählte Terms
  $tax = get_taxonomy($taxonomy);
  $terms = get_terms([
    'taxonomy'   => $taxonomy,
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
  ]);

  $selected = wp_get_object_terms($post->ID, $taxonomy, ['fields' => 'ids']);
  if (!is_array($selected)) $selected = [];

  // Nonce für WP-Term-Save
  wp_nonce_field('otw_country_metabox_save', 'otw_country_nonce');

  echo '<div class="otw-taxbox">';
  echo '<p style="margin:0 0 8px;"><strong>' . esc_html($tax->labels->name) . '</strong></p>';

  // Suchfeld
  echo '<input type="search" class="otw-taxbox__search" placeholder="Land suchen …" style="width:100%; margin:0 0 10px;" />';

  // Liste
  echo '<div class="otw-taxbox__list" style="max-height:240px; overflow:auto; padding:8px; border:1px solid #dcdcde; background:#fff;">';

  if (empty($terms) || is_wp_error($terms)) {
    echo '<em style="opacity:.75;">Noch keine Länder angelegt.</em>';
  } else {
    foreach ($terms as $t) {
      $checked = in_array((int)$t->term_id, $selected, true) ? 'checked' : '';
      echo '<label class="otw-taxbox__item" style="display:block; margin:0 0 6px;" data-name="' . esc_attr(mb_strtolower($t->name)) . '">';
      echo '<input type="checkbox" name="tax_input[' . esc_attr($taxonomy) . '][]" value="' . (int)$t->term_id . '" ' . $checked . ' /> ';
      echo esc_html($t->name);
      echo '</label>';
    }
  }

  echo '</div>'; // list
  echo '</div>'; // box
}

/**
 * Speichern (WP macht tax_input meist selbst, aber wir lassen es sauber durch)
 */
add_action('save_post', function($post_id) {
  if (!isset($_POST['otw_country_nonce']) || !wp_verify_nonce($_POST['otw_country_nonce'], 'otw_country_metabox_save')) return;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!current_user_can('edit_post', $post_id)) return;

  // Falls keine Länder ausgewählt: leere Zuordnung setzen
  if (!isset($_POST['tax_input']['country'])) {
    wp_set_object_terms($post_id, [], 'country');
  }
}, 10, 1);

/**
 * Admin JS: filtert nur in unserer Länder-Box
 */
add_action('admin_enqueue_scripts', function($hook) {
  // nur auf Post-Editor Screens
  if (!in_array($hook, ['post.php', 'post-new.php'], true)) return;

  $js = <<<JS
  (function(){
    function init(){
      document.querySelectorAll('.otw-taxbox').forEach(function(box){
        var input = box.querySelector('.otw-taxbox__search');
        var items = box.querySelectorAll('.otw-taxbox__item');
        if(!input || !items.length) return;

        input.addEventListener('input', function(){
          var q = (input.value || '').trim().toLowerCase();
          items.forEach(function(item){
            var name = item.getAttribute('data-name') || '';
            item.style.display = (!q || name.indexOf(q) !== -1) ? '' : 'none';
          });
        });
      });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
  })();
JS;

  wp_add_inline_script('jquery-core', $js); // nutzt nur DOM, hängt sich aber zuverlässig an
});




// ===============================
// CPT: Country Pages (Länder-Landingpages)
// WICHTIG: NICHT "country" nennen, weil das deine Taxonomy ist!
// ===============================
add_action('init', function () {

  $labels = [
    'name'          => 'Country Pages',
    'singular_name' => 'Country Page',
    'menu_name'     => 'Country Pages',
    'add_new_item'  => 'Neue Country Page hinzufügen',
    'edit_item'     => 'Country Page bearbeiten',
    'view_item'     => 'Country Page ansehen',
    'all_items'     => 'Alle Country Pages',
  ];

  register_post_type('country_page', [
    'labels'        => $labels,
    'public'        => true,
    'show_in_rest'  => true,
    'has_archive'   => false,
    'rewrite'       => [
      'slug'       => 'reiseziele',
      'with_front' => false
    ],
    'menu_position' => 21,
    'menu_icon'     => 'dashicons-location',
    'supports'      => ['title', 'editor', 'thumbnail', 'excerpt'],
  ]);

}, 0);

// ==================================================
// Country Page: Kontinent + Land Auswahl (Metabox)
// CPT = country_page
// ==================================================
add_action('add_meta_boxes_country_page', function () {
  add_meta_box(
    'otw_country_context',
    'Ziel-Zuordnung (für Auto-Content)',
    'otw_render_country_context_metabox',
    'country_page',
    'side',
    'high'
  );
});

function otw_render_country_context_metabox($post) {
  wp_nonce_field('otw_country_context_save', 'otw_country_context_nonce');

  $saved_continent = (int) get_post_meta($post->ID, '_otw_continent_term_id', true);
  $saved_country   = (int) get_post_meta($post->ID, '_otw_country_term_id', true);

  $continents = get_terms([
    'taxonomy'   => 'continent',
    'hide_empty' => false,
  ]);

  $countries = get_terms([
    'taxonomy'   => 'country',
    'hide_empty' => false,
  ]);

  echo '<p style="margin:0 0 8px;">Wähle hier die Zuordnung für diese Country-Landingpage. Damit filtern wir später automatisch die Beiträge.</p>';

  echo '<p style="margin:10px 0 6px;"><strong>Kontinent</strong></p>';
  echo '<select name="otw_continent_term_id" style="width:100%;">';
  echo '<option value="0">— wählen —</option>';
  if (!is_wp_error($continents)) {
    foreach ($continents as $t) {
      printf(
        '<option value="%d" %s>%s</option>',
        (int) $t->term_id,
        selected($saved_continent, (int) $t->term_id, false),
        esc_html($t->name)
      );
    }
  }
  echo '</select>';

  echo '<p style="margin:10px 0 6px;"><strong>Land</strong></p>';
  echo '<select name="otw_country_term_id" style="width:100%;">';
  echo '<option value="0">— wählen —</option>';
  if (!is_wp_error($countries)) {
    foreach ($countries as $t) {
      printf(
        '<option value="%d" %s>%s</option>',
        (int) $t->term_id,
        selected($saved_country, (int) $t->term_id, false),
        esc_html($t->name)
      );
    }
  }
  echo '</select>';

  echo '<p style="margin:10px 0 0; opacity:.75; font-size:12px;">Hinweis: Beiträge müssen ebenfalls Kontinent + Land bekommen, damit sie hier erscheinen.</p>';
}

add_action('save_post_country_page', function ($post_id) {
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!isset($_POST['otw_country_context_nonce']) || !wp_verify_nonce($_POST['otw_country_context_nonce'], 'otw_country_context_save')) return;
  if (!current_user_can('edit_post', $post_id)) return;

  $continent_id = isset($_POST['otw_continent_term_id']) ? (int) $_POST['otw_continent_term_id'] : 0;
  $country_id   = isset($_POST['otw_country_term_id']) ? (int) $_POST['otw_country_term_id'] : 0;

  update_post_meta($post_id, '_otw_continent_term_id', $continent_id);
  update_post_meta($post_id, '_otw_country_term_id', $country_id);
}, 10, 1);

// =====================================================
// Country Page: Split Hero Content (Headline, Intro, Image)
// CPT = country_page
// =====================================================
add_action('add_meta_boxes_country_page', function () {
  add_meta_box(
    'otw_country_hero',
    'Hero (Split Section)',
    'otw_render_country_hero_metabox',
    'country_page',
    'normal',
    'high'
  );
});

function otw_render_country_hero_metabox($post) {
  wp_nonce_field('otw_country_hero_save', 'otw_country_hero_nonce');

  $headline = (string) get_post_meta($post->ID, '_otw_country_hero_headline', true);
  $intro    = (string) get_post_meta($post->ID, '_otw_country_hero_intro', true);
  $image_id = (int) get_post_meta($post->ID, '_otw_country_hero_image_id', true);

  $img_url  = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
  ?>
  <style>
    .otw-field{margin:0 0 14px;}
    .otw-field label{display:block;font-weight:600;margin:0 0 6px;}
    .otw-help{font-size:12px;opacity:.75;margin-top:6px;}
    .otw-hero-img{display:flex;gap:14px;align-items:center;}
    .otw-hero-preview{width:120px;height:120px;border-radius:999px;overflow:hidden;background:#f1f1f1;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;}
    .otw-hero-preview img{width:100%;height:100%;object-fit:cover;display:block;}
  </style>

  <div class="otw-field">
    <label for="otw_country_hero_headline">Headline</label>
    <input type="text" id="otw_country_hero_headline" name="otw_country_hero_headline" value="<?php echo esc_attr($headline); ?>" style="width:100%;" placeholder="z. B. Italien Reisetipps: Inspiration und Reiseziele">
    <div class="otw-help">Wenn leer, wird automatisch der Seitentitel verwendet.</div>
  </div>

  <div class="otw-field">
    <label for="otw_country_hero_intro">Intro-Text</label>
    <textarea id="otw_country_hero_intro" name="otw_country_hero_intro" rows="5" style="width:100%;" placeholder="Kurzer Einleitungstext..."><?php echo esc_textarea($intro); ?></textarea>
    <div class="otw-help">Tipp: 2–5 Sätze, wie im Screenshot.</div>
  </div>

  <div class="otw-field">
    <label>Hero-Bild (rund)</label>

    <div class="otw-hero-img">
      <div class="otw-hero-preview" id="otw_country_hero_preview">
        <?php if ($img_url): ?>
          <img src="<?php echo esc_url($img_url); ?>" alt="">
        <?php else: ?>
          <span style="opacity:.6;">Kein Bild</span>
        <?php endif; ?>
      </div>

      <div>
        <input type="hidden" id="otw_country_hero_image_id" name="otw_country_hero_image_id" value="<?php echo (int)$image_id; ?>">
        <button type="button" class="button" id="otw_country_hero_pick">Bild wählen</button>
        <button type="button" class="button" id="otw_country_hero_remove" style="margin-left:6px;">Entfernen</button>
        <div class="otw-help">Bild wird als Kreis angezeigt.</div>
      </div>
    </div>
  </div>

  <script>
    (function(){
      let frame;
      const pickBtn   = document.getElementById('otw_country_hero_pick');
      const removeBtn = document.getElementById('otw_country_hero_remove');
      const input     = document.getElementById('otw_country_hero_image_id');
      const preview   = document.getElementById('otw_country_hero_preview');

      if(!pickBtn) return;

      pickBtn.addEventListener('click', function(e){
        e.preventDefault();
        if(frame){ frame.open(); return; }

        frame = wp.media({
          title: 'Hero Bild auswählen',
          button: { text: 'Übernehmen' },
          multiple: false
        });

        frame.on('select', function(){
          const attachment = frame.state().get('selection').first().toJSON();
          input.value = attachment.id;

          const url = (attachment.sizes && attachment.sizes.large) ? attachment.sizes.large.url : attachment.url;
          preview.innerHTML = '<img src="'+url+'" alt="">';
        });

        frame.open();
      });

      removeBtn.addEventListener('click', function(e){
        e.preventDefault();
        input.value = '';
        preview.innerHTML = '<span style="opacity:.6;">Kein Bild</span>';
      });
    })();
  </script>
  <?php
}

// Media Uploader nur im Country Page Editor laden
add_action('admin_enqueue_scripts', function($hook){
  if (!in_array($hook, ['post.php', 'post-new.php'], true)) return;
  $screen = get_current_screen();
  if (!$screen || $screen->post_type !== 'country_page') return;
  wp_enqueue_media();
});

add_action('save_post_country_page', function($post_id){
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!isset($_POST['otw_country_hero_nonce']) || !wp_verify_nonce($_POST['otw_country_hero_nonce'], 'otw_country_hero_save')) return;
  if (!current_user_can('edit_post', $post_id)) return;

  $headline = isset($_POST['otw_country_hero_headline']) ? sanitize_text_field($_POST['otw_country_hero_headline']) : '';
  $intro    = isset($_POST['otw_country_hero_intro']) ? sanitize_textarea_field($_POST['otw_country_hero_intro']) : '';
  $image_id = isset($_POST['otw_country_hero_image_id']) ? (int) $_POST['otw_country_hero_image_id'] : 0;

  update_post_meta($post_id, '_otw_country_hero_headline', $headline);
  update_post_meta($post_id, '_otw_country_hero_intro', $intro);
  update_post_meta($post_id, '_otw_country_hero_image_id', $image_id);
}, 10, 1);

// =====================================================
// Country Page: Content Sections (Rich Text mit wp_editor)
// - Section Light (z.B. Kurzinfo)
// - Section Beige (z.B. Beste Reisezeit)
// =====================================================
add_action('add_meta_boxes_country_page', function () {
  add_meta_box(
    'otw_country_sections',
    'Country Page Inhalte (Sections)',
    'otw_render_country_sections_metabox',
    'country_page',
    'normal',
    'high'
  );
});

function otw_render_country_sections_metabox($post) {
  wp_nonce_field('otw_country_sections_save', 'otw_country_sections_nonce');

  $light_title = (string) get_post_meta($post->ID, '_otw_country_light_title', true);
  $light_html  = (string) get_post_meta($post->ID, '_otw_country_light_html', true);

  $beige_title = (string) get_post_meta($post->ID, '_otw_country_beige_title', true);
  $beige_html  = (string) get_post_meta($post->ID, '_otw_country_beige_html', true);

  echo '<p style="margin:0 0 12px;opacity:.8;">Hier pflegst du die Inhalte für die Country Page Sections. Du kannst Überschriften, Fett, Kursiv, Listen und Links verwenden.</p>';

  // --- Section Light ---
  echo '<hr style="margin:14px 0;">';
  echo '<h3 style="margin:0 0 10px;">Section: Heller Hintergrund</h3>';

  echo '<p style="margin:0 0 6px;"><strong>Überschrift</strong></p>';
  echo '<input type="text" name="otw_country_light_title" value="' . esc_attr($light_title) . '" style="width:100%;margin:0 0 12px;" placeholder="z.B. Kurzinfo zu Italien">';

  wp_editor(
    $light_html,
    'otw_country_light_html_editor',
    [
      'textarea_name' => 'otw_country_light_html',
      'media_buttons' => false,
      'teeny'         => false,
      'textarea_rows' => 10,
      'quicktags'     => true,
      'tinymce'       => [
        'toolbar1' => 'formatselect,bold,italic,underline,link,unlink,bullist,numlist,blockquote,removeformat',
        'toolbar2' => '',
      ],
    ]
  );

  // --- Section Beige ---
  echo '<hr style="margin:18px 0;">';
  echo '<h3 style="margin:0 0 10px;">Section: Beiger Hintergrund</h3>';

  echo '<p style="margin:0 0 6px;"><strong>Überschrift</strong></p>';
  echo '<input type="text" name="otw_country_beige_title" value="' . esc_attr($beige_title) . '" style="width:100%;margin:0 0 12px;" placeholder="z.B. Wann ist die beste Reisezeit für Italien?">';

  wp_editor(
    $beige_html,
    'otw_country_beige_html_editor',
    [
      'textarea_name' => 'otw_country_beige_html',
      'media_buttons' => false,
      'teeny'         => false,
      'textarea_rows' => 14,
      'quicktags'     => true,
      'tinymce'       => [
        'toolbar1' => 'formatselect,bold,italic,underline,link,unlink,bullist,numlist,blockquote,removeformat',
        'toolbar2' => '',
      ],
    ]
  );
}

add_action('save_post_country_page', function ($post_id) {
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!isset($_POST['otw_country_sections_nonce']) || !wp_verify_nonce($_POST['otw_country_sections_nonce'], 'otw_country_sections_save')) return;
  if (!current_user_can('edit_post', $post_id)) return;

  $light_title = isset($_POST['otw_country_light_title']) ? sanitize_text_field($_POST['otw_country_light_title']) : '';
  $beige_title = isset($_POST['otw_country_beige_title']) ? sanitize_text_field($_POST['otw_country_beige_title']) : '';

  // HTML aus wp_editor: erlaubte Tags filtern (sicher)
  $allowed = wp_kses_allowed_html('post');

  $light_html = isset($_POST['otw_country_light_html']) ? wp_kses($_POST['otw_country_light_html'], $allowed) : '';
  $beige_html = isset($_POST['otw_country_beige_html']) ? wp_kses($_POST['otw_country_beige_html'], $allowed) : '';

  update_post_meta($post_id, '_otw_country_light_title', $light_title);
  update_post_meta($post_id, '_otw_country_light_html',  $light_html);

  update_post_meta($post_id, '_otw_country_beige_title', $beige_title);
  update_post_meta($post_id, '_otw_country_beige_html',  $beige_html);
}, 10, 1);


// =====================================================
// Admin Checkboxen => setzen/entfernen definierte Tags
// Tags werden automatisch erstellt, falls sie nicht existieren
// =====================================================

function otw_feature_tags_config(): array {
  return [
    [
      'slug'  => 'top-reisetipps',
      'label' => 'Meine Top Reisetipps (Country)',
    ],
    [
      'slug'  => 'top-reiseziele-global',
      'label' => 'Top Reiseziele (Global)',
    ],
    [
      'slug'  => 'winter-reiseziele',
      'label' => 'Winter Reiseziele',
    ],
    [
      'slug'  => 'aussergewoehnliche-reiseziele',
      'label' => 'Außergewöhnliche Reiseziele',
    ],
  ];
}

// Metabox hinzufügen
add_action('add_meta_boxes', function () {
  add_meta_box(
    'otw_feature_tags_box',
    'Carousel-Markierungen',
    'otw_render_feature_tags_metabox',
    'post',
    'side',
    'high'
  );
});

function otw_render_feature_tags_metabox($post) {
  wp_nonce_field('otw_feature_tags_save', 'otw_feature_tags_nonce');

  $cfg = otw_feature_tags_config();

  echo '<p style="margin:0 0 10px; font-size:12px; opacity:.8;">Hake an, wo dieser Beitrag erscheinen soll. (setzt WordPress-Schlagwörter automatisch)</p>';

  foreach ($cfg as $item) {
    $slug = $item['slug'];
    $label = $item['label'];

    $has = has_term($slug, 'post_tag', $post);
    ?>
    <label style="display:flex; gap:10px; align-items:center; margin:8px 0;">
      <input type="checkbox" name="otw_feature_tags[]" value="<?php echo esc_attr($slug); ?>" <?php checked($has); ?> />
      <span><?php echo esc_html($label); ?></span>
    </label>
    <?php
  }

  echo '<p style="margin:10px 0 0; font-size:12px; opacity:.8;">Tags: <code>top-reisetipps</code>, <code>top-reiseziele-global</code>, <code>winter-reiseziele</code>, <code>aussergewoehnliche-reiseziele</code></p>';
}

// Speichern: Checkboxen => Tags setzen/entfernen
add_action('save_post', function ($post_id) {
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (wp_is_post_revision($post_id)) return;
  if (!current_user_can('edit_post', $post_id)) return;

  if (!isset($_POST['otw_feature_tags_nonce']) || !wp_verify_nonce($_POST['otw_feature_tags_nonce'], 'otw_feature_tags_save')) {
    return;
  }

  $cfg = otw_feature_tags_config();
  $all_slugs = array_map(fn($x) => $x['slug'], $cfg);

  // Aus dem Form-Post: was wurde angehakt?
  $selected = isset($_POST['otw_feature_tags']) && is_array($_POST['otw_feature_tags'])
    ? array_values(array_intersect($all_slugs, array_map('sanitize_key', $_POST['otw_feature_tags'])))
    : [];

  // Tags sicherstellen (automatisch anlegen, falls nicht vorhanden)
  foreach ($cfg as $item) {
    $slug = $item['slug'];
    if (!term_exists($slug, 'post_tag')) {
      wp_insert_term($slug, 'post_tag', ['slug' => $slug]);
    }
  }

  // Aktuelle Tags holen
  $current = wp_get_post_terms($post_id, 'post_tag', ['fields' => 'slugs']);
  if (!is_array($current)) $current = [];

  // Unsere Feature-Tags aus dem Current entfernen…
  $kept = array_values(array_diff($current, $all_slugs));
  // …und die neu ausgewählten hinzufügen
  $final = array_values(array_unique(array_merge($kept, $selected)));

  wp_set_post_terms($post_id, $final, 'post_tag', false);

}, 10, 1);

// =====================================================
// Code-only Legal Pages:
// - Auto-create (if missing)
// - Force template
// - Disable editor UI
// - Prevent content changes via admin save
// =====================================================

function otw_legal_pages_config(): array {
  return [
    'impressum' => [
      'title'    => 'Impressum',
      'template' => 'page-impressum.php',
    ],
    'datenschutz' => [
      'title'    => 'Datenschutz',
      'template' => 'page-datenschutz.php',
    ],
    'cookies' => [
      'title'    => 'Cookies',
      'template' => 'page-cookies.php',
    ],
    'sitemap' => [
      'title'    => 'Sitemap',
      'template' => 'page-sitemap.php',
    ],
  ];
}

/**
 * 1) Create pages on theme switch (once)
 */
add_action('after_switch_theme', function () {
  $cfg = otw_legal_pages_config();

  foreach ($cfg as $slug => $p) {
    $existing = get_page_by_path($slug, OBJECT, 'page');

    if (!$existing) {
      $id = wp_insert_post([
        'post_type'    => 'page',
        'post_title'   => $p['title'],
        'post_name'    => $slug,
        'post_status'  => 'publish',
        'post_content' => '', // content irrelevant (comes from template)
      ]);

      if (!is_wp_error($id) && $id) {
        update_post_meta($id, '_wp_page_template', $p['template']);
      }
    } else {
      // Ensure template stays correct even if someone changed it
      update_post_meta($existing->ID, '_wp_page_template', $p['template']);
    }
  }
});

/**
 * Helper: is current admin editing a locked legal page?
 */
function otw_is_locked_legal_page_admin(int $post_id): bool {
  if (!$post_id) return false;

  $cfg = otw_legal_pages_config();
  $slug = get_post_field('post_name', $post_id);

  if (!$slug || !isset($cfg[$slug])) return false;
  return (get_post_type($post_id) === 'page');
}

/**
 * 2) Disable editor UI for these pages (Gutenberg + Classic)
 */
add_action('admin_init', function () {
  if (!is_admin()) return;

  $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
  if (!$post_id) return;

  if (otw_is_locked_legal_page_admin($post_id)) {
    // remove editor + custom fields etc.
    remove_post_type_support('page', 'editor');
    remove_post_type_support('page', 'custom-fields');
    remove_post_type_support('page', 'revisions');
  }
});

/**
 * 3) Force template on save + prevent content changes
 */
add_action('save_post_page', function ($post_id, $post, $update) {
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (wp_is_post_revision($post_id)) return;
  if (!current_user_can('edit_page', $post_id)) return;

  if (!otw_is_locked_legal_page_admin($post_id)) return;

  $cfg = otw_legal_pages_config();
  $slug = get_post_field('post_name', $post_id);

  // Force template always
  if (isset($cfg[$slug]['template'])) {
    update_post_meta($post_id, '_wp_page_template', $cfg[$slug]['template']);
  }
}, 10, 3);

/**
 * 4) Extra safety: block updates to post_title/content for locked pages
 */
add_filter('wp_insert_post_data', function ($data, $postarr) {
  $post_id = isset($postarr['ID']) ? (int)$postarr['ID'] : 0;
  if (!$post_id) return $data;

  if (!otw_is_locked_legal_page_admin($post_id)) return $data;

  // Keep original title/content (so admin can't change it)
  $orig = get_post($post_id);
  if ($orig) {
    $data['post_title']   = $orig->post_title;
    $data['post_content'] = $orig->post_content;
    $data['post_excerpt'] = $orig->post_excerpt;
  }

  return $data;
}, 10, 2);
