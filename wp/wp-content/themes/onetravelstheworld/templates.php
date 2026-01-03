<?php if (!defined('ABSPATH')) exit; ?>

<!-- Template: Carousel -->
<template id="tpl-carousel-section">
  <section class="section carousel-section" data-carousel-section>
    <div class="container">
      <div class="section__kicker" data-carousel-kicker></div>
      <h2 class="section__title" data-carousel-title></h2>

      <div class="carousel" data-carousel>
        <button class="carousel__btn carousel__btn--left" data-carousel-prev aria-label="Zurück">
          <span class="arrow arrow--left"></span>
        </button>

        <div class="carousel__viewport">
          <div class="carousel__track" data-carousel-track></div>
        </div>

        <button class="carousel__btn carousel__btn--right" data-carousel-next aria-label="Weiter">
          <span class="arrow arrow--right"></span>
        </button>
      </div>
    </div>
  </section>
</template>

<!-- Template: Split Left -->
<template id="tpl-split-section-left">
  <section class="section split-section split-section--left" data-split-section>
    <div class="container split-section__inner">
      <div class="split-section__media">
        <div class="split-section__circle">
          <img data-split-image src="" alt="">
        </div>
      </div>

      <div class="split-section__content">
        <div class="split-section__kicker" data-split-kicker></div>
        <h2 class="split-section__title" data-split-title></h2>
        <div data-split-text></div>
        <a class="split-section__btn" data-split-button href="#"></a>
      </div>
    </div>
  </section>
</template>

<!-- Template: Split Right -->
<template id="tpl-split-section-right">
  <section class="section split-section split-section--right" data-split-section>
    <div class="container split-section__inner">

      <div class="split-section__content">
        <div class="split-section__kicker" data-split-kicker></div>
        <h2 class="split-section__title" data-split-title></h2>
        <div data-split-text></div>
        <div class="split-section__script" data-split-script aria-hidden="true"></div>
      </div>

      <div class="split-section__media">
        <div class="split-section__circle">
          <img data-split-image src="" alt="">
          <a class="split-section__btn split-section__btn--overlay" data-split-button href="#"></a>
        </div>
      </div>

    </div>
  </section>
</template>

<!-- Template: Recommendations -->
<template id="tpl-recommendations">
  <section class="section rec-section" data-rec-section>
    <div class="container">
      <div class="rec-grid" data-rec-grid></div>
    </div>
  </section>
</template>

<!-- Template: Newsletter (basic) -->
<template id="tpl-newsletter">
  <section class="section newsletter" data-newsletter>
    <div class="container">
      <div class="newsletter__inner">
        <div class="newsletter__kicker" data-newsletter-kicker></div>
        <h2 class="newsletter__title" data-newsletter-title></h2>
        <p class="newsletter__text" data-newsletter-text></p>

        <form class="newsletter__form" action="#" method="post">
          <input class="newsletter__input" type="email" placeholder="E-Mail Adresse" required>
          <button class="newsletter__btn" type="submit">Anmelden</button>
        </form>
      </div>
    </div>
  </section>
</template>
