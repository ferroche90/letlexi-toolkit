<?php
/**
 * Post type helper functions for LetLexi Toolkit
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
 * Check if the given post ID is a constitution article post type
 *
 * @since 1.0.0
 *
 * @param int $post_id The post ID to check.
 * @return bool True if the post is a constitution article, false otherwise.
 */
function lexi_is_constitution_article( $post_id ) {
	$post_id = absint( $post_id );

	if ( ! $post_id ) {
		return false;
	}

	// Get the post object to check existence and status.
	$post = get_post( $post_id );
	if ( ! $post ) {
		return false;
	}

	// Check post status - allow published posts or admin previews.
	$allowed_statuses = array( 'publish' );
	if ( is_admin() ) {
		$allowed_statuses[] = 'draft';
		$allowed_statuses[] = 'private';
	}

	if ( ! in_array( $post->post_status, $allowed_statuses, true ) ) {
		return false;
	}

	// Check post type.
	$post_type = get_post_type( $post_id );
	$is_constitution_article = ( 'constitution_article' === $post_type );

	/**
	 * Filter whether a post is considered a constitution article
	 *
	 * @since 1.0.0
	 *
	 * @param bool $is_constitution_article Whether the post is a constitution article.
	 * @param int  $post_id                The post ID.
	 * @param WP_Post $post                The post object.
	 */
	return apply_filters( 'lexi/is_constitution_article', $is_constitution_article, $post_id, $post );
}
