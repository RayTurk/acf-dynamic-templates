<?php
/**
 * Team Members - List Layout
 *
 * @var array $fields The fields from get_fields().
 * @var array $atts The shortcode attributes.
 */

if ( empty( $fields['team_members'] ) ) {
    return;
}

$use_bootstrap = get_option( 'acf_dt_bootstrap_enabled', false );
$row_class = $use_bootstrap ? 'row' : 'acfdt-row';
$col_class_img = $use_bootstrap ? 'col-md-3' : 'acfdt-col acfdt-col-3';
$col_class_content = $use_bootstrap ? 'col-md-9' : 'acfdt-col acfdt-col-9';

?>
<div class="acfdt-team-members acfdt-team-members--list">
    <?php foreach ( $fields['team_members'] as $member ) : ?>
        <div class="acfdt-team-member-item">
            <div class="<?php echo esc_attr( $row_class ); ?>">
                <div class="<?php echo esc_attr( $col_class_img ); ?>">
                    <?php if ( ! empty( $member['photo'] ) ) : ?>
                        <div class="acfdt-team-member-item__photo">
                            <?php echo wp_get_attachment_image( $member['photo']['ID'], 'medium', false, [ 'class' => 'acfdt-img-fluid' ] ); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="<?php echo esc_attr( $col_class_content ); ?>">
                    <div class="acfdt-team-member-item__content">
                        <?php if ( ! empty( $member['name'] ) ) : ?>
                            <h3 class="acfdt-team-member-item__name"><?php echo esc_html( $member['name'] ); ?></h3>
                        <?php endif; ?>
                        <?php if ( ! empty( $member['position'] ) ) : ?>
                            <p class="acfdt-team-member-item__position"><?php echo esc_html( $member['position'] ); ?></p>
                        <?php endif; ?>
                        <?php if ( ! empty( $member['bio'] ) ) : ?>
                            <div class="acfdt-team-member-item__bio"><?php echo wp_kses_post( $member['bio'] ); ?></div>
                        <?php endif; ?>
                        <div class="acfdt-team-member-item__social">
                             <?php if ( ! empty( $member['email'] ) ) : ?>
                                <a href="mailto:<?php echo esc_attr( $member['email'] ); ?>" class="acfdt-social-link acfdt-social-link--email" aria-label="<?php esc_attr_e( 'Email', 'acf-dt' ); ?>">
                                    <span class="dashicons dashicons-email-alt"></span>
                                </a>
                            <?php endif; ?>
                            <?php if ( ! empty( $member['linkedin'] ) ) : ?>
                                <a href="<?php echo esc_url( $member['linkedin'] ); ?>" target="_blank" rel="noopener noreferrer" class="acfdt-social-link acfdt-social-link--linkedin" aria-label="<?php esc_attr_e( 'LinkedIn', 'acf-dt' ); ?>">
                                    <span class="dashicons dashicons-linkedin"></span>
                                a>
                            <?php endif; ?>
                            <?php if ( ! empty( $member['twitter'] ) ) : ?>
                                <a href="<?php echo esc_url( $member['twitter'] ); ?>" target="_blank" rel="noopener noreferrer" class="acfdt-social-link acfdt-social-link--twitter" aria-label="<?php esc_attr_e( 'Twitter', 'acf-dt' ); ?>">
                                   <span class="dashicons dashicons-twitter"></span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
