<?php
if (!defined('ABSPATH')) exit;

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



