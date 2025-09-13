<?php
/**
 * Asset enqueuing for LetLexi Toolkit
 *
 * @package LetLexi\Toolkit
 * @since 1.0.0
 */

namespace LetLexi\Toolkit;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register assets (styles and scripts) so Elementor can enqueue them by handle
 *
 * @since 1.0.0
 */
function register_assets() {
	// Register CSS.
	wp_register_style(
		'letlexi-fa',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
		array(),
		'6.4.0'
	);

	// Include FA v4 shims to support legacy icon names (e.g., times, volume-up, user-cog).
	wp_register_style(
		'letlexi-fa-v4-shims',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/v4-shims.min.css',
		array('letlexi-fa'),
		'6.4.0'
	);

	wp_register_style(
		'letlexi-section-nav',
		LEXI_URL . 'assets/css/lexi-section-nav.css',
		array(),
		LEXI_VERSION
	);

	// Register JS.
	wp_register_script(
		'letlexi-section-nav',
		LEXI_URL . 'assets/js/lexi-section-nav.js',
		array(),
		LEXI_VERSION,
		true // In footer.
	);
}

/**
 * Register and enqueue assets for section navigation
 *
 * @since 1.0.0
 */
function enqueue_assets() {
	// Check if assets should be enqueued.
	if ( ! should_enqueue_assets() ) {
		return;
	}

	$post_id = get_the_ID();
	if ( ! $post_id ) {
		return;
	}

	// Validate post type.
	if ( ! lexi_supports_section_navigation( $post_id ) ) {
		return;
	}

	// Get article display settings for localization.
	$display_args = lexi_get_article_display_args( $post_id );

	// Get sections for total count.
	$sections = lexi_get_sections( $post_id );
	$total_sections = count( $sections );

	// Prepare localization data.
	$localize_data = array(
		'restUrl'        => rest_url( LEXI_NS . '/v1/section' ),
		'postId'         => $post_id,
		'totalSections'  => $total_sections,
		'settings'       => $display_args,
		'i18n'           => array(
			'loading'           => __( 'Loading...', 'letlexi' ),
			'error'             => __( 'Error loading section', 'letlexi' ),
			'previous'          => __( 'Previous Section', 'letlexi' ),
			'next'              => __( 'Next Section', 'letlexi' ),
			'tableOfContents'   => __( 'Table of Contents', 'letlexi' ),
			'section'           => __( 'Section', 'letlexi' ),
			'showCommentary'    => __( 'Show Commentary', 'letlexi' ),
			'hideCommentary'    => __( 'Hide Commentary', 'letlexi' ),
			'increaseFontSize'  => __( 'Increase Font Size', 'letlexi' ),
			'decreaseFontSize'  => __( 'Decrease Font Size', 'letlexi' ),
			'resetFontSize'     => __( 'Reset Font Size', 'letlexi' ),
			'printSuccess'      => __( 'Print dialog opened', 'letlexi' ),
			'citationCopied'    => __( 'Citation copied!', 'letlexi' ),
		),
	);

	// Ensure assets are registered before enqueuing (for safety if hook order changes).
	register_assets();

	// Enqueue CSS.
	wp_enqueue_style( 'letlexi-section-nav' );

	// Localize and enqueue JS.
	wp_localize_script( 'letlexi-section-nav', 'letlexiSectionNav', $localize_data );
	wp_enqueue_script( 'letlexi-section-nav' );

	/**
	 * Fire action hook for extensibility after assets are enqueued
	 *
	 * @since 1.0.0
	 *
	 * @param int   $post_id      The post ID.
	 * @param array $display_args The display arguments.
	 */
	do_action( 'letlexi/section_nav/enqueued', $post_id, $display_args );
}

/**
 * Determine if assets should be enqueued
 *
 * @since 1.0.0
 *
 * @return bool Whether assets should be enqueued.
 */
function should_enqueue_assets() {
	// Don't enqueue in admin area.
	if ( is_admin() ) {
		return false;
	}

	// Don't enqueue during AJAX requests unless specifically needed.
	if ( wp_doing_ajax() ) {
		return false;
	}

	// Don't enqueue during REST API requests unless specifically needed.
	if ( wp_is_json_request() ) {
		return false;
	}

	// Check if we're on a page that supports section navigation.
	if ( lexi_should_enqueue_assets_for_current_page() ) {
		return true;
	}

	// Check if the current post has the shortcode.
	$post = get_post();
	if ( $post && has_shortcode( $post->post_content, 'lexi_section_navigator' ) ) {
		return true;
	}

	// Allow external control via filter (e.g., for Elementor widgets).
	$post_id = get_the_ID();
	$should_enqueue = apply_filters( 'letlexi/should_enqueue', false, $post_id );

	return $should_enqueue;
}

/**
 * Enqueue admin assets
 *
 * @since 1.0.0
 *
 * @param string $hook Current admin page hook.
 */
function enqueue_admin_assets( $hook ) {
	// Only enqueue on specific admin pages if needed.
	// This is a placeholder for future admin functionality.
}

/**
 * Force Font Awesome to load from our CDN handle only.
 *
 * Dequeue/deregister common non-CDN FA handles (including Elementor's FA packs)
 * and ensure our CDN-based 'letlexi-fa' is enqueued.
 */
function enforce_font_awesome_cdn() {
	// Ensure our FA is registered.
	register_assets();

	$non_cdn_fa_handles = array(
		'font-awesome',
		'fontawesome',
		'fa',
		'elementor-icons-fa',
		'elementor-icons-fa-solid',
		'elementor-icons-fa-regular',
		'elementor-icons-fa-brands',
		'elementor-font-awesome',
	);

	foreach ( $non_cdn_fa_handles as $handle ) {
		if ( wp_style_is( $handle, 'enqueued' ) ) {
			wp_dequeue_style( $handle );
		}
		if ( wp_style_is( $handle, 'registered' ) ) {
			wp_deregister_style( $handle );
		}
	}

	// Enqueue our CDN FA as the primary source.
	if ( ! wp_style_is( 'letlexi-fa', 'enqueued' ) ) {
		wp_enqueue_style( 'letlexi-fa' );
	}
	// Enqueue v4 shim to allow legacy icon names used in the picker data.
	if ( ! wp_style_is( 'letlexi-fa-v4-shims', 'enqueued' ) ) {
		wp_enqueue_style( 'letlexi-fa-v4-shims' );
	}
}

// Hook registrations so Elementor editor can find handles.
add_action( 'init', __NAMESPACE__ . '\register_assets' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\register_assets', 1 );
add_action( 'elementor/frontend/after_register_styles', __NAMESPACE__ . '\register_assets' );
add_action( 'elementor/frontend/after_register_scripts', __NAMESPACE__ . '\register_assets' );

// Hook enqueue on frontend where needed.
add_action( 'wp_enqueue_scripts', function() {
	enforce_font_awesome_cdn();
	enqueue_assets();
}, 20 );
add_action( 'admin_enqueue_scripts', function( $hook ) {
	enforce_font_awesome_cdn();
	enqueue_admin_assets( $hook );
}, 20 );
