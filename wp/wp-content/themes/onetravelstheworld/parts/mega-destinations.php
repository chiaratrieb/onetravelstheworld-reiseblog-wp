<?php
if (!defined('ABSPATH')) exit;

$tree = otw_get_destinations_tree();
?>

<div class="dropdown dropdown--mega" role="menu" aria-label="Reiseziele">
  <div class="mega">
    <div class="mega__head">Wohin möchtest du reisen?</div>

    <?php if (empty($tree)): ?>
      <div style="opacity:.75; font-size:18px; padding: 8px 0 6px;">
        Noch keine Reiseziele vorhanden.
      </div>
    <?php else: ?>

      <div class="mega__grid">

        <!-- LEFT: Kontinente -->
        <div class="mega__left">
          <?php foreach ($tree as $i => $node): ?>
            <button
              type="button"
              class="mega__tab <?php echo $i === 0 ? 'is-active' : ''; ?>"
              data-continent="<?php echo esc_attr($node['term']->slug); ?>">
              <?php echo esc_html($node['term']->name); ?>
            </button>
          <?php endforeach; ?>
        </div>

        <!-- RIGHT: Panels -->
        <div class="mega__right">
          <?php foreach ($tree as $i => $node):
            $continent = $node['term'];
            $countries = $node['countries'];
            if (empty($countries)) continue;
          ?>
            <div class="mega__panel <?php echo $i === 0 ? 'is-active' : ''; ?>"
                 data-panel="<?php echo esc_attr($continent->slug); ?>">

              <div class="mega__right-head">
                <span class="mega__right-title">
                  <?php echo esc_html($continent->name); ?>
                </span>
              </div>

              <div class="mega__countries">
                <?php foreach ($countries as $c): ?>
                  <a class="mega__country" href="<?php echo esc_url($c['url']); ?>">
                    <?php echo esc_html($c['term']->name); ?>
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
