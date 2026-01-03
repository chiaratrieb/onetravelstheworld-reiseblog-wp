<?php
if (!defined('ABSPATH')) exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="header">
  <div class="header__row">
    <!-- Logo ganz links -->
    <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
      <span class="brand__logo">
        <img
          src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/logo onetravelstheworld (1024 x 300 px).svg"
          alt="onetravelstheworld Logo"
        >
      </span>
    </a>

    <!-- Rest im Container -->
    <div class="container header__inner">
      <div class="header__right">

        <!-- Suche (nur UI, später WP Search) -->
        <form class="search" action="<?php echo esc_url(home_url('/')); ?>" method="get" role="search">
          <span class="search__icon" aria-hidden="true">⌕</span>
          <input class="search__input" type="search" name="s" placeholder="Suche ..." />
        </form>

        <!-- Navigation -->
        <nav class="nav" aria-label="Hauptnavigation">
          <a href="<?php echo esc_url(home_url('/about/')); ?>" class="nav__link">About</a>

          <!-- Reiseziele (Mega Dropdown) -->
          <div class="nav__item nav__item--dropdown nav__item--mega" data-mega="destinations">
            <button class="nav__link nav__drop" type="button" aria-haspopup="true" aria-expanded="false">
              Reiseziele
            </button>

            <?php get_template_part('parts/mega-destinations'); ?>
          </div>

          <!-- Reisethemen: klassisches Dropdown (placeholder) -->
          <div class="nav__item nav__item--dropdown">
            <button class="nav__link nav__drop" type="button" aria-haspopup="true" aria-expanded="false">
              Reisethemen
            </button>

            <div class="dropdown" role="menu" aria-label="Reisethemen">
              <a class="dropdown__link" href="#" role="menuitem">Städtetrips</a>
              <a class="dropdown__link" href="#" role="menuitem">Roadtrips</a>
              <a class="dropdown__link" href="#" role="menuitem">Wandern</a>
              <a class="dropdown__link" href="#" role="menuitem">Vanlife</a>
              <a class="dropdown__link" href="#" role="menuitem">Budget</a>
            </div>
          </div>

          <a href="<?php echo esc_url(home_url('/reiseplanung/')); ?>" class="nav__link">Reiseplanung</a>
          <a href="<?php echo esc_url(home_url('/reisezeit/')); ?>" class="nav__link">Reisezeit</a>
        </nav>

        <!-- Social -->
        <div class="social">
          <a class="social__btn" href="#" aria-label="Instagram">◎</a>
          <a class="social__btn" href="#" aria-label="Pinterest">⟐</a>
          <a class="social__btn" href="#" aria-label="Facebook">f</a>
        </div>

      </div>
    </div>
  </div>
</header>
