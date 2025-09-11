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
	if ( ! lexi_is_constitution_article( $post_id ) ) {
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
		),
	);

	// Register and enqueue CSS.
	wp_register_style(
		'letlexi-section-nav',
		LEXI_URL . 'assets/css/lexi-section-nav.css',
		array(),
		LEXI_VERSION
	);
	wp_enqueue_style( 'letlexi-section-nav' );

	// Register and enqueue JavaScript.
	wp_register_script(
		'letlexi-section-nav',
		LEXI_URL . 'assets/js/lexi-section-nav.js',
		array(),
		LEXI_VERSION,
		true // In footer.
	);

	// Localize script.
	wp_localize_script( 'letlexi-section-nav', 'letlexiSectionNav', $localize_data );

	// Enqueue the script.
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

	// Check if we're on a constitution article page.
	if ( is_singular( 'constitution_article' ) ) {
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

// Hook into WordPress.
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets' );
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_admin_assets' );
