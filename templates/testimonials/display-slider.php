<?php

/**
 * Additional Template Files
 * File: templates/testimonials/display-slider.php
 */
?>

<?php
$testimonials = get_field('testimonials');

if (! $testimonials) {
  return;
}

$unique_id = 'testimonials-' . uniqid();
?>

<div class="acfdt-testimonials-slider" id="<?php echo esc_attr($unique_id); ?>">
  <div class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php foreach ($testimonials as $index => $testimonial) : ?>
        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
          <div class="acfdt-testimonial-card">
            <div class="acfdt-testimonial-content">
              <?php echo esc_html($testimonial['content']); ?>
            </div>
            <div class="acfdt-testimonial-author">
              <?php if ($testimonial['author_photo']) : ?>
                <img src="<?php echo esc_url($testimonial['author_photo']['sizes']['thumbnail']); ?>"
                  alt="<?php echo esc_attr($testimonial['author_name']); ?>">
              <?php endif; ?>
              <div class="acfdt-testimonial-author-info">
                <h4><?php echo esc_html($testimonial['author_name']); ?></h4>
                <?php if ($testimonial['author_company']) : ?>
                  <span class="company"><?php echo esc_html($testimonial['author_company']); ?></span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Carousel controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo esc_attr($unique_id); ?>" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#<?php echo esc_attr($unique_id); ?>" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</div>