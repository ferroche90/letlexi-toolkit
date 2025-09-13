=== LetLexi Toolkit ===
Contributors: Fernando Roche
Tags: elementor, legal, constitution, sections, navigation, acf, rest-api
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Universal section navigation system for any post type with ACF integration. Works with constitution articles, law documents, statutes, and any custom post type.

== Description ==

LetLexi Toolkit provides a comprehensive solution for displaying structured documents with interactive section navigation. Originally built for legal documents, it now works with any post type that has ACF sections, offering seamless integration with Elementor, Advanced Custom Fields (ACF), and WordPress REST API.

= Key Features =

* **Universal Post Type Support** - Works with any post type (posts, pages, custom post types)
* **Section Navigator Widget** - Interactive navigation through document sections
* **Extensible Widget System** - Auto-loading system for easy addition of new Elementor widgets
* **Elementor Integration** - Custom widgets with full control panel and comprehensive styling options
* **REST API Endpoints** - Dynamic section loading via AJAX
* **ACF Integration** - Works with ACF repeater fields for content management
* **Configurable Field Names** - Use custom ACF field names for different post types
* **Responsive Design** - Mobile-friendly two-pane layout
* **Accessibility** - ARIA support, keyboard navigation, screen reader friendly
* **Font Controls** - User-adjustable font scaling with persistence
* **Server-Side Rendering** - Works without JavaScript enabled
* **Developer-Friendly** - Extensive filters and hooks for customization

= Use Cases =

* Constitution articles and legal documents
* Law documents, statutes, and regulations
* Multi-section content with table of contents
* Interactive legal reference materials
* Academic papers with section navigation
* Technical documentation with sections
* Any content requiring structured section browsing
* Custom post types with ACF sections

== Installation ==

1. Upload the `letlexi-toolkit` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Ensure Advanced Custom Fields (ACF) is installed and activated
4. Create ACF repeater fields named 'sections' with subfields for content (or configure custom field names)
5. Add your post types to the supported list (or use the default supported types)
6. Use the Elementor widget to display your content

= Requirements =

* WordPress 5.0 or higher
* PHP 7.4 or higher
* Advanced Custom Fields (ACF) plugin
* Elementor plugin (required, for widget functionality)

== Frequently Asked Questions ==

= Does this work without Elementor? =

This plugin is designed specifically for Elementor and requires Elementor to be installed and activated.

= What ACF fields are required? =

The plugin expects a repeater field named 'sections' with these subfields:
* `section_title` - The section heading
* `section_content` - The main section content (WYSIWYG)
* `section_commentary` - Optional commentary (WYSIWYG)
* `section_status` - Status (active, repealed, superseded, pending)

= Can I customize the styling? =

Yes! The Elementor widget includes comprehensive styling controls in the Style tab, allowing you to customize:

* **Colors**: Primary/secondary colors, text colors, link colors, backgrounds
* **Typography**: Font families, sizes, weights for headings, body text, TOC links, and buttons
* **Spacing**: Margins, padding, gaps between elements
* **Borders & Shadows**: Custom borders and box shadows for TOC and sections
* **Navigation**: Button styling, padding, border radius, hover effects
* **Header & Document Info**: Header backgrounds, typography for document labels, padding
* **Section Headers**: Background colors, border colors, padding, margins
* **Status Badges**: Typography, padding, border radius, and individual colors for Active, Repealed, Superseded, and Pending states
* **Commentary**: Background colors, border colors, padding, border radius
* **Font Controls**: Background colors, hover states, padding, border radius
* **Jump Selector**: Background colors, border colors, padding, border radius
* **Loading & Error States**: Text colors and padding for loading/error messages

All styles are also scoped under `.lexi-doc` and use comprehensive CSS custom properties for advanced customization via CSS. The plugin includes a complete design token system with variables for:

* **Colors**: `--lexi-color-primary`, `--lexi-color-secondary`, `--lexi-color-bg-primary`, `--lexi-color-text-primary`, etc.
* **Typography**: `--lexi-font-size-base`, `--lexi-font-weight-semibold`, `--lexi-line-height-base`, etc.
* **Spacing**: `--lexi-spacing-sm`, `--lexi-spacing-lg`, `--lexi-spacing-xl`, etc.
* **Border Radius**: `--lexi-radius-sm`, `--lexi-radius-md`, `--lexi-radius-lg`, etc.
* **Shadows**: `--lexi-shadow-sm`, `--lexi-shadow-md`, `--lexi-shadow-lg`, etc.
* **Transitions**: `--lexi-transition-fast`, `--lexi-transition-normal`

This allows themes and users to easily override colors, spacing, and typography without editing the plugin files directly.

= Is this accessible? =

Absolutely! The plugin includes full ARIA support, keyboard navigation, focus management, and screen reader announcements.

= How do I add new Elementor widgets? =

The plugin includes an auto-loading system that makes it easy to add new Elementor widgets:

1. **Create a new widget file** in `inc/elementor/widgets/` following the naming convention: `class-lexi-elementor-{widget-name}.php`
2. **Extend the base widget class** `Lexi_Elementor_Widget_Base` for common functionality
3. **Implement required methods** like `get_name()`, `get_title()`, `register_controls()`, and `render()`
4. **The widget is automatically detected and registered** - no need to modify core plugin files

Example widget structure:
```php
namespace LetLexi\Toolkit\Elementor;

class Lexi_Elementor_My_Widget extends Lexi_Elementor_Widget_Base {
    public function get_name() {
        return 'lexi_my_widget';
    }
    
    public function get_title() {
        return __('My Widget (Lexi)', 'letlexi');
    }
    
    // Implement other required methods...
}
```

