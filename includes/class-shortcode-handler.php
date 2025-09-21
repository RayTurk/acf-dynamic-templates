class ACFDT_Shortcode_Handler {

public function __construct() {
add_shortcode( 'acf_template', [ $this, 'render_shortcode' ] );
}

public function render_shortcode( $atts ) {
$atts = shortcode_atts( [
'type' => 'team-members',
'layout' => 'grid',
'columns' => 3,
'post_id' => get_the_ID(),
'class' => ''
], $atts );

$template_loader = new ACFDT_Template_Loader();

return $template_loader->render_template(
$atts['type'],
$atts['layout'],
$atts
);
}
}