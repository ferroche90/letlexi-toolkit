<?php
/**
 * Bootstrap file for LetLexi Toolkit
 *
 * This file handles the initialization of the LetLexi Toolkit plugin,
 * including text domain loading and file includes.
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
 * Load plugin text domain for internationalization
 *
 * @since 1.0.0
 */
function load_textdomain() {
	load_plugin_textdomain(
		'letlexi',
		false,
		dirname( plugin_basename( LEXI_PATH . 'letlexi-toolkit.php' ) ) . '/languages'
	);
}

// Hook text domain loading.
add_action( 'plugins_loaded', __NAMESPACE__ . '\load_textdomain' );

/**
 * Include helper files if they exist
 *
 * @since 1.0.0
 */
function include_helper_files() {
	$helper_files = array(
		'inc/helpers/post-types.php',
		'inc/helpers/acf.php',
		'inc/helpers/render.php',
		'inc/helpers/security.php',
		'inc/helpers/compat.php',
		'inc/assets/enqueue.php',
		'inc/rest/sections-route.php',
		'inc/elementor/register.php',
		'inc/shortcode/section-navigator.php',
	);

	foreach ( $helper_files as $file ) {
		$file_path = LEXI_PATH . $file;
		if ( file_exists( $file_path ) ) {
			require_once $file_path;
		}
	}
}

// Include helper files.
include_helper_files();
