<?php
if (!defined('ABSPATH')) exit;

$tree = otw_get_destinations_tree();
$theme_uri = get_template_directory_uri();
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
                <span class="worldmap__num"><?php echo $i + 1; ?></span>
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

    <!-- RIGHT: Map -->
    <div class="worldmap__stage">

      <div class="worldmap__map"
           style="background-image:url('<?php echo esc_url($theme_uri . '/assets/images/worldmap_beige.svg'); ?>');">
      </div>

    </div>

  </div>
</section>
