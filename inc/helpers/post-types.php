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
 * @deprecated 1.1.0 Use lexi_supports_section_navigation() instead
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
	 * @deprecated 1.1.0 Use lexi/supports_section_navigation filter instead
	 *
	 * @param bool $is_constitution_article Whether the post is a constitution article.
	 * @param int  $post_id                The post ID.
	 * @param WP_Post $post                The post object.
	 */
	return apply_filters( 'lexi/is_constitution_article', $is_constitution_article, $post_id, $post );
}

/**
 * Check if the given post ID supports section navigation
 *
 * This function checks if a post has the required ACF fields and is in a supported post type.
 * It replaces the hardcoded constitution_article restriction with a flexible system.
 *
 * @since 1.1.0
 *
 * @param int $post_id The post ID to check.
 * @return bool True if the post supports section navigation, false otherwise.
 */
function lexi_supports_section_navigation( $post_id ) {
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

	// Get post type.
	$post_type = get_post_type( $post_id );

	// Get supported post types from filter.
	$supported_post_types = lexi_get_supported_post_types();

	// Check if post type is supported.
	$is_supported_post_type = in_array( $post_type, $supported_post_types, true );

	// Check if ACF is available and post has sections field.
	$has_sections = false;
	if ( function_exists( 'get_field' ) ) {
		// Get the field name (allows customization via filter).
		$field_name = apply_filters( 'lexi/sections_field_name', 'sections', $post_id );
		$sections = get_field( $field_name, $post_id );
		$has_sections = ! empty( $sections ) && is_array( $sections );
	}

	// Post supports section navigation if it has supported post type and sections.
	$supports_navigation = $is_supported_post_type && $has_sections;

	/**
	 * Filter whether a post supports section navigation
	 *
	 * @since 1.1.0
	 *
	 * @param bool    $supports_navigation Whether the post supports section navigation.
	 * @param int     $post_id            The post ID.
	 * @param WP_Post $post               The post object.
	 * @param string  $post_type          The post type.
	 * @param bool    $has_sections       Whether the post has ACF sections.
	 */
	return apply_filters( 'lexi/supports_section_navigation', $supports_navigation, $post_id, $post, $post_type, $has_sections );
}

/**
 * Get list of post types that support section navigation
 *
 * @since 1.1.0
 *
 * @return array Array of supported post type slugs.
 */
function lexi_get_supported_post_types() {
	// Default supported post types.
	$default_post_types = array(
		'constitution_article', // Maintain backward compatibility.
		'post',                 // Standard posts.
		'page',                 // Standard pages.
	);

	/**
	 * Filter the list of post types that support section navigation
	 *
	 * @since 1.1.0
	 *
	 * @param array $post_types Array of supported post type slugs.
	 */
	$supported_post_types = apply_filters( 'lexi/supported_post_types', $default_post_types );

	// Ensure we always return an array.
	if ( ! is_array( $supported_post_types ) ) {
		return $default_post_types;
	}

	// Validate that all items are strings.
	$supported_post_types = array_filter( $supported_post_types, 'is_string' );

	return $supported_post_types;
}

/**
 * Check if the current page should have section navigation assets enqueued
 *
 * @since 1.1.0
 *
 * @return bool True if assets should be enqueued, false otherwise.
 */
function lexi_should_enqueue_assets_for_current_page() {
	// Check if we're on a singular page with supported post type.
	if ( is_singular() ) {
		$post_id = get_the_ID();
		if ( $post_id && lexi_supports_section_navigation( $post_id ) ) {
			return true;
		}
	}


	// Allow external control via filter (e.g., for Elementor widgets).
	$post_id = get_the_ID();
	$should_enqueue = apply_filters( 'letlexi/should_enqueue', false, $post_id );

	return $should_enqueue;
}
