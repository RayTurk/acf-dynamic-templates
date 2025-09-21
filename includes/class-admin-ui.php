<?php

class ACFDT_Admin_UI {

public function __construct() {
add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
add_action( 'wp_ajax_acfdt_import_template', [ $this, 'ajax_import_template' ] );
}

public function add_admin_menu() {
add_menu_page(
__( 'ACF Templates', 'acf-dt' ),
__( 'ACF Templates', 'acf-dt' ),
'manage_options',
'acf-dynamic-templates',
[ $this, 'render_admin_page' ],
'dashicons-layout',
30
);
}

public function render_admin_page() {
$template_loader = new ACFDT_Template_Loader();
?>
<div class="wrap acfdt-admin-wrap">
  <h1><?php echo get_admin_page_title(); ?></h1>

  <div class="acfdt-templates-grid">
    <?php foreach ($template_loader->get_templates() as $name => $template) : ?>
      <div class="acfdt-template-card">
        <h3><?php echo ucwords(str_replace('-', ' ', $name)); ?></h3>
        <p><?php echo count($template['layouts']); ?> layouts available</p>

        <button class="button button-primary acfdt-import-btn"
          data-template="<?php echo esc_attr($name); ?>">
          Import Field Group
        </button>

        <div class="acfdt-shortcode-preview">
          <code>[acf_template type="<?php echo $name; ?>"]</code>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="acfdt-settings-section">
    <h2>Settings</h2>