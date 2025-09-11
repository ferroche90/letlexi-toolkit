<?php
/**
 * Backward compatibility helpers for LetLexi Toolkit
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
 * Initialize backward compatibility layer
 *
 * @since 1.0.0
 */
function init_compatibility() {
	// Register old asset handles as aliases.
	register_asset_aliases();
	
	// Create deprecated function wrappers.
	create_deprecated_function_wrappers();
	
	// Create deprecated class wrappers.
	create_deprecated_class_wrappers();
}

/**
 * Register old asset handles as aliases to new ones
 *
 * @since 1.0.0
 */
function register_asset_aliases() {
	// Hook into asset registration to create aliases.
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\create_asset_aliases', 1 );
	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\create_asset_aliases', 1 );
}

/**
 * Create asset aliases for old handles
 *
 * @since 1.0.0
 */
function create_asset_aliases() {
	// Only create aliases if the new assets are registered.
	if ( wp_style_is( 'letlexi-section-nav', 'registered' ) ) {
		// Alias old CSS handle to new one.
		wp_register_style( 'lexi-section-nav', '', array( 'letlexi-section-nav' ), LEXI_VERSION );
	}
	
	if ( wp_script_is( 'letlexi-section-nav', 'registered' ) ) {
		// Alias old JS handle to new one.
		wp_register_script( 'lexi-section-nav', '', array( 'letlexi-section-nav' ), LEXI_VERSION, true );
	}
}

/**
 * Create deprecated function wrappers
 *
 * @since 1.0.0
 */
