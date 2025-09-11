=== LetLexi Toolkit ===
Contributors: yourname
Tags: elementor, legal, constitution, sections, navigation, acf, rest-api
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Custom Elementor widgets, REST endpoints, and helpers for LetLexi legal resources with section navigation and ACF integration.

== Description ==

LetLexi Toolkit provides a comprehensive solution for displaying legal documents with interactive section navigation. Built specifically for constitution articles and legal content, it offers seamless integration with Elementor, Advanced Custom Fields (ACF), and WordPress REST API.

= Key Features =

* **Section Navigator Widget** - Interactive navigation through document sections
* **Elementor Integration** - Custom widget with full control panel
* **REST API Endpoints** - Dynamic section loading via AJAX
* **ACF Integration** - Works with ACF repeater fields for content management
* **Shortcode Support** - Fallback shortcode for non-Elementor usage
* **Responsive Design** - Mobile-friendly two-pane layout
* **Accessibility** - ARIA support, keyboard navigation, screen reader friendly
* **Font Controls** - User-adjustable font scaling with persistence
* **Server-Side Rendering** - Works without JavaScript enabled

= Use Cases =

* Constitution articles and legal documents
* Multi-section content with table of contents
* Interactive legal reference materials
* Academic papers with section navigation
* Any content requiring structured section browsing

== Installation ==

1. Upload the `letlexi-toolkit` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Ensure Advanced Custom Fields (ACF) is installed and activated
4. Create a custom post type called 'constitution_article' (or use the filter to modify)
5. Add ACF repeater fields named 'sections' with subfields for content
6. Use the Elementor widget or shortcode to display your content

= Requirements =

* WordPress 5.0 or higher
* PHP 7.4 or higher
* Advanced Custom Fields (ACF) plugin
* Elementor plugin (optional, for widget functionality)

== Frequently Asked Questions ==

= Does this work without Elementor? =

Yes! The plugin includes a shortcode `[lexi_section_navigator]` that provides the same functionality without requiring Elementor.

= What ACF fields are required? =

The plugin expects a repeater field named 'sections' with these subfields:
* `section_title` - The section heading
* `section_content` - The main section content (WYSIWYG)
* `section_commentary` - Optional commentary (WYSIWYG)
* `section_status` - Status (active, repealed, superseded, pending)

= Can I customize the styling? =

Yes! All styles are scoped under `.lexi-doc` and use CSS custom properties for easy customization. The plugin includes comprehensive CSS variables for colors, spacing, and typography.

= Is this accessible? =

Absolutely! The plugin includes full ARIA support, keyboard navigation, focus management, and screen reader announcements.

== Screenshots ==

1. Section Navigator in Elementor
2. Mobile responsive design
3. Table of contents with active section highlighting
4. Font size controls and accessibility features

== Changelog ==

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
* Backward compatibility layer for existing implementations
* Security hardening with input validation and output escaping
* Internationalization support with POT file generation

== Upgrade Notice ==

= 1.0.0 =
Initial release of LetLexi Toolkit. No upgrade required.

== Shortcode Usage ==

The plugin provides a shortcode for displaying section navigation without Elementor:

`[lexi_section_navigator]`

= Shortcode Attributes =

* `document_label` - Label for document information (default: "Document:")
* `query_format` - Format string for document query (default: "%constitution% Art. %article%, Section %section%")
* `print_label` - Label for print button (default: "Print")
* `copy_citation_label` - Label for copy citation button (default: "Copy Citation")
* `toc_heading` - Table of contents heading (default: "Table of Contents")
* `previous_label` - Previous section button label (default: "Previous")
* `next_label` - Next section button label (default: "Next")
* `show_commentary` - Show/hide commentary sections (default: "yes")
* `show_cross_refs` - Show/hide cross-references (default: "yes")
* `show_case_law` - Show/hide case law (default: "yes")
* `show_amendments` - Show/hide amendments (default: "yes")
* `loading_strategy` - Loading strategy: "ajax" or "preload" (default: "preload")

= Example Usage =

```
[lexi_section_navigator document_label="Constitution:" show_commentary="no"]
```

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

== Hooks and Filters ==

The plugin provides several hooks for customization:

**Filters:**
* `letlexi/should_enqueue` - Control when assets are enqueued
* `letlexi/section_html` - Modify individual section HTML
* `letlexi/shell_html` - Modify the complete shell HTML
* `letlexi/is_constitution_article` - Extend post type checking
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
