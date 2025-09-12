<?php
/**
 * Plugin Name: LetLexi Toolkit
 * Plugin URI: https://webatix.com/
 * Description: Custom Elementor widgets, REST endpoints, and helpers for LetLexi legal resources.
 * Version: 1.0.0
 * Author: Webatix
 * Author URI: https://webatix.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: letlexi
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 *
 * @package LetLexi\Toolkit
 * @version 1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
if ( ! defined( 'LEXI_VERSION' ) ) {
	define( 'LEXI_VERSION', '1.0.0' );
}

if ( ! defined( 'LEXI_PATH' ) ) {
	define( 'LEXI_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'LEXI_URL' ) ) {
	define( 'LEXI_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'LEXI_NS' ) ) {
	define( 'LEXI_NS', 'letlexi' );
}

// Load the bootstrap file.
require_once LEXI_PATH . 'inc/bootstrap.php';
