<?php
/**
 * Elementor integration for LetLexi Toolkit
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
 * Register Elementor category
 *
 * @since 1.0.0
 *
 * @param \Elementor\Elements_Manager $elements_manager The elements manager.
 */
function register_elementor_category( $elements_manager ) {
	$elements_manager->add_category(
		'letlexi',
		array(
			'title' => __( 'LetLexi', 'letlexi' ),
			'icon'  => 'fa fa-gavel',
		)
	);
}

/**
 * Register Elementor widgets
 *
 * @since 1.0.0
 *
 * @param \Elementor\Widgets_Manager $widgets_manager The widgets manager.
 */
function register_elementor_widgets( $widgets_manager ) {
	// Check if Elementor is available.
	if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
		return;
	}

	// Require the widget class.
	$widget_file = LEXI_PATH . 'inc/elementor/widgets/class-lexi-elementor-section-navigator.php';
	if ( file_exists( $widget_file ) ) {
		require_once $widget_file;
	}

	// Register the widget.
	if ( class_exists( '\LetLexi\Toolkit\Elementor\Lexi_Elementor_Section_Navigator' ) ) {
		$widgets_manager->register( new \LetLexi\Toolkit\Elementor\Lexi_Elementor_Section_Navigator() );
	}
}

/**
 * Initialize Elementor integration
 *
 * @since 1.0.0
 */
function init_elementor_integration() {
	// Check if Elementor is active.
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	// Hook into Elementor.
	add_action( 'elementor/elements/categories_registered', __NAMESPACE__ . '\register_elementor_category' );
	add_action( 'elementor/widgets/register', __NAMESPACE__ . '\register_elementor_widgets' );
}

// Initialize Elementor integration after Elementor is loaded.
add_action( 'elementor/init', __NAMESPACE__ . '\init_elementor_integration' );
