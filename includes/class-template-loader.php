<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class ACFDT_Template_Loader
 *
 * Handles scanning, loading, and rendering of templates.
 */
class ACFDT_Template_Loader {

    /**
     * Array of all available templates.
     *
     * @var array
     */
    private $templates = [];

    /**
     * The post ID where a shortcode is found.
     *
     * @var int|null
     */
    private static $shortcode_post_id = null;

    /**
     * ACFDT_Template_Loader constructor.
     */
    public function __construct() {
        $this->load_available_templates();
    }

    /**
     * Scan the templates directory and load available templates.
     * Caches the result in a transient for performance.
     */
    public function load_available_templates() {
        $cached_templates = get_transient( 'acfdt_available_templates' );
        if ( false !== $cached_templates ) {
            $this->templates = $cached_templates;
            return;
        }

        $template_dirs = glob( ACFDT_PLUGIN_DIR . 'templates/*', GLOB_ONLYDIR );
        $templates = [];

        foreach ( $template_dirs as $dir ) {
            $template_id = basename( $dir );
            $config_file = $dir . '/fields.json';

            if ( ! file_exists( $config_file ) ) {
                continue;
            }

            $config = json_decode( file_get_contents( $config_file ), true );
            if ( empty( $config ) || ! isset( $config['name'] ) ) {
                continue;
            }

            $layouts = $this->get_template_layouts( $dir );
            if ( empty( $layouts ) ) {
                continue;
            }

            $templates[ $template_id ] = [
                'id'          => $template_id,
                'name'        => $config['name'],
                'description' => $config['description'] ?? '',
                'path'        => $dir,
                'layouts'     => array_keys( $layouts ),
                'layout_files'=> $layouts,
            ];
        }

        set_transient( 'acfdt_available_templates', $templates, DAY_IN_SECONDS );
        $this->templates = $templates;
    }

    /**
     * Get available display layouts for a template.
     *
     * @param string $dir The directory of the template.
     * @return array
     */
    private function get_template_layouts( $dir ) {
        $layouts = [];
        $layout_files = glob( $dir . '/display-*.php' );

        foreach ( $layout_files as $file ) {
            $layout_name = str_replace( [ 'display-', '.php' ], '', basename( $file ) );
            $layouts[ $layout_name ] = $file;
        }

        return $layouts;
    }

    /**
     * Public getter for the templates array.
     *
     * @return array
     */
    public function get_templates() {
        return $this->templates;
    }

    /**
     * Render a specific template with a given layout.
     *
     * @param string $template_id The ID of the template to render.
     * @param string $layout The layout to use.
     * @param array  $data The data to pass to the template file.
     * @return string The rendered HTML.
     */
    public function render_template( $template_id, $layout, $data ) {
        if ( ! isset( $this->templates[ $template_id ] ) ) {
            return '<p>' . __( 'ACF Template not found.', 'acf-dt' ) . '</p>';
        }

        $template = $this->templates[ $template_id ];

        // Default to the first available layout if the specified one doesn't exist.
        if ( ! in_array( $layout, $template['layouts'] ) ) {
            $layout = ! empty( $template['layouts'] ) ? $template['layouts'][0] : null;
        }

        if ( ! $layout || ! isset( $template['layout_files'][ $layout ] ) ) {
            return '<p>' . __( 'ACF Template layout not found.', 'acf-dt' ) . '</p>';
        }

        $layout_file = $template['layout_files'][ $layout ];

        if ( ! file_exists( $layout_file ) ) {
            return '<p>' . __( 'ACF Template layout file is missing.', 'acf-dt' ) . '</p>';
        }

        ob_start();

        // Expose the data to the template file.
        extract( $data );

        include $layout_file;

        return ob_get_clean();
    }

    /**
     * Enqueue frontend assets only when the shortcode is used.
     * This is hooked to 'wp_enqueue_scripts'.
     */
    public function enqueue_frontend_assets() {
        global $post;

        if ( ! is_a( $post, 'WP_Post' ) || ! has_shortcode( $post->post_content, 'acf_template' ) ) {
            return;
        }

        // General frontend styles for all templates
        wp_enqueue_style(
            'acfdt-frontend',
            ACFDT_PLUGIN_URL . 'assets/css/frontend.css',
            [],
            ACFDT_VERSION
        );

        // Enqueue Bootstrap if enabled
        if ( get_option( 'acf_dt_bootstrap_enabled', false ) ) {
            wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' );
            wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', [], null, true );
        }

        // Find which templates are used and enqueue their specific assets
        preg_match_all( '/' . get_shortcode_regex() . '/s', $post->post_content, $matches, PREG_SET_ORDER );

        if ( ! empty( $matches ) ) {
            foreach ( $matches as $shortcode ) {
                if ( 'acf_template' === $shortcode[2] ) {
                    $attrs = shortcode_parse_atts( $shortcode[3] );
                    if ( ! empty( $attrs['type'] ) ) {
                        $template_id = $attrs['type'];
                        if ( isset( $this->templates[ $template_id ] ) ) {
                            $style_file = $this->templates[ $template_id ]['path'] . '/style.css';
                            if ( file_exists( $style_file ) ) {
                                wp_enqueue_style(
                                    'acfdt-template-' . $template_id,
                                    str_replace( ACFDT_PLUGIN_DIR, ACFDT_PLUGIN_URL, $style_file ),
                                    [ 'acfdt-frontend' ],
                                    ACFDT_VERSION
                                );
                            }
                        }
                    }
                }
            }
        }
    }
}