The base widget class provides common controls for typography, borders, shadows, backgrounds, and spacing, making widget development faster and more consistent.

== Screenshots ==

1. Section Navigator in Elementor
2. Mobile responsive design
3. Table of contents with active section highlighting
4. Font size controls and accessibility features

== Changelog ==

= 1.1.0 =
* **NEW**: Universal post type support - works with any post type, not just constitution_article
* **NEW**: Configurable ACF field names - use different field names for different post types
* **NEW**: Enhanced validation system with better error messages
* **NEW**: Developer configuration file with extensive customization options
* **NEW**: Auto-loading system for Elementor widgets
* **NEW**: Base widget class with common functionality
* **NEW**: Comprehensive CSS custom properties system
* **NEW**: Enhanced accessibility with high contrast and reduced motion support
* **IMPROVED**: WordPress CSS Coding Standards compliance
* **IMPROVED**: Better theme customization through CSS variables
* **IMPROVED**: Documentation for widget development
* **IMPROVED**: Backward compatibility maintained for existing installations

= 1.0.0 =
* Initial release
* Section Navigator Elementor widget
* REST API endpoint for dynamic section loading
* Shortcode support for non-Elementor usage
* ACF integration with repeater fields
* Responsive two-pane layout
* Font scaling controls with localStorage persistence
* Full accessibility support (ARIA, keyboard navigation)
* Server-side rendering for first section
* Security hardening with input validation and output escaping
* Internationalization support with POT file generation

== Upgrade Notice ==

= 1.1.0 =
Major update with auto-loading widget system and enhanced CSS customization. The new system makes it easy to add custom Elementor widgets without modifying core plugin files. Includes comprehensive CSS custom properties for better theme integration.

= 1.0.0 =
Initial release of LetLexi Toolkit. No upgrade required.


== REST API Usage ==

The plugin provides a REST API endpoint for dynamic section loading:

**Endpoint:** `GET /wp-json/letlexi/v1/section`

**Parameters:**
* `post` (integer, required) - The post ID containing the sections
* `index` (integer, required) - The section index to retrieve (0-based)

**Response:**
```json
{
  "html": "<div class=\"lexi-section\">...</div>",
  "index": 0,
  "total": 5
}
```

**Error Response:**
```json
{
  "code": "invalid_post",
  "message": "Invalid post ID or post type.",
  "data": {
    "status": 400
  }
}
```

= JavaScript Integration =

The plugin provides localized data for JavaScript integration:

```javascript
// Access localized data
const config = window.letlexiSectionNav;

// Available properties:
// - config.restUrl: REST API endpoint URL
// - config.postId: Current post ID
// - config.totalSections: Total number of sections
// - config.settings: Display settings object
// - config.i18n: Internationalized strings
```

== Configuration ==

### Adding Custom Post Types

Add support for your custom post types:

```php
// In your theme's functions.php
add_filter( 'lexi/supported_post_types', function( $post_types ) {
    $custom_post_types = array(
        'law_document',      // Your custom post type
        'legal_article',     // Another custom post type
    );
    
    return array_merge( $post_types, $custom_post_types );
});
```

### Using Custom ACF Field Names

Use different field names for different post types:

```php
add_filter( 'lexi/sections_field_name', function( $field_name, $post_id ) {
    $post_type = get_post_type( $post_id );
    
    switch ( $post_type ) {
        case 'law_document':
            return 'law_sections';
        case 'statute':
            return 'statute_sections';
        default:
            return $field_name; // Default: 'sections'
    }
}, 10, 2 );
```

### Custom Validation

Add custom validation logic:

```php
add_filter( 'lexi/supports_section_navigation', function( $supports, $post_id, $post, $post_type, $has_sections ) {
    if ( $post_type === 'my_custom_post_type' ) {
        $enable_sections = get_post_meta( $post_id, 'enable_sections', true );
        return $has_sections && $enable_sections === 'yes';
    }
    
    return $supports;
}, 10, 5 );
```

== Hooks and Filters ==

The plugin provides several hooks for customization:

**Filters:**
* `lexi/supported_post_types` - Configure supported post types
* `lexi/sections_field_name` - Customize ACF field name
* `lexi/supports_section_navigation` - Custom validation logic
* `letlexi/should_enqueue` - Control when assets are enqueued
* `letlexi/section_html` - Modify individual section HTML
* `letlexi/shell_html` - Modify the complete shell HTML
* `letlexi/is_constitution_article` - Extend post type checking (deprecated)
* `letlexi/get_sections` - Modify section data retrieval

**Actions:**
* `letlexi/section_nav/enqueued` - Fired after assets are enqueued

== Security ==

The plugin implements comprehensive security measures:

* All user input is sanitized and validated
* Output is properly escaped using WordPress functions
* REST API endpoints include parameter validation
* Capability checks for administrative functions
* Nonce verification for write operations
* Security event logging (debug mode only)

== Performance ==

* Assets are only loaded when needed
* Server-side rendering for initial content
* AJAX loading for subsequent sections
* Intelligent prefetching of next sections
* Minimal JavaScript footprint
* Optimized CSS with scoped selectors

== Support ==

For support, feature requests, or bug reports, please visit the plugin's support forum or contact the developer.

== Credits ==

Built with WordPress best practices and modern web standards. Special thanks to the WordPress, Elementor, and ACF communities for their excellent tools and documentation.
