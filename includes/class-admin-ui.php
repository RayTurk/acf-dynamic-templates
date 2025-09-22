<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class ACFDT_Admin_UI
 *
 * Handles the admin interface for the plugin.
 */
class ACFDT_Admin_UI {

    /**
     * The template loader instance.
     *
     * @var ACFDT_Template_Loader
     */
    private $template_loader;

    /**
     * The field builder instance.
     *
     * @var ACFDT_Field_Builder
     */
    private $field_builder;

    /**
     * ACFDT_Admin_UI constructor.
     *
     * @param ACFDT_Template_Loader $template_loader
     * @param ACFDT_Field_Builder   $field_builder
     */
    public function __construct( $template_loader, $field_builder ) {
        $this->template_loader = $template_loader;
        $this->field_builder   = $field_builder;
    }

    /**
     * Add the admin menu pages.
     */
    public function add_admin_menu() {
        add_menu_page(
            __( 'ACF Dynamic Templates', 'acf-dt' ),
            __( 'ACF Templates', 'acf-dt' ),
            'manage_options',
            'acf-dynamic-templates',
            [ $this, 'render_admin_page' ],
            'dashicons-layout',
            81
        );

        add_submenu_page(
            'acf-dynamic-templates',
            __( 'Settings', 'acf-dt' ),
            __( 'Settings', 'acf-dt' ),
            'manage_options',
            'acf-dt-settings',
            [ $this, 'render_settings_page' ]
        );
    }

    /**
     * Render the main admin page.
     */
    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <p><?php _e( 'Browse the available templates and import them into your ACF setup with a single click.', 'acf-dt' ); ?></p>

            <div id="acfdt-templates-grid" class="acfdt-templates-grid">
                <?php
                $templates = $this->template_loader->get_templates();
                if ( ! empty( $templates ) ) {
                    foreach ( $templates as $template_id => $template_data ) {
                        $this->render_template_card( $template_id, $template_data );
                    }
                } else {
                    echo '<p>' . __( 'No templates found. Make sure your templates are in the /templates directory.', 'acf-dt' ) . '</p>';
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Renders a single template card for the admin UI.
     *
     * @param string $id The template ID.
     * @param array  $data The template data.
     */
    public function render_template_card( $id, $data ) {
        $field_group_exists = $this->field_builder->field_group_exists( $id );
        ?>
        <div class="acfdt-template-card">
            <h3 class="acfdt-template-card__title"><?php echo esc_html( $data['name'] ); ?></h3>
            <p class="acfdt-template-card__description"><?php echo esc_html( $data['description'] ); ?></p>
            <div class="acfdt-template-card__layouts">
                <strong><?php _e( 'Layouts:', 'acf-dt' ); ?></strong>
                <span><?php echo esc_html( implode( ', ', $data['layouts'] ) ); ?></span>
            </div>
            <div class="acfdt-template-card__actions">
                <button
                    class="button acfdt-import-btn <?php echo $field_group_exists ? 'button-secondary' : 'button-primary'; ?>"
                    data-template-id="<?php echo esc_attr( $id ); ?>"
                    <?php disabled( $field_group_exists, true ); ?>>
                    <?php $field_group_exists ? _e( 'Imported', 'acf-dt' ) : _e( 'Import Field Group', 'acf-dt' ); ?>
                </button>
                <div class="acfdt-import-status"></div>
            </div>
            <div class="acfdt-shortcode-preview">
                <strong><?php _e( 'Shortcode:', 'acf-dt' ); ?></strong>
                <code>[acf_template type="<?php echo esc_attr( $id ); ?>"]</code>
                <button class="button button-small acfdt-copy-shortcode"><?php _e( 'Copy', 'acf-dt' ); ?></button>
            </div>
        </div>
        <?php
    }


    /**
     * Render the settings page.
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'acf_dt_settings' );
                do_settings_sections( 'acf_dt_settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Enqueue admin assets.
     */
    public function enqueue_admin_assets( $hook ) {
        if ( 'toplevel_page_acf-dynamic-templates' !== $hook && 'acf-templates_page_acf-dt-settings' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'acfdt-admin-css',
            ACFDT_PLUGIN_URL . 'assets/css/admin.css',
            [],
            ACFDT_VERSION
        );

        wp_enqueue_script(
            'acfdt-admin-js',
            ACFDT_PLUGIN_URL . 'assets/js/admin.js',
            [ 'jquery' ],
            ACFDT_VERSION,
            true
        );

        wp_localize_script( 'acfdt-admin-js', 'acfdt_ajax', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'acfdt-import-nonce' ),
            'importing_text' => __( 'Importing...', 'acf-dt' ),
            'imported_text' => __( 'Imported', 'acf-dt' ),
        ] );
    }

    /**
     * Handle AJAX template import.
     */
    public function ajax_import_template() {
        check_ajax_referer( 'acfdt-import-nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => __( 'You do not have permission to do this.', 'acf-dt' ) ], 403 );
        }

        $template_id = isset( $_POST['template_id'] ) ? sanitize_text_field( $_POST['template_id'] ) : '';

        if ( empty( $template_id ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid template ID.', 'acf-dt' ) ], 400 );
        }

        $result = $this->field_builder->import_template( $template_id );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( [ 'message' => $result->get_error_message() ] );
        }

        wp_send_json_success( [ 'message' => __( 'Template imported successfully!', 'acf-dt' ) ] );
    }

    /**
     * Register plugin settings.
     */
    public function register_settings() {
        register_setting( 'acf_dt_settings', 'acf_dt_bootstrap_enabled' );

        add_settings_section(
            'acf_dt_general_options_section',
            __( 'General Settings', 'acf-dt' ),
            null,
            'acf_dt_settings'
        );

        add_settings_field(
            'acf_dt_bootstrap_enabled',
            __( 'Enable Bootstrap 5', 'acf-dt' ),
            [ $this, 'render_bootstrap_setting_field' ],
            'acf_dt_settings',
            'acf_dt_general_options_section'
        );
    }

    /**
     * Render the Bootstrap setting field.
     */
    public function render_bootstrap_setting_field() {
        $option = get_option( 'acf_dt_bootstrap_enabled' );
        ?>
        <label for="acf_dt_bootstrap_enabled">
            <input type="checkbox" id="acf_dt_bootstrap_enabled" name="acf_dt_bootstrap_enabled" value="1" <?php checked( 1, $option ); ?>>
            <?php _e( 'Load Bootstrap 5 CSS and JS from CDN. Disable this if your theme already includes Bootstrap.', 'acf-dt' ); ?>
        </label>
        <?php
    }
}