function create_deprecated_function_wrappers() {
	// Only create wrappers if functions don't already exist.
	if ( ! function_exists( 'lexi_is_constitution_article' ) ) {
		/**
		 * Deprecated function wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_is_constitution_article() instead.
		 * @param int $post_id The post ID to check.
		 * @return bool True if the post is a constitution article, false otherwise.
		 */
		function lexi_is_constitution_article( $post_id ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				trigger_error(
					'Function lexi_is_constitution_article() is deprecated. Use LetLexi\\Toolkit\\lexi_is_constitution_article() instead.',
					E_USER_DEPRECATED
				);
			}
			return lexi_is_constitution_article( $post_id );
		}
	}
	
	if ( ! function_exists( 'lexi_get_sections' ) ) {
		/**
		 * Deprecated function wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_get_sections() instead.
		 * @param int $post_id The post ID to get sections for.
		 * @return array Array of ACF repeater rows or empty array if none found.
		 */
		function lexi_get_sections( $post_id ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				trigger_error(
					'Function lexi_get_sections() is deprecated. Use LetLexi\\Toolkit\\lexi_get_sections() instead.',
					E_USER_DEPRECATED
				);
			}
			return lexi_get_sections( $post_id );
		}
	}
	
	if ( ! function_exists( 'lexi_bool' ) ) {
		/**
		 * Deprecated function wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_bool() instead.
		 * @param mixed $val The value to normalize.
		 * @return bool Normalized boolean value.
		 */
		function lexi_bool( $val ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				trigger_error(
					'Function lexi_bool() is deprecated. Use LetLexi\\Toolkit\\lexi_bool() instead.',
					E_USER_DEPRECATED
				);
			}
			return lexi_bool( $val );
		}
	}
	
	if ( ! function_exists( 'lexi_sanitize_section_index' ) ) {
		/**
		 * Deprecated function wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_sanitize_section_index() instead.
		 * @param int $index The index to sanitize.
		 * @param int $total The total number of sections.
		 * @return int Clamped index between 0 and (total - 1).
		 */
		function lexi_sanitize_section_index( $index, $total ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				trigger_error(
					'Function lexi_sanitize_section_index() is deprecated. Use LetLexi\\Toolkit\\lexi_sanitize_section_index() instead.',
					E_USER_DEPRECATED
				);
			}
			return lexi_sanitize_section_index( $index, $total );
		}
	}
	
	if ( ! function_exists( 'lexi_render_section_html' ) ) {
		/**
		 * Deprecated function wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_render_section_html() instead.
		 * @param int   $post_id The post ID containing the sections.
		 * @param int   $index   The section index to render.
		 * @param array $args    Optional arguments for rendering behavior.
		 * @return string The rendered HTML or empty string on error.
		 */
		function lexi_render_section_html( $post_id, $index, $args = array() ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				trigger_error(
					'Function lexi_render_section_html() is deprecated. Use LetLexi\\Toolkit\\lexi_render_section_html() instead.',
					E_USER_DEPRECATED
				);
			}
			return lexi_render_section_html( $post_id, $index, $args );
		}
	}
	
	if ( ! function_exists( 'lexi_build_shell_html' ) ) {
		/**
		 * Deprecated function wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_build_shell_html() instead.
		 * @param int   $post_id  The post ID containing the sections.
		 * @param array $settings Widget/shortcode settings for customization.
		 * @return string The complete HTML shell structure.
		 */
		function lexi_build_shell_html( $post_id, $settings = array() ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				trigger_error(
					'Function lexi_build_shell_html() is deprecated. Use LetLexi\\Toolkit\\lexi_build_shell_html() instead.',
					E_USER_DEPRECATED
				);
			}
			return lexi_build_shell_html( $post_id, $settings );
		}
	}
	
	if ( ! function_exists( 'lexi_get_article_display_args' ) ) {
		/**
		 * Deprecated function wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_get_article_display_args() instead.
		 * @param int $post_id The post ID to get display settings from.
		 * @return array Array of display arguments.
		 */
		function lexi_get_article_display_args( $post_id ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				trigger_error(
					'Function lexi_get_article_display_args() is deprecated. Use LetLexi\\Toolkit\\lexi_get_article_display_args() instead.',
					E_USER_DEPRECATED
				);
			}
			return lexi_get_article_display_args( $post_id );
		}
	}
	
	if ( ! function_exists( 'lexi_sanitize_input' ) ) {
		/**
		 * Deprecated function wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_sanitize_input() instead.
		 * @param mixed  $input The input to sanitize.
		 * @param string $type  The type of sanitization to apply.
		 * @return mixed The sanitized input.
		 */
		function lexi_sanitize_input( $input, $type = 'text' ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				trigger_error(
					'Function lexi_sanitize_input() is deprecated. Use LetLexi\\Toolkit\\lexi_sanitize_input() instead.',
					E_USER_DEPRECATED
				);
			}
			return lexi_sanitize_input( $input, $type );
		}
	}
	
	if ( ! function_exists( 'lexi_log_security_event' ) ) {
		/**
		 * Deprecated function wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_log_security_event() instead.
		 * @param string $event   The event type.
		 * @param array  $details Additional event details.
		 */
		function lexi_log_security_event( $event, $details = array() ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				trigger_error(
					'Function lexi_log_security_event() is deprecated. Use LetLexi\\Toolkit\\lexi_log_security_event() instead.',
					E_USER_DEPRECATED
				);
			}
			return lexi_log_security_event( $event, $details );
		}
	}
	
	if ( ! function_exists( 'lexi_create_nonce' ) ) {
		/**
		 * Deprecated function wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_create_nonce() instead.
		 * @param string $action The nonce action.
		 * @return string The nonce value.
		 */
		function lexi_create_nonce( $action = 'lexi_write_action' ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				trigger_error(
					'Function lexi_create_nonce() is deprecated. Use LetLexi\\Toolkit\\lexi_create_nonce() instead.',
					E_USER_DEPRECATED
				);
			}
			return lexi_create_nonce( $action );
		}
	}
	
	if ( ! function_exists( 'lexi_verify_nonce' ) ) {
		/**
		 * Deprecated function wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_verify_nonce() instead.
		 * @param string $nonce  The nonce to verify.
		 * @param string $action The nonce action.
		 * @return bool Whether the nonce is valid.
		 */
		function lexi_verify_nonce( $nonce, $action = 'lexi_write_action' ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				trigger_error(
					'Function lexi_verify_nonce() is deprecated. Use LetLexi\\Toolkit\\lexi_verify_nonce() instead.',
					E_USER_DEPRECATED
				);
			}
			return lexi_verify_nonce( $nonce, $action );
		}
	}
	
	if ( ! function_exists( 'lexi_user_can_operate' ) ) {
		/**
		 * Deprecated function wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_user_can_operate() instead.
		 * @param string $operation The operation to check.
		 * @return bool Whether user has required capabilities.
		 */
		function lexi_user_can_operate( $operation = 'read' ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				trigger_error(
					'Function lexi_user_can_operate() is deprecated. Use LetLexi\\Toolkit\\lexi_user_can_operate() instead.',
					E_USER_DEPRECATED
				);
			}
			return lexi_user_can_operate( $operation );
		}
	}
}

