<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class ACFDT_Field_Builder
 *
 * Handles importing and managing ACF field groups.
 */
class ACFDT_Field_Builder {

    /**
     * Import a template's field group into ACF.
     *
     * @param string $template_id The ID of the template to import.
     * @return bool|WP_Error True on success, WP_Error on failure.
     */
    public function import_template( $template_id ) {
        if ( ! function_exists( 'acf_import_field_group' ) ) {
            return new WP_Error( 'acf_not_found', __( 'ACF Pro functions not available.', 'acf-dt' ) );
        }

        $template_dir = ACFDT_PLUGIN_DIR . 'templates/' . $template_id;
        $config_file  = $template_dir . '/fields.json';

        if ( ! file_exists( $config_file ) ) {
            return new WP_Error( 'file_not_found', __( 'Template config file not found.', 'acf-dt' ) );
        }

        $json = file_get_contents( $config_file );
        $field_group = json_decode( $json, true );

        if ( empty( $field_group ) ) {
            return new WP_Error( 'invalid_json', __( 'Invalid JSON in template config file.', 'acf-dt' ) );
        }

        // Make sure the field group is inactive on import, so user can enable it manually.
        $field_group['active'] = false;

        // The acf_import_field_group function handles the entire import process.
        $result = acf_import_field_group( $field_group );

        if ( ! $result || is_wp_error( $result ) ) {
            return new WP_Error( 'import_failed', __( 'Failed to import field group.', 'acf-dt' ) );
        }

        // Clear the templates transient so it can be rebuilt with the new "imported" status.
        delete_transient( 'acfdt_available_templates' );

        return true;
    }

    /**
     * Check if a field group for a given template already exists.
     *
     * @param string $template_id The ID of the template.
     * @return bool True if the field group exists, false otherwise.
     */
    public function field_group_exists( $template_id ) {
        $key = 'group_' . $template_id;

        // acf_get_field_group is the proper way to check for a field group.
        $field_group = acf_get_field_group( $key );

        return ! empty( $field_group );
    }
}
