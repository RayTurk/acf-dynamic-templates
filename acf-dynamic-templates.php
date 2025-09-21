/**
* Plugin Name: ACF Dynamic Templates
* Plugin URI: https://your-domain.com
* Description: Pre-built ACF field groups with beautiful display templates
* Version: 1.0.0
* Author: Your Name
* License: GPL v2 or later
* Text Domain: acf-dt
*/

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
exit;
}

// Define plugin constants
define( 'ACFDT_VERSION', '1.0.0' );
define( 'ACFDT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ACFDT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Check if ACF Pro is active
function acfdt_check_dependencies() {
if ( ! class_exists( 'ACF' ) ) {
add_action( 'admin_notices', 'acfdt_missing_acf_notice' );
return false;
}
return true;
}

function acfdt_missing_acf_notice() {
echo '<div class="notice notice-error">
  <p>' .
    __( 'ACF Dynamic Templates requires Advanced Custom Fields Pro to be installed and activated.', 'acf-dt' ) .
    '</p>
</div>';
}

// Initialize plugin
function acfdt_init() {
if ( ! acfdt_check_dependencies() ) {
return;
}

// Load plugin classes
require_once ACFDT_PLUGIN_DIR . 'includes/class-template-loader.php';
require_once ACFDT_PLUGIN_DIR . 'includes/class-field-builder.php';
require_once ACFDT_PLUGIN_DIR . 'includes/class-admin-ui.php';
require_once ACFDT_PLUGIN_DIR . 'includes/class-shortcode-handler.php';

// Initialize components
new ACFDT_Template_Loader();
new ACFDT_Admin_UI();
new ACFDT_Shortcode_Handler();
}
add_action( 'plugins_loaded', 'acfdt_init' );