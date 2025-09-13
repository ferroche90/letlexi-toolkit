<?php
/**
 * Permalink and rewrite helpers for LetLexi Toolkit
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
 * Custom post type permalink for constitution articles
 *
 * Replaces %legal_document% placeholder in the permalink with the taxonomy term slug.
 *
 * @since 1.2.0
 * @param string   $post_link The post's permalink.
 * @param \WP_Post $post      The post object.
 * @return string Modified permalink.
 */
function letlexi_custom_post_type_link( $post_link, $post ) {
	if ( 'constitution_article' !== $post->post_type ) {
		return $post_link;
	}

	if ( false === strpos( $post_link, '%legal_document%' ) ) {
		return $post_link;
	}

	$terms = wp_get_object_terms( $post->ID, 'legal_document' );
	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		$post_link = str_replace( '%legal_document%', $terms[0]->slug, $post_link );
	}

	return $post_link;
}
add_filter( 'post_type_link', __NAMESPACE__ . '\\letlexi_custom_post_type_link', 10, 2 );

/**
 * Add custom rewrite rules for constitution articles
 *
 * Enables URLs like: /resources/laws/constitutions/{term}/{post-name}/
 *
 * @since 1.2.0
 * @return void
 */
function letlexi_add_rewrite_rules() {
	add_rewrite_rule(
		'^resources/laws/constitutions/([^/]+)/([^/]+)/?$',
		'index.php?post_type=constitution_article&name=$matches[2]',
		'top'
	);
}
add_action( 'init', __NAMESPACE__ . '\\letlexi_add_rewrite_rules' );

// Flush rewrite rules on activation (ensure main plugin file path is correct).
if ( function_exists( 'register_activation_hook' ) ) {
	register_activation_hook( LEXI_PATH . 'letlexi-toolkit.php', function() {
		letlexi_add_rewrite_rules();
		flush_rewrite_rules();
	} );
}


