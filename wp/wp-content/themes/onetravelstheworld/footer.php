<?php if (!defined('ABSPATH')) exit; ?>

<footer class="site-footer">
  <div class="container">

    <!-- ROW 1: Logo | 3 Menüs | Social -->
    <div class="site-footer__top">

      <div class="site-footer__brand">
        <a class="site-footer__logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Startseite">
          <img
            src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/logo onetravelstheworld (1024 x 300 px).svg"
            alt="onetravelstheworld"
            loading="lazy"
            decoding="async"
          >
        </a>
      </div>

      <div class="site-footer__cols">

        <div class="site-footer__col">
          <div class="site-footer__heading">EMPFEHLUNGEN</div>
          <ul class="site-footer__list site-footer__list--recs">
            <li>
              <a href="#">
                <span class="site-footer__icon">🏨</span>
                Hotels: <span class="hl">Booking.com</span>
              </a>
            </li>
            <li>
              <a href="#">
                <span class="site-footer__icon">🎟️</span>
                Touren: <span class="hl">Get Your Guide</span>
              </a>
            </li>
            <li>
              <a href="#">
                <span class="site-footer__icon">💳</span>
                Kreditkarte: <span class="hl">DKB Visa</span>
              </a>
            </li>
          </ul>
        </div>

        <div class="site-footer__col">
          <div class="site-footer__heading">INFOS</div>
          <ul class="site-footer__list">
            <li><a href="<?php echo esc_url(home_url('/about/')); ?>">Über onetravelstheworld</a></li>
            <li><a href="#">Zusammenarbeit</a></li>
            <li><a href="#">Unsere Fotoausrüstung</a></li>
          </ul>
        </div>

        <div class="site-footer__col">
          <div class="site-footer__heading">BELIEBTE REISEZIELE</div>
          <ul class="site-footer__list">
            <li><a href="#">Thailand</a></li>
            <li><a href="#">Deutschland</a></li>
            <li><a href="#">Arabische Emirate</a></li>
          </ul>
        </div>

      </div>

      <div class="site-footer__social">
        <a class="site-footer__social-btn" href="#">f</a>
        <a class="site-footer__social-btn" href="#">◎</a>
        <a class="site-footer__social-btn" href="#">p</a>
      </div>

    </div>

    <div class="site-footer__divider"></div>

    <!-- ROW 2 -->
    <?php
function otw_page_link($slug, $label) {
  $page = get_page_by_path($slug, OBJECT, 'page');
  if ($page) {
    echo '<a href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html($label) . '</a>';
  } else {
    // Fallback: falls Seite (noch) nicht existiert
    echo '<a href="' . esc_url(home_url('/' . trim($slug, '/') . '/')) . '">' . esc_html($label) . '</a>';
  }
}
?>

<div class="site-footer__legal">
  <?php otw_page_link('impressum', 'IMPRESSUM'); ?>
  <?php otw_page_link('datenschutz', 'DATENSCHUTZ'); ?>
  <?php otw_page_link('cookies', 'COOKIES'); ?>
  <?php otw_page_link('sitemap', 'SITEMAP'); ?>
</div>

      <div class="site-footer__copy">
        © 2026 onetravelstheworld
      </div>
    </div>

  </div>
</footer>

<?php
// Globale HTML-Templates für JS Components (Split/Recs/Newsletter etc.)
get_template_part('templates');
?>

<?php wp_footer(); ?>
</body>
</html>
