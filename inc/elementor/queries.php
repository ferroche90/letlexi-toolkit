<?php
/**
 * Elementor custom query handlers for LetLexi Toolkit
 *
 * @package LetLexi\Toolkit
 * @since 1.2.0
 */

namespace LetLexi\Toolkit;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor Loop Grid: Show child pages of the current page
 *
 * Custom Elementor query action to display child pages of the current page.
 * Use in Elementor Loop Grid: Advanced > Query > Custom Query ID = children_of_current
 *
 * @since 1.2.0
 * @param \WP_Query $query The WordPress query object to modify.
 * @return void
 */
add_action( 'elementor/query/children_of_current', function( $query ) {
	$parent_id = get_queried_object_id();
	if ( ! $parent_id && isset( $_GET['post'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$parent_id = (int) $_GET['post'];
	}
	if ( ! $parent_id ) {
		return;
	}

	$query->set( 'post_type', 'page' );
	$query->set( 'post_parent', $parent_id );
} );

/**
 * Elementor Loop Grid: Show articles in order of article_order
 *
 * Custom Elementor query action to display articles in order of article_order.
 * Use in Elementor Loop Grid: Advanced > Query > Custom Query ID = article_order
 *
 * @since 1.2.0
 * @param \WP_Query $query The WordPress query object to modify.
 * @return void
 */
add_action( 'elementor/query/article_order', function( $query ) {
	$query->set( 'orderby', 'meta_value_num' );
	$query->set( 'meta_key', 'article_order' );
	$query->set( 'order', 'ASC' );
} );