/**
 * Create deprecated class wrappers
 *
 * @since 1.0.0
 */
function create_deprecated_class_wrappers() {
	// Only create wrappers if classes don't already exist.
	if ( ! class_exists( 'Lexi_Bootstrap' ) ) {
		/**
		 * Deprecated class wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit functions instead.
		 */
		class Lexi_Bootstrap {
			/**
			 * Deprecated method wrapper
			 *
			 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_is_constitution_article() instead.
			 * @param int $post_id The post ID to check.
			 * @return bool True if the post is a constitution article, false otherwise.
			 */
			public static function is_constitution_article( $post_id ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					trigger_error(
						'Class Lexi_Bootstrap is deprecated. Use LetLexi\\Toolkit\\lexi_is_constitution_article() instead.',
						E_USER_DEPRECATED
					);
				}
				return lexi_is_constitution_article( $post_id );
			}
			
			/**
			 * Deprecated method wrapper
			 *
			 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_get_sections() instead.
			 * @param int $post_id The post ID to get sections for.
			 * @return array Array of ACF repeater rows or empty array if none found.
			 */
			public static function get_sections( $post_id ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					trigger_error(
						'Class Lexi_Bootstrap is deprecated. Use LetLexi\\Toolkit\\lexi_get_sections() instead.',
						E_USER_DEPRECATED
					);
				}
				return lexi_get_sections( $post_id );
			}
			
			/**
			 * Deprecated method wrapper
			 *
			 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_sanitize_section_index() instead.
			 * @param int $index The index to sanitize.
			 * @param int $total The total number of sections.
			 * @return int Clamped index between 0 and (total - 1).
			 */
			public static function sanitize_section_index( $index, $total ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					trigger_error(
						'Class Lexi_Bootstrap is deprecated. Use LetLexi\\Toolkit\\lexi_sanitize_section_index() instead.',
						E_USER_DEPRECATED
					);
				}
				return lexi_sanitize_section_index( $index, $total );
			}
			
			/**
			 * Deprecated method wrapper
			 *
			 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_bool() instead.
			 * @param mixed $val The value to normalize.
			 * @return bool Normalized boolean value.
			 */
			public static function bool( $val ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					trigger_error(
						'Class Lexi_Bootstrap is deprecated. Use LetLexi\\Toolkit\\lexi_bool() instead.',
						E_USER_DEPRECATED
					);
				}
				return lexi_bool( $val );
			}
		}
	}
	
	if ( ! class_exists( 'Lexi_Section_Renderer' ) ) {
		/**
		 * Deprecated class wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit functions instead.
		 */
		class Lexi_Section_Renderer {
			/**
			 * Deprecated method wrapper
			 *
			 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_render_section_html() instead.
			 * @param int   $post_id The post ID containing the sections.
			 * @param int   $index   The section index to render.
			 * @param array $args    Optional arguments for rendering behavior.
			 * @return string The rendered HTML or empty string on error.
			 */
			public static function render_section_html( $post_id, $index, $args = array() ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					trigger_error(
						'Class Lexi_Section_Renderer is deprecated. Use LetLexi\\Toolkit\\lexi_render_section_html() instead.',
						E_USER_DEPRECATED
					);
				}
				return lexi_render_section_html( $post_id, $index, $args );
			}
			
			/**
			 * Deprecated method wrapper
			 *
			 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_get_article_display_args() instead.
			 * @param int $post_id The post ID to get display settings from.
			 * @return array Array of display arguments.
			 */
			public static function get_article_display_args( $post_id ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					trigger_error(
						'Class Lexi_Section_Renderer is deprecated. Use LetLexi\\Toolkit\\lexi_get_article_display_args() instead.',
						E_USER_DEPRECATED
					);
				}
				return lexi_get_article_display_args( $post_id );
			}
		}
	}
	
	if ( ! class_exists( 'Lexi_Shell_Builder' ) ) {
		/**
		 * Deprecated class wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit functions instead.
		 */
		class Lexi_Shell_Builder {
			/**
			 * Deprecated method wrapper
			 *
			 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_build_shell_html() instead.
			 * @param int   $post_id  The post ID containing the sections.
			 * @param array $settings Widget/shortcode settings for customization.
			 * @return string The complete HTML shell structure.
			 */
			public static function build_shell_html( $post_id, $settings = array() ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					trigger_error(
						'Class Lexi_Shell_Builder is deprecated. Use LetLexi\\Toolkit\\lexi_build_shell_html() instead.',
						E_USER_DEPRECATED
					);
				}
				return lexi_build_shell_html( $post_id, $settings );
			}
		}
	}
	
	if ( ! class_exists( 'Lexi_Security' ) ) {
		/**
		 * Deprecated class wrapper
		 *
		 * @deprecated 1.0.0 Use LetLexi\Toolkit functions instead.
		 */
		class Lexi_Security {
			/**
			 * Deprecated method wrapper
			 *
			 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_sanitize_input() instead.
			 * @param mixed  $input The input to sanitize.
			 * @param string $type  The type of sanitization to apply.
			 * @return mixed The sanitized input.
			 */
			public static function sanitize_input( $input, $type = 'text' ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					trigger_error(
						'Class Lexi_Security is deprecated. Use LetLexi\\Toolkit\\lexi_sanitize_input() instead.',
						E_USER_DEPRECATED
					);
				}
				return lexi_sanitize_input( $input, $type );
			}
			
			/**
			 * Deprecated method wrapper
			 *
			 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_log_security_event() instead.
			 * @param string $event   The event type.
			 * @param array  $details Additional event details.
			 */
			public static function log_security_event( $event, $details = array() ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					trigger_error(
						'Class Lexi_Security is deprecated. Use LetLexi\\Toolkit\\lexi_log_security_event() instead.',
						E_USER_DEPRECATED
					);
				}
				return lexi_log_security_event( $event, $details );
			}
			
			/**
			 * Deprecated method wrapper
			 *
			 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_create_nonce() instead.
			 * @param string $action The nonce action.
			 * @return string The nonce value.
			 */
			public static function create_nonce( $action = 'lexi_write_action' ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					trigger_error(
						'Class Lexi_Security is deprecated. Use LetLexi\\Toolkit\\lexi_create_nonce() instead.',
						E_USER_DEPRECATED
					);
				}
				return lexi_create_nonce( $action );
			}
			
			/**
			 * Deprecated method wrapper
			 *
			 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_verify_nonce() instead.
			 * @param string $nonce  The nonce to verify.
			 * @param string $action The nonce action.
			 * @return bool Whether the nonce is valid.
			 */
			public static function verify_nonce( $nonce, $action = 'lexi_write_action' ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					trigger_error(
						'Class Lexi_Security is deprecated. Use LetLexi\\Toolkit\\lexi_verify_nonce() instead.',
						E_USER_DEPRECATED
					);
				}
				return lexi_verify_nonce( $nonce, $action );
			}
			
			/**
			 * Deprecated method wrapper
			 *
			 * @deprecated 1.0.0 Use LetLexi\Toolkit\lexi_user_can_operate() instead.
			 * @param string $operation The operation to check.
			 * @return bool Whether user has required capabilities.
			 */
			public static function user_can_operate( $operation = 'read' ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					trigger_error(
						'Class Lexi_Security is deprecated. Use LetLexi\\Toolkit\\lexi_user_can_operate() instead.',
						E_USER_DEPRECATED
					);
				}
				return lexi_user_can_operate( $operation );
			}
		}
	}
}

// Initialize compatibility layer.
init_compatibility();
