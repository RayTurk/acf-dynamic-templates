<?php

/**
 * Portfolio Grid Template
 * File: templates/portfolio/display-grid.php
 */
?>

<?php
$portfolio_items = get_field('portfolio_items');

if (! $portfolio_items) {
  return;
}

$columns = $args['columns'] ?? 3;
$col_class = 'col-md-' . (12 / $columns);
?>

<div class="acfdt-portfolio-grid">
  <div class="<?php echo get_option('acfdt_use_bootstrap') ? 'container' : 'acfdt-container'; ?>">
    <div class="row">
      <?php foreach ($portfolio_items as $item) : ?>
        <div class="<?php echo esc_attr($col_class); ?>">
          <div class="acfdt-portfolio-item">
            <?php if ($item['featured_image']) : ?>
              <img src="<?php echo esc_url($item['featured_image']['sizes']['large']); ?>"
                alt="<?php echo esc_attr($item['title']); ?>"
                class="img-fluid">
            <?php endif; ?>

            <div class="acfdt-portfolio-overlay">
              <h3><?php echo esc_html($item['title']); ?></h3>
              <?php if ($item['category']) : ?>
                <span class="category"><?php echo esc_html($item['category']); ?></span>
              <?php endif; ?>

              <?php if ($item['project_url']) : ?>
                <a href="<?php echo esc_url($item['project_url']); ?>"
                  class="btn btn-light btn-sm mt-2"
                  target="_blank">
                  View Project
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>