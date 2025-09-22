<?php
/**
 * Testimonials - Grid Layout
 *
 * @var array $fields The fields from get_fields().
 * @var array $atts The shortcode attributes.
 */

if ( empty( $fields['testimonials'] ) ) {
    return;
}

$columns = ! empty( $atts['columns'] ) ? absint( $atts['columns'] ) : 3;
$use_bootstrap = get_option( 'acf_dt_bootstrap_enabled', false );
$row_class = $use_bootstrap ? 'row' : 'acfdt-row';
$col_class = $use_bootstrap ? 'col-md-' . ( 12 / $columns ) : 'acfdt-col acfdt-col-' . ( 12 / $columns );

?>
<div class="acfdt-testimonials acfdt-testimonials--grid">
    <div class="<?php echo esc_attr( $row_class ); ?>">
        <?php foreach ( $fields['testimonials'] as $testimonial ) : ?>
            <div class="<?php echo esc_attr( $col_class ); ?>">
                <div class="acfdt-testimonial-card">
                    <?php if ( ! empty( $testimonial['rating'] ) ) : ?>
                        <div class="acfdt-testimonial-card__rating">
                            <?php for ( $i = 0; $i < 5; $i++ ) : ?>
                                <span class="dashicons dashicons-star-<?php echo ( $i < $testimonial['rating'] ) ? 'filled' : 'empty'; ?>"></span>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! empty( $testimonial['content'] ) ) : ?>
                        <blockquote class="acfdt-testimonial-card__content">
                            <?php echo wp_kses_post( $testimonial['content'] ); ?>
                        </blockquote>
                    <?php endif; ?>

                    <div class="acfdt-testimonial-card__author">
                        <?php if ( ! empty( $testimonial['author_photo'] ) ) : ?>
                            <div class="acfdt-testimonial-card__author-photo">
                                <?php echo wp_get_attachment_image( $testimonial['author_photo']['ID'], 'thumbnail' ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="acfdt-testimonial-card__author-info">
                            <?php if ( ! empty( $testimonial['author_name'] ) ) : ?>
                                <p class="acfdt-testimonial-card__author-name"><?php echo esc_html( $testimonial['author_name'] ); ?></p>
                            <?php endif; ?>
                            <?php if ( ! empty( $testimonial['author_position'] ) || ! empty( $testimonial['author_company'] ) ) : ?>
                                <p class="acfdt-testimonial-card__author-meta">
                                    <?php echo esc_html( $testimonial['author_position'] ); ?>
                                    <?php if ( ! empty( $testimonial['author_position'] ) && ! empty( $testimonial['author_company'] ) ) : ?>
                                        ,
                                    <?php endif; ?>
                                    <?php echo esc_html( $testimonial['author_company'] ); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>