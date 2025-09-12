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
 * Auto-load all Elementor widget classes from the widgets directory
 *
 * @since 1.0.0
 *
 * @return array Array of loaded widget class names.
 */
function auto_load_elementor_widgets() {
	$widgets_dir = LEXI_PATH . 'inc/elementor/widgets/';
	$loaded_widgets = array();

	// Check if the widgets directory exists.
	if ( ! is_dir( $widgets_dir ) ) {
		return $loaded_widgets;
	}

	// Get all PHP files in the widgets directory.
	$widget_files = glob( $widgets_dir . '*.php' );

	if ( empty( $widget_files ) ) {
		return $loaded_widgets;
	}

	// Sort files to ensure base class is loaded first.
	usort( $widget_files, function( $a, $b ) {
		$a_basename = basename( $a );
		$b_basename = basename( $b );
		
		// Base class should be loaded first.
		if ( strpos( $a_basename, 'widget-base' ) !== false ) {
			return -1;
		}
		if ( strpos( $b_basename, 'widget-base' ) !== false ) {
			return 1;
		}
		
		// Otherwise, maintain alphabetical order.
		return strcmp( $a_basename, $b_basename );
	});

	// First, load the base class if it exists.
	$base_class_file = $widgets_dir . 'class-lexi-elementor-widget-base.php';
	if ( file_exists( $base_class_file ) ) {
		require_once $base_class_file;
	}

	// Load each widget file.
	foreach ( $widget_files as $widget_file ) {
		// Skip if it's not a valid PHP file.
		if ( ! is_file( $widget_file ) || pathinfo( $widget_file, PATHINFO_EXTENSION ) !== 'php' ) {
			continue;
		}

		// Skip base class file as it's already loaded.
		if ( strpos( basename( $widget_file ), 'widget-base' ) !== false ) {
			continue;
		}

		// Require the widget file.
		require_once $widget_file;

		// Extract class name from filename.
		$filename = basename( $widget_file, '.php' );
		$class_name = str_replace( 'class-', '', $filename );
		$class_name = str_replace( '-', '_', $class_name );
		$class_name = 'LetLexi\\Toolkit\\Elementor\\' . ucwords( $class_name, '_' );

		// Check if the class exists and extends Widget_Base.
		if ( class_exists( $class_name ) ) {
			$reflection = new \ReflectionClass( $class_name );
			if ( $reflection->isSubclassOf( '\Elementor\Widget_Base' ) ) {
				$loaded_widgets[] = $class_name;
			}
		}
	}

	return $loaded_widgets;
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

	// Auto-load all widget classes.
	$loaded_widgets = auto_load_elementor_widgets();

	// Register each loaded widget.
	foreach ( $loaded_widgets as $widget_class ) {
		if ( class_exists( $widget_class ) ) {
			$widgets_manager->register( new $widget_class() );
		}
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
