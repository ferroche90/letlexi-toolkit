<?php
/**
 * ACF helper functions for LetLexi Toolkit
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
 * Get ACF repeater sections for a given post ID
 *
 * @since 1.0.0
 *
 * @param int $post_id The post ID to get sections for.
 * @return array Array of ACF repeater rows or empty array if none found.
 */
function lexi_get_sections( $post_id ) {
	$post_id = absint( $post_id );

	if ( ! $post_id ) {
		return array();
	}

	// Validate post type first.
	if ( ! lexi_supports_section_navigation( $post_id ) ) {
		return array();
	}

	// Check if ACF is available.
	if ( ! function_exists( 'get_field' ) ) {
		return array();
	}

	// Get the field name (allows customization via filter).
	$field_name = apply_filters( 'lexi/sections_field_name', 'sections', $post_id );
	$sections = get_field( $field_name, $post_id );

	// Ensure we return an array.
	if ( ! is_array( $sections ) ) {
		return array();
	}

	/**
	 * Filter the sections array before returning
	 *
	 * @since 1.0.0
	 *
	 * @param array $sections Array of section data.
	 * @param int   $post_id  The post ID.
	 */
	return apply_filters( 'lexi/get_sections', $sections, $post_id );
}

/**
 * Normalize a value to boolean
 *
 * @since 1.0.0
 *
 * @param mixed $val The value to normalize.
 * @return bool Normalized boolean value.
 */
function lexi_bool( $val ) {
	if ( is_bool( $val ) ) {
		return $val;
	}

	if ( is_string( $val ) ) {
		$val = strtolower( trim( $val ) );
		return in_array( $val, array( 'true', '1', 'yes', 'on' ), true );
	}

	if ( is_numeric( $val ) ) {
		return (bool) $val;
	}

	return false;
}

/**
 * Sanitize and clamp section index to valid range
 *
 * @since 1.0.0
 *
 * @param int $index The index to sanitize.
 * @param int $total The total number of sections.
 * @return int Clamped index between 0 and (total - 1).
 */
function lexi_sanitize_section_index( $index, $total ) {
	$index = absint( $index );
	$total = absint( $total );

	if ( $total <= 0 ) {
		return 0;
	}

	// Clamp to valid range [0, total-1].
	return max( 0, min( $index, $total - 1 ) );
}
