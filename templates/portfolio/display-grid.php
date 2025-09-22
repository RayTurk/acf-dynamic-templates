<?php
/**
 * Portfolio - Grid Layout
 *
 * @var array $fields The fields from get_fields().
 * @var array $atts The shortcode attributes.
 */

if ( empty( $fields['portfolio_items'] ) ) {
    return;
}

$columns = ! empty( $atts['columns'] ) ? absint( $atts['columns'] ) : 3;
$use_bootstrap = get_option( 'acf_dt_bootstrap_enabled', false );
$row_class = $use_bootstrap ? 'row' : 'acfdt-row';
$col_class = $use_bootstrap ? 'col-md-' . ( 12 / $columns ) : 'acfdt-col acfdt-col-' . ( 12 / $columns );

?>
<div class="acfdt-portfolio acfdt-portfolio--grid">
    <div class="<?php echo esc_attr( $row_class ); ?>">
        <?php foreach ( $fields['portfolio_items'] as $item ) : ?>
            <div class="<?php echo esc_attr( $col_class ); ?>">
                <div class="acfdt-portfolio-item">
                    <div class="acfdt-portfolio-item__image">
                        <?php if ( ! empty( $item['featured_image'] ) ) : ?>
                            <a href="<?php echo esc_url( $item['project_url'] ?: '#' ); ?>" <?php if( $item['project_url'] ) echo 'target="_blank" rel="noopener noreferrer"'; ?>>
                                <?php echo wp_get_attachment_image( $item['featured_image']['ID'], 'large' ); ?>
                            </a>
                        <?php endif; ?>
                        <div class="acfdt-portfolio-item__overlay">
                             <div class="acfdt-portfolio-item__overlay-content">
                                <?php if ( ! empty( $item['title'] ) ) : ?>
                                    <h3 class="acfdt-portfolio-item__title"><?php echo esc_html( $item['title'] ); ?></h3>
                                <?php endif; ?>
                                <?php if ( ! empty( $item['category'] ) ) : ?>
                                    <div class="acfdt-portfolio-item__categories">
                                        <?php if( is_array($item['category']) ): ?>
                                            <?php foreach( $item['category'] as $category ): ?>
                                                <span class="acfdt-portfolio-item__category"><?php echo esc_html( $category ); ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="acfdt-portfolio-item__category"><?php echo esc_html( $item['category'] ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
