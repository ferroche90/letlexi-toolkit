<?php
/**
 * Shortcode implementation for LetLexi Toolkit
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
 * Register the shortcode
 *
 * @since 1.0.0
 */
function register_shortcode() {
	add_shortcode( 'lexi_section_navigator', __NAMESPACE__ . '\handle_shortcode' );
}

/**
 * Handle the shortcode output
 *
 * @since 1.0.0
 *
 * @param array  $atts    Shortcode attributes.
 * @param string $content Shortcode content (not used).
 * @return string The shortcode output.
 */
function handle_shortcode( $atts, $content = '' ) {
	// Parse shortcode attributes with defaults.
	$atts = shortcode_atts(
		array(
			'document_label'        => __( 'Document:', 'letlexi' ),
			'query_format'          => '%constitution% Art. %article%, Section %section%',
			'print_label'           => __( 'Print', 'letlexi' ),
			'copy_citation_label'   => __( 'Copy Citation', 'letlexi' ),
			'toc_heading'           => __( 'Table of Contents', 'letlexi' ),
			'previous_label'        => __( 'Previous', 'letlexi' ),
			'next_label'            => __( 'Next', 'letlexi' ),
			'show_commentary'       => 'yes',
			'show_cross_refs'       => 'yes',
			'show_case_law'         => 'yes',
			'show_amendments'       => 'yes',
			'loading_strategy'      => 'preload', // Default to SSR for shortcode.
		),
		$atts,
		'lexi_section_navigator'
	);

	// Get current post ID.
	$post_id = get_the_ID();
	if ( ! $post_id ) {
		return '';
	}

	// Validate post type.
	if ( ! lexi_is_constitution_article( $post_id ) ) {
		// Return admin notice for editors, empty for regular users.
		if ( current_user_can( 'edit_posts' ) ) {
			return '<div class="lexi-error lexi-admin-notice">' . 
				   esc_html__( 'This shortcode can only be used on constitution article pages.', 'letlexi' ) . 
				   '</div>';
		}
		return '';
	}

	// Get sections to ensure they exist.
	$sections = lexi_get_sections( $post_id );
	if ( empty( $sections ) ) {
		if ( current_user_can( 'edit_posts' ) ) {
			return '<div class="lexi-error lexi-admin-notice">' . 
				   esc_html__( 'No sections found for this document.', 'letlexi' ) . 
				   '</div>';
		}
		return '';
	}

	// Merge shortcode settings with ACF toggles.
	$settings = merge_shortcode_settings( $atts, $post_id );

	// Ensure first section is server-side rendered for accessibility.
	$settings['loading_strategy'] = 'preload';

	// Build and return the shell HTML.
	$html = lexi_build_shell_html( $post_id, $settings );

	/**
	 * Filter the shortcode output before returning
	 *
	 * @since 1.0.0
	 *
	 * @param string $html    The shortcode HTML output.
	 * @param int    $post_id The post ID.
	 * @param array  $atts    The shortcode attributes.
	 */
	return apply_filters( 'lexi/shortcode_output', $html, $post_id, $atts );
}

/**
 * Merge shortcode settings with ACF toggles
 *
 * @since 1.0.0
 *
 * @param array $atts    Shortcode attributes.
 * @param int   $post_id The post ID.
 * @return array Merged settings.
 */
function merge_shortcode_settings( $atts, $post_id ) {
	// Start with shortcode defaults.
	$settings = array(
		'document_label'        => $atts['document_label'],
		'query_format'          => $atts['query_format'],
		'print_label'           => $atts['print_label'],
		'copy_citation_label'   => $atts['copy_citation_label'],
		'toc_heading'           => $atts['toc_heading'],
		'previous_label'        => $atts['previous_label'],
		'next_label'            => $atts['next_label'],
		'loading_strategy'      => $atts['loading_strategy'],
	);

	// Get ACF display settings.
	$acf_settings = lexi_get_article_display_args( $post_id );

	// Merge ACF settings with shortcode overrides.
	$settings['show_commentary'] = lexi_bool( $atts['show_commentary'] ) ? lexi_bool( $atts['show_commentary'] ) : $acf_settings['show_commentary'];
	$settings['show_cross_refs'] = lexi_bool( $atts['show_cross_refs'] ) ? lexi_bool( $atts['show_cross_refs'] ) : $acf_settings['show_cross_refs'];
	$settings['show_case_law']   = lexi_bool( $atts['show_case_law'] ) ? lexi_bool( $atts['show_case_law'] ) : $acf_settings['show_case_law'];
	$settings['show_amendments'] = lexi_bool( $atts['show_amendments'] ) ? lexi_bool( $atts['show_amendments'] ) : $acf_settings['show_amendments'];

	return $settings;
}

/**
 * Ensure assets are enqueued when shortcode is present
 *
 * @since 1.0.0
 *
 * @param string $content The post content.
 * @return string The post content (unchanged).
 */
function ensure_assets_for_shortcode( $content ) {
	// Check if the shortcode is present in the content.
	if ( has_shortcode( $content, 'lexi_section_navigator' ) ) {
		// Trigger asset enqueuing via filter.
		add_filter( 'letlexi/should_enqueue', '__return_true' );
	}

	return $content;
}

// Hook into WordPress.
add_action( 'init', __NAMESPACE__ . '\register_shortcode' );
add_filter( 'the_content', __NAMESPACE__ . '\ensure_assets_for_shortcode' );
