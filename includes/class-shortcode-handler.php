<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class ACFDT_Shortcode_Handler
 *
 * Registers and handles the [acf_template] shortcode.
 */
class ACFDT_Shortcode_Handler {

    /**
     * The template loader instance.
     *
     * @var ACFDT_Template_Loader
     */
    private $template_loader;

    /**
     * ACFDT_Shortcode_Handler constructor.
     *
     * @param ACFDT_Template_Loader $template_loader
     */
    public function __construct( $template_loader ) {
        $this->template_loader = $template_loader;
    }

    /**
     * Register the shortcodes.
     */
    public function register_shortcodes() {
        add_shortcode( 'acf_template', [ $this, 'render_shortcode' ] );
    }

    /**
     * Render the shortcode.
     *
     * @param array $atts The shortcode attributes.
     * @return string The rendered HTML.
     */
    public function render_shortcode( $atts ) {
        $atts = shortcode_atts( [
            'type'    => '',
            'layout'  => 'grid', // Default layout for lists
            'display' => 'list', // 'list' (repeater) or 'single'
            'post_id' => get_the_ID(),
            'class'   => '', // Custom CSS class
            'id'      => '', // Custom CSS ID
        ], $atts, 'acf_template' );

        // Validate attributes
        $template_id = sanitize_text_field( $atts['type'] );
        $layout = sanitize_text_field( $atts['layout'] );
        $display = sanitize_text_field( $atts['display'] );
        $post_id = absint( $atts['post_id'] );

        // If displaying a single item, we force the layout to 'single'.
        if ( 'single' === $display ) {
            $layout = 'single';
        }

        if ( empty( $template_id ) ) {
            return '<!-- ACF Dynamic Templates: "type" attribute is missing. -->';
        }

        // Get the fields from the specified post
        $fields = get_fields( $post_id );

        if ( ! $fields ) {
             return '<!-- ACF Dynamic Templates: No ACF data found for this post. -->';
        }

        // Prepare data for the template
        $data = [
            'fields' => $fields,
            'atts'   => $atts, // Pass attributes for further customization if needed
        ];

        $container_class = 'acfdt-template-container ' . esc_attr( $atts['class'] );
        $container_id = esc_attr( $atts['id'] );

        $output = '<div id="' . $container_id . '" class="' . $container_class . '">';
        $output .= $this->template_loader->render_template( $template_id, $layout, $data );
        $output .= '</div>';

        return $output;
    }
}