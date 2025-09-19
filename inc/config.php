<?php
/**
 * Configuration file for LetLexi Toolkit
 *
 * This file contains default configuration options and filters for customizing
 * the plugin behavior. You can override these settings in your theme's functions.php
 * or in a custom plugin.
 *
 * @package LetLexi\Toolkit
 * @since 1.1.0
 */

namespace LetLexi\Toolkit;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Configure supported post types for section navigation
 *
 * Add your custom post types to this array to enable section navigation
 * on those post types. The post must also have ACF sections field.
 *
 * @since 1.1.0
 */
add_filter( 'lexi/supported_post_types', function( $post_types ) {
	// Add your custom post types here.
	$custom_post_types = array(
		// 'law_document',     // Example: Custom law document post type.
		// 'legal_article',    // Example: Custom legal article post type.
		// 'statute',          // Example: Custom statute post type.
		// 'regulation',       // Example: Custom regulation post type.
	);
	
	// Merge with existing post types.
	return array_merge( $post_types, $custom_post_types );
});

/**
 * Configure ACF field name for sections
 *
 * If you use a different field name than 'sections' for your ACF repeater,
 * you can customize it here.
 *
 * @since 1.1.0
 */
add_filter( 'lexi/sections_field_name', function( $field_name ) {
	// Change 'sections' to your custom field name if needed.
	// return 'my_custom_sections';
	
	return $field_name; // Default: 'sections'
});

/**
 * Configure default display settings
 *
 * You can set default values for section navigation display options.
 *
 * @since 1.1.0
 */
add_filter( 'lexi/default_display_settings', function( $settings ) {
	// Customize default display settings.
	$custom_defaults = array(
		'show_commentary'  => true,  // Show commentary sections by default.
		'show_cross_refs'  => true,  // Show cross-references by default.
	);
	
	return wp_parse_args( $custom_defaults, $settings );
});

/**
 * Configure default widget settings
 *
 * You can set default values for widget attributes.
 *
 * @since 1.1.0
 */
add_filter( 'lexi/default_widget_settings', function( $settings ) {
	// Customize default widget settings.
	$custom_defaults = array(
		'document_label'        => __( 'Document:', 'letlexi' ),
		'print_label'           => __( 'Print', 'letlexi' ),
		'copy_citation_label'   => __( 'Copy Citation', 'letlexi' ),
		'toc_heading'           => __( 'Table of Contents', 'letlexi' ),
		'previous_label'        => __( 'Previous', 'letlexi' ),
		'next_label'            => __( 'Next', 'letlexi' ),
		'loading_strategy'      => 'preload', // 'ajax' or 'preload'.
	);
	
	return wp_parse_args( $custom_defaults, $settings );
});

/**
 * Configure REST API settings
 *
 * You can customize REST API behavior and permissions.
 *
 * @since 1.1.0
 */
add_filter( 'lexi/rest_api_permission_callback', function( $permission_callback ) {
	// You can customize the permission callback for REST API endpoints.
	// For example, to require authentication:
	// return function() { return is_user_logged_in(); };
	
	// Default: allow public access.
	return '__return_true';
});

/**
 * Configure asset loading behavior
 *
 * You can customize when and how assets are loaded.
 *
 * @since 1.1.0
 */
add_filter( 'lexi/asset_loading_strategy', function( $strategy ) {
	// 'conditional' - Only load on pages that need it (default).
	// 'always' - Always load on frontend.
	// 'never' - Never auto-load (manual enqueuing only).
	
	return 'conditional'; // Default strategy.
});

/**
 * Configure caching behavior
 *
 * You can enable caching for section content to improve performance.
 *
 * @since 1.1.0
 */
add_filter( 'lexi/enable_section_caching', function( $enable ) {
	// Enable caching for section content.
	// Note: This requires a caching plugin or custom implementation.
	
	return false; // Default: disabled.
});

/**
 * Configure debug mode
 *
 * Enable debug mode to see additional information and logging.
 *
 * @since 1.1.0
 */
add_filter( 'lexi/debug_mode', function( $debug ) {
	// Enable debug mode for development.
	// Only enable on development/staging sites.
	
	return defined( 'WP_DEBUG' ) && WP_DEBUG;
});

/**
 * Example: Add support for a custom post type
 *
 * Uncomment and modify this example to add support for your custom post type.
 *
 * @since 1.1.0
 */
/*
add_filter( 'lexi/supported_post_types', function( $post_types ) {
	$post_types[] = 'my_custom_post_type';
	return $post_types;
});
*/

/**
 * Example: Customize section validation
 *
 * You can add custom validation logic for posts.
 *
 * @since 1.1.0
 */
/*
add_filter( 'lexi/supports_section_navigation', function( $supports, $post_id, $post, $post_type, $has_sections ) {
	// Add custom validation logic here.
	// For example, check for specific meta fields or custom conditions.
	
	if ( $post_type === 'my_custom_post_type' ) {
		// Custom validation for your post type.
		$custom_field = get_post_meta( $post_id, 'enable_sections', true );
		return $has_sections && $custom_field === 'yes';
	}
	
	return $supports;
}, 10, 5 );
*/

/**
 * Example: Customize section field name per post type
 *
 * You can use different ACF field names for different post types.
 *
 * @since 1.1.0
 */
/*
add_filter( 'lexi/sections_field_name', function( $field_name, $post_id ) {
	$post_type = get_post_type( $post_id );
	
	switch ( $post_type ) {
		case 'constitution_article':
			return 'sections';
		case 'law_document':
			return 'law_sections';
		case 'statute':
			return 'statute_sections';
		default:
			return $field_name;
	}
}, 10, 2 );
*/
