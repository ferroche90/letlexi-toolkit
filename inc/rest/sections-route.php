<?php
/**
 * REST API routes for LetLexi Toolkit
 *
 * @package LetLexi\Toolkit\REST
 * @since 1.0.0
 */

namespace LetLexi\Toolkit\REST;

use LetLexi\Toolkit as Toolkit;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register REST API routes
 *
 * @since 1.0.0
 */
function register_routes() {
	register_rest_route(
		'letlexi/v1',
		'/section',
		array(
			'methods'             => 'GET',
			'callback'            => __NAMESPACE__ . '\get_section',
			'permission_callback' => '__return_true',
			'args'                => array(
				'post'  => array(
					'type'              => 'integer',
					'required'          => true,
					'sanitize_callback' => 'absint',
					'validate_callback' => function( $param, $request, $key ) {
						return $param > 0;
					},
				),
				'index' => array(
					'type'              => 'integer',
					'required'          => true,
					'sanitize_callback' => 'absint',
					'validate_callback' => function( $param, $request, $key ) {
						return $param >= 0;
					},
				),
			),
		)
	);
}

/**
 * REST API callback to get section HTML
 *
 * @since 1.0.0
 *
 * @param \WP_REST_Request $request The REST request object.
 * @return \WP_REST_Response|\WP_Error The response or error.
 */
function get_section( $request ) {
	$post_id = $request->get_param( 'post' );
	$index   = $request->get_param( 'index' );

	// Enhanced validation.
	$validation = validate_rest_params( $post_id, $index );
	if ( is_wp_error( $validation ) ) {
		return $validation;
	}

	// Get sections.
	$sections = Toolkit\lexi_get_sections( $post_id );
	$total_sections = count( $sections );

	// Sanitize index (already validated, but ensure it's properly clamped).
	$index = Toolkit\lexi_sanitize_section_index( $index, $total_sections );

	// Build args from article-level ACF toggles.
	$args = Toolkit\lexi_get_article_display_args( $post_id );

	// Render section HTML safely.
	$html = Toolkit\lexi_render_section_html( $post_id, $index, $args );

	if ( empty( $html ) || strpos( $html, 'lexi-error' ) !== false ) {
		return new \WP_Error(
			'render_failed',
			__( 'Failed to render section content.', 'letlexi' ),
			array( 'status' => 500 )
		);
	}

	// Return successful response.
	return rest_ensure_response(
		array(
			'html'  => $html,
			'index' => $index,
			'total' => $total_sections,
		)
	);
}

/**
 * Enhanced validation for REST API endpoint
 *
 * @since 1.0.0
 *
 * @param int $post_id The post ID to validate.
 * @param int $index   The section index to validate.
 * @return \WP_Error|true Validation result.
 */
function validate_rest_params( $post_id, $index ) {
	// Ensure post_id is numeric and positive.
	if ( ! is_numeric( $post_id ) || $post_id <= 0 ) {
		return new \WP_Error(
			'invalid_post_id',
			__( 'Invalid post ID. Must be a positive integer.', 'letlexi' ),
			array( 'status' => 400 )
		);
	}

	// Ensure index is numeric and non-negative.
	if ( ! is_numeric( $index ) || $index < 0 ) {
		return new \WP_Error(
			'invalid_index',
			__( 'Invalid section index. Must be a non-negative integer.', 'letlexi' ),
			array( 'status' => 400 )
		);
	}

	// Check if post exists and is published.
	$post = get_post( $post_id );
	if ( ! $post ) {
		return new \WP_Error(
			'post_not_found',
			__( 'Post not found.', 'letlexi' ),
			array( 'status' => 404 )
		);
	}

	if ( 'publish' !== $post->post_status ) {
		return new \WP_Error(
			'post_not_published',
			__( 'Post is not published.', 'letlexi' ),
			array( 'status' => 403 )
		);
	}

	// Validate post type.
	if ( ! Toolkit\lexi_supports_section_navigation( $post_id ) ) {
		return new \WP_Error(
			'invalid_post_type',
			__( 'Invalid post type or missing ACF sections. Only posts with ACF sections and supported post types are allowed.', 'letlexi' ),
			array( 'status' => 400 )
		);
	}

	// Get sections and validate index bounds.
	$sections = Toolkit\lexi_get_sections( $post_id );
	if ( empty( $sections ) ) {
		return new \WP_Error(
			'no_sections',
			__( 'No sections found for this post.', 'letlexi' ),
			array( 'status' => 404 )
		);
	}

	// Clamp index to valid range.
	$index = Toolkit\lexi_sanitize_section_index( $index, count( $sections ) );

	return true;
}

// Hook into REST API initialization.
add_action( 'rest_api_init', __NAMESPACE__ . '\register_routes' );
