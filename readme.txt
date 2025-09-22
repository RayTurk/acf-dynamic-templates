=== ACF Dynamic Templates ===
Contributors: jules
Tags: acf, advanced custom fields, template, shortcode, layout, grid, slider, portfolio, testimonials, team
Requires at least: 5.8
Tested up to: 6.2
Stable tag: 1.0.0
Requires PHP: 7.4
Requires Plugins: advanced-custom-fields-pro
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A powerful plugin that provides pre-built ACF field groups with matching display templates to quickly add professional sections to your website.

== Description ==

ACF Dynamic Templates allows you to supercharge your website development with a library of ready-to-use templates for common sections like "Team Members", "Testimonials", and "Portfolios". Simply import the ACF field group with a single click, add your content, and display it anywhere on your site using a simple shortcode.

This plugin is perfect for:
*   **Developers** who want to save time on building custom sections.
*   **Designers** who want to offer their clients beautifully designed, pre-built components.
*   **Site Builders** who want to add professional layouts without writing any code.

**Features:**
*   **One-Click Import:** Import complex ACF field groups instantly from the admin interface.
*   **Multiple Layouts:** Each template comes with multiple layouts (e.g., grid, list, slider).
*   **Shortcode System:** Easily display templates anywhere with a flexible shortcode `[acf_template]`.
*   **Responsive Design:** All templates are fully responsive and look great on all devices.
*   **Bootstrap 5 Support:** Optionally enable Bootstrap 5 from a CDN for enhanced styling. The templates also work without Bootstrap.
*   **Customizable:** Use shortcode parameters to customize layouts, and add your own CSS classes for further styling.

**Available Templates:**
*   **Team Members:** Display your team with photos, positions, bios, and social media links. Layouts: Grid, List.
*   **Testimonials:** Showcase customer feedback in a clean grid or an animated slider. Layouts: Grid, Slider.
*   **Portfolio:** Present your work in a beautiful grid or a masonry-style layout. Layouts: Grid, Masonry.

== Installation ==

1.  Upload the `acf-dynamic-templates` folder to the `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Make sure you have **Advanced Custom Fields Pro** installed and activated.
4.  Navigate to the "ACF Templates" menu in your WordPress dashboard.
5.  Click the "Import Field Group" button for the template you want to use.
6.  Edit a page or post, and you will see the new ACF fields available.
7.  Add your content in the fields.
8.  Use the shortcode to display the template on your page (e.g., `[acf_template type="team-members"]`).

== Shortcode Documentation ==

The basic shortcode is `[acf_template]`.

**Parameters:**

*   `type` (required): The ID of the template you want to display.
    *   Example: `[acf_template type="team-members"]`
    *   Available types: `team-members`, `testimonials`, `portfolio`
*   `layout` (optional): The layout to use for the template. Defaults to the first available layout (usually 'grid').
    *   Example: `[acf_template type="team-members" layout="list"]`
*   `columns` (optional): For grid-based layouts, the number of columns to display.
    *   Example: `[acf_template type="portfolio" layout="grid" columns="4"]`
*   `post_id` (optional): The ID of the post or page from which to pull the ACF data. Defaults to the current post.
    *   Example: `[acf_template type="team-members" post_id="123"]`
*   `class` (optional): Add custom CSS classes to the template container.
    *   Example: `[acf_template type="testimonials" class="my-custom-class"]`
*   `id` (optional): Add a custom CSS ID to the template container.
    *   Example: `[acf_template type="testimonials" id="my-custom-id"]`


== Changelog ==

= 1.0.0 =
*   Initial release.

== Screenshots ==

1. The admin interface showing the available templates.
2. The settings page for enabling Bootstrap.
3. Example of the "Team Members" grid layout.
4. Example of the "Testimonials" slider layout.
