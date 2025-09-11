<?php
/**
 * Security helper functions for LetLexi Toolkit
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
 * Sanitize and validate user input for Lexi operations
 *
 * @since 1.0.0
 *
 * @param mixed  $input The input to sanitize.
 * @param string $type  The type of sanitization to apply.
 * @return mixed The sanitized input.
 */
function lexi_sanitize_input( $input, $type = 'text' ) {
	switch ( $type ) {
		case 'int':
			return absint( $input );
			
		case 'float':
			return floatval( $input );
			
		case 'email':
			return sanitize_email( $input );
			
		case 'url':
			return esc_url_raw( $input );
			
		case 'html':
			return wp_kses_post( $input );
			
		case 'textarea':
			return sanitize_textarea_field( $input );
			
		case 'key':
			return sanitize_key( $input );
			
		case 'text':
		default:
			return sanitize_text_field( $input );
	}
}

/**
 * Log security events for monitoring
 *
 * @since 1.0.0
 *
 * @param string $event   The event type.
 * @param array  $details Additional event details.
 */
function lexi_log_security_event( $event, $details = array() ) {
	// Only log in debug mode or if explicitly enabled.
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
		return;
	}
	
	$log_entry = array(
		'timestamp' => current_time( 'mysql' ),
		'event'     => $event,
		'details'   => $details,
		'user_id'   => get_current_user_id(),
		'ip'        => isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown',
		'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : 'unknown',
	);
	
	error_log( 'Lexi Security Event: ' . wp_json_encode( $log_entry ) );
}

/**
 * Generate nonce for future write operations
 *
 * @since 1.0.0
 *
 * @param string $action The nonce action.
 * @return string The nonce value.
 */
function lexi_create_nonce( $action = 'lexi_write_action' ) {
	return wp_create_nonce( $action );
}

/**
 * Verify nonce for future write operations
 *
 * @since 1.0.0
 *
 * @param string $nonce  The nonce to verify.
 * @param string $action The nonce action.
 * @return bool Whether the nonce is valid.
 */
function lexi_verify_nonce( $nonce, $action = 'lexi_write_action' ) {
	return wp_verify_nonce( $nonce, $action );
}

/**
 * Check if current user has required capabilities for Lexi operations
 *
 * @since 1.0.0
 *
 * @param string $operation The operation to check.
 * @return bool Whether user has required capabilities.
 */
function lexi_user_can_operate( $operation = 'read' ) {
	switch ( $operation ) {
		case 'write':
		case 'edit':
			return current_user_can( 'edit_posts' );
			
		case 'delete':
			return current_user_can( 'delete_posts' );
			
		case 'read':
		default:
			return true; // Read operations are public.
	}
}
