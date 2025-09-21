class ACFDT_Template_Loader {

private $templates = [];

public function __construct() {
$this->load_available_templates();
add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_assets' ] );
}

/**
* Scan and load available templates
*/
private function load_available_templates() {
$template_dirs = glob( ACFDT_PLUGIN_DIR . 'templates/*', GLOB_ONLYDIR );

foreach ( $template_dirs as $dir ) {
$template_name = basename( $dir );
$fields_file = $dir . '/fields.json';

if ( file_exists( $fields_file ) ) {
$this->templates[ $template_name ] = [
'path' => $dir,
'fields' => json_decode( file_get_contents( $fields_file ), true ),
'layouts' => $this->get_template_layouts( $dir )
];
}
}
}

/**
* Get available display layouts for a template
*/
private function get_template_layouts( $dir ) {
$layouts = [];
$layout_files = glob( $dir . '/display-*.php' );

foreach ( $layout_files as $file ) {
$layout_name = str_replace( ['display-', '.php'], '', basename( $file ) );
$layouts[ $layout_name ] = $file;
}

return $layouts;
}

/**
* Render a template
*/
public function render_template( $template_name, $layout = 'grid', $args = [] ) {
if ( ! isset( $this->templates[ $template_name ] ) ) {
return '';
}

$template = $this->templates[ $template_name ];
$layout_file = $template['layouts'][ $layout ] ?? reset( $template['layouts'] );

if ( ! file_exists( $layout_file ) ) {
return '';
}

// Start output buffering
ob_start();

// Make args available to template
extract( $args );

// Include template file
include $layout_file;

return ob_get_clean();
}

public function enqueue_frontend_assets() {
wp_enqueue_style(
'acfdt-frontend',
ACFDT_PLUGIN_URL . 'assets/css/frontend.css',
[],
ACFDT_VERSION
);

// Enqueue Bootstrap if enabled in settings
if ( get_option( 'acfdt_use_bootstrap', true ) ) {
wp_enqueue_style(
'bootstrap',
'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'
);
}
}
}