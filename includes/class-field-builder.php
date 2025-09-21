<?php
class ACFDT_Field_Builder
{

  /**
   * Import a template's field group into ACF
   */
  public function import_template($template_name)
  {
    $template_dir = ACFDT_PLUGIN_DIR . 'templates/' . $template_name;
    $fields_file = $template_dir . '/fields.json';

    if (! file_exists($fields_file)) {
      return [
        'success' => false,
        'message' => __('Template fields not found.', 'acf-dt')
      ];
    }

    $field_group = json_decode(file_get_contents($fields_file), true);

    // Check if field group already exists
    if ($this->field_group_exists($field_group['key'])) {
      return [
        'success' => false,
        'message' => __('Field group already imported.', 'acf-dt')
      ];
    }

    // Register the field group with ACF
    acf_add_local_field_group($field_group);

    // Save to database for persistence
    $this->save_field_group($field_group);

    return [
      'success' => true,
      'message' => sprintf(
        __('%s template imported successfully!', 'acf-dt'),
        ucwords(str_replace('-', ' ', $template_name))
      )
    ];
  }

  /**
   * Check if field group already exists
   */
  private function field_group_exists($key)
  {
    global $wpdb;

    $exists = $wpdb->get_var($wpdb->prepare(
      "SELECT ID FROM {$wpdb->posts}
            WHERE post_type = 'acf-field-group'
            AND post_name = %s",
      $key
    ));

    return ! empty($exists);
  }

  /**
   * Save field group to database
   */
  private function save_field_group($field_group)
  {
    // Create the field group post
    $post_id = wp_insert_post([
      'post_title'    => $field_group['title'],
      'post_name'     => $field_group['key'],
      'post_type'     => 'acf-field-group',
      'post_status'   => 'publish',
      'post_content'  => serialize($field_group)
    ]);

    if (! is_wp_error($post_id)) {
      // Save the field group meta
      foreach ($field_group as $key => $value) {
        if ($key !== 'fields') {
          update_post_meta($post_id, $key, $value);
        }
      }

      // Save fields
      $this->save_fields($field_group['fields'], $post_id);
    }

    return $post_id;
  }

  /**
   * Save individual fields
   */
  private function save_fields($fields, $parent_id, $parent_key = '')
  {
    foreach ($fields as $order => $field) {
      $field_post_id = wp_insert_post([
        'post_title'    => $field['label'],
        'post_name'     => $field['key'],
        'post_type'     => 'acf-field',
        'post_status'   => 'publish',
        'post_parent'   => $parent_id,
        'menu_order'    => $order,
        'post_content'  => serialize($field)
      ]);

      if (! is_wp_error($field_post_id)) {
        foreach ($field as $key => $value) {
          if ($key !== 'sub_fields') {
            update_post_meta($field_post_id, $key, $value);
          }
        }

        // Handle sub-fields for repeaters and groups
        if (isset($field['sub_fields']) && is_array($field['sub_fields'])) {
          $this->save_fields($field['sub_fields'], $field_post_id, $field['key']);
        }
      }
    }
  }
}
