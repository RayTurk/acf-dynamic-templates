<?php
/**
 * Testimonials - Slider Layout
 *
 * @var array $fields The fields from get_fields().
 * @var array $atts The shortcode attributes.
 */

if ( empty( $fields['testimonials'] ) ) {
    return;
}

$use_bootstrap = get_option( 'acf_dt_bootstrap_enabled', false );
$slider_id = 'acfdt-testimonial-slider-' . uniqid();

if ( $use_bootstrap ) : ?>

<div id="<?php echo esc_attr( $slider_id ); ?>" class="carousel slide acfdt-testimonials acfdt-testimonials--slider" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php foreach ( $fields['testimonials'] as $index => $testimonial ) : ?>
            <div class="carousel-item <?php echo ( $index === 0 ) ? 'active' : ''; ?>">
                <div class="acfdt-testimonial-slide">
                    <?php if ( ! empty( $testimonial['content'] ) ) : ?>
                        <blockquote class="acfdt-testimonial-slide__content">
                            <?php echo wp_kses_post( $testimonial['content'] ); ?>
                        </blockquote>
                    <?php endif; ?>
                    <div class="acfdt-testimonial-slide__author">
                        <?php if ( ! empty( $testimonial['author_photo'] ) ) : ?>
                            <div class="acfdt-testimonial-slide__author-photo">
                                <?php echo wp_get_attachment_image( $testimonial['author_photo']['ID'], 'thumbnail' ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="acfdt-testimonial-slide__author-info">
                             <?php if ( ! empty( $testimonial['author_name'] ) ) : ?>
                                <p class="acfdt-testimonial-slide__author-name"><?php echo esc_html( $testimonial['author_name'] ); ?></p>
                            <?php endif; ?>
                            <?php if ( ! empty( $testimonial['author_position'] ) || ! empty( $testimonial['author_company'] ) ) : ?>
                                <p class="acfdt-testimonial-slide__author-meta">
                                    <?php echo esc_html( $testimonial['author_position'] ); ?><?php if ( ! empty( $testimonial['author_position'] ) && ! empty( $testimonial['author_company'] ) ) : ?>,<?php endif; ?> <?php echo esc_html( $testimonial['author_company'] ); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo esc_attr( $slider_id ); ?>" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden"><?php _e( 'Previous', 'acf-dt' ); ?></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#<?php echo esc_attr( $slider_id ); ?>" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden"><?php _e( 'Next', 'acf-dt' ); ?></span>
    </button>
</div>

<?php else : // Fallback for non-bootstrap slider ?>

<div id="<?php echo esc_attr( $slider_id ); ?>" class="acfdt-testimonials acfdt-testimonials--slider-fallback">
    <?php foreach ( $fields['testimonials'] as $index => $testimonial ) : ?>
        <div class="acfdt-testimonial-slide-fallback <?php echo ( $index === 0 ) ? 'active' : ''; ?>">
             <div class="acfdt-testimonial-slide">
                <?php if ( ! empty( $testimonial['content'] ) ) : ?>
                    <blockquote class="acfdt-testimonial-slide__content">
                        <?php echo wp_kses_post( $testimonial['content'] ); ?>
                    </blockquote>
                <?php endif; ?>
                <div class="acfdt-testimonial-slide__author">
                    <?php if ( ! empty( $testimonial['author_photo'] ) ) : ?>
                        <div class="acfdt-testimonial-slide__author-photo">
                            <?php echo wp_get_attachment_image( $testimonial['author_photo']['ID'], 'thumbnail' ); ?>
                        </div>
                    <?php endif; ?>
                    <div class="acfdt-testimonial-slide__author-info">
                            <?php if ( ! empty( $testimonial['author_name'] ) ) : ?>
                            <p class="acfdt-testimonial-slide__author-name"><?php echo esc_html( $testimonial['author_name'] ); ?></p>
                        <?php endif; ?>
                        <?php if ( ! empty( $testimonial['author_position'] ) || ! empty( $testimonial['author_company'] ) ) : ?>
                            <p class="acfdt-testimonial-slide__author-meta">
                                <?php echo esc_html( $testimonial['author_position'] ); ?><?php if ( ! empty( $testimonial['author_position'] ) && ! empty( $testimonial['author_company'] ) ) : ?>,<?php endif; ?> <?php echo esc_html( $testimonial['author_company'] ); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>