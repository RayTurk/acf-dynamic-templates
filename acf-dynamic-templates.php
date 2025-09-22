<?php
/**
 * Plugin Name:       ACF Dynamic Templates
 * Plugin URI:        https://example.com/
 * Description:       Provides pre-built ACF field groups and matching display templates to quickly add professional sections to your website.
 * Version:           1.0.0
 * Author:            Jules
 * Author URI:        https://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       acf-dt
 * Domain Path:       /languages
 *
 * Requires Plugins:  advanced-custom-fields-pro
 * Requires at least: 5.8
 * Requires PHP:      7.4
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants.
define( 'ACFDT_VERSION', '1.0.0' );
define( 'ACFDT_PLUGIN_DIR', wp_normalize_path( plugin_dir_path( __FILE__ ) ) );
define( 'ACFDT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ACFDT_PLUGIN_FILE', __FILE__ );

/**
 * The main plugin class.
 */
final class ACF_Dynamic_Templates {

    /**
     * The single instance of the class.
     *
     * @var ACF_Dynamic_Templates
     */
    protected static $_instance = null;

    /**
     * Main ACF_Dynamic_Templates Instance.
     *
     * Ensures only one instance of ACF_Dynamic_Templates is loaded or can be loaded.
     *
     * @static
     * @return ACF_Dynamic_Templates - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );
    }

    /**
     * Checks if ACF Pro is active.
     */
    public function is_acf_pro_active() {
        if ( ! class_exists( 'acf' ) ) {
            add_action( 'admin_notices', [ $this, 'notice_missing_acf' ] );
            return false;
        }
        return true;
    }

    /**
     * Renders an admin notice if ACF Pro is not active.
     */
    public function notice_missing_acf() {
        echo '<div class="notice notice-error"><p>';
        echo __( 'ACF Dynamic Templates requires Advanced Custom Fields Pro to be installed and activated.', 'acf-dt' );
        echo '</p></div>';
    }

    /**
     * On plugins_loaded, check for ACF Pro and initialize the plugin.
     */
    public function on_plugins_loaded() {
        if ( ! $this->is_acf_pro_active() ) {
            return;
        }

        $this->includes();
        $this->init();
    }

    /**
     * Include required files.
     */
    private function includes() {
        require_once ACFDT_PLUGIN_DIR . 'includes/class-admin-ui.php';
        require_once ACFDT_PLUGIN_DIR . 'includes/class-field-builder.php';
        require_once ACFDT_PLUGIN_DIR . 'includes/class-template-loader.php';
        require_once ACFDT_PLUGIN_DIR . 'includes/class-shortcode-handler.php';
    }

    /**
     * Initialize the plugin.
     */
    private function init() {
        // Instantiate classes
        $template_loader = new ACFDT_Template_Loader();
        $field_builder = new ACFDT_Field_Builder();
        $admin_ui = new ACFDT_Admin_UI( $template_loader, $field_builder );
        $shortcode_handler = new ACFDT_Shortcode_Handler( $template_loader );

        // Register hooks
        add_action( 'init', [ $shortcode_handler, 'register_shortcodes' ] );
        add_action( 'admin_init', [ $admin_ui, 'register_settings' ] );
        add_action( 'admin_menu', [ $admin_ui, 'add_admin_menu' ] );
        add_action( 'admin_enqueue_scripts', [ $admin_ui, 'enqueue_admin_assets' ] );
        add_action( 'wp_ajax_acfdt_import_template', [ $admin_ui, 'ajax_import_template' ] );

        // Frontend assets
        add_action( 'wp_enqueue_scripts', [ $template_loader, 'enqueue_frontend_assets' ] );
    }
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function acf_dynamic_templates() {
    return ACF_Dynamic_Templates::instance();
}

// Let's get this party started.
acf_dynamic_templates();