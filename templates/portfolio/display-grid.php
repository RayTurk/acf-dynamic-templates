<?php

/**
 * Portfolio - Grid Layout
 *
 * This template is designed to display a single portfolio item.
 *
 * @var array $fields The fields from get_fields().
 * @var array $atts The shortcode attributes.
 */

// Use a field that should always be present for a portfolio item to check for data.
if (empty($fields) || ! isset($fields['title'])) {
  return;
}

$use_bootstrap = get_option('acf_dt_bootstrap_enabled', false);

// Define classes based on whether Bootstrap is enabled.
$container_class = 'acfdt-portfolio-single';
$item_class      = 'acfdt-portfolio-item';

if ($use_bootstrap) {
  $container_class .= ' container'; // Add Bootstrap container class.
  $item_class      .= ' card'; // Use Bootstrap card component for styling.
}

// The $fields array is the single item.
$item = $fields;

?>
<div class="<?php echo esc_attr($container_class); ?>">
  <div class="<?php echo esc_attr($item_class); ?>">
    <?php if (! empty($item['featured_image'])) : ?>
      <div class="acfdt-portfolio-item__image">
        <a href="<?php echo esc_url($item['project_url'] ?? '#'); ?>" <?php if (! empty($item['project_url'])) echo 'target="_blank" rel="noopener noreferrer"'; ?>>
          <?php echo wp_get_attachment_image($item['featured_image']['ID'], 'large', false, ['class' => $use_bootstrap ? 'card-img-top' : '']); ?>
        </a>
      </div>
    <?php endif; ?>

    <div class="acfdt-portfolio-item__content <?php if ($use_bootstrap) echo 'card-body'; ?>">
      <?php if (! empty($item['title'])) : ?>
        <h3 class="acfdt-portfolio-item__title <?php if ($use_bootstrap) echo 'card-title'; ?>"><?php echo esc_html($item['title']); ?></h3>
      <?php endif; ?>

      <?php if (! empty($item['description'])) : ?>
        <p class="acfdt-portfolio-item__description <?php if ($use_bootstrap) echo 'card-text'; ?>"><?php echo esc_html($item['description']); ?></p>
      <?php endif; ?>

      <?php if (! empty($item['category'])) : ?>
        <div class="acfdt-portfolio-item__categories">
          <strong><?php _e('Categories:', 'acf-dt'); ?></strong>
          <?php
          $categories = is_array($item['category']) ? $item['category'] : [$item['category']];
          foreach ($categories as $category) :
          ?>
            <span class="badge bg-secondary"><?php echo esc_html($category); ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <?php if (! empty($item['project_url']) && $use_bootstrap) : ?>
      <div class="card-footer">
        <a href="<?php echo esc_url($item['project_url']); ?>" class="btn btn-primary" target="_blank" rel="noopener noreferrer"><?php _e('View Project', 'acf-dt'); ?></a>
      </div>
    <?php endif; ?>
  </div>
</div>