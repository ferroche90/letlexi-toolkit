<?php
/**
 * Rendering helper functions for LetLexi Toolkit
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
 * Render a single section as HTML
 *
 * @since 1.0.0
 *
 * @param int   $post_id The post ID containing the sections.
 * @param int   $index   The section index to render.
 * @param array $args    Optional arguments for rendering behavior.
 * @return string The rendered HTML or empty string on error.
 */
function lexi_render_section_html( $post_id, $index, $args = array() ) {
	// Validate post type.
	if ( ! lexi_is_constitution_article( $post_id ) ) {
		return '';
	}

	// Load all sections.
	$sections = lexi_get_sections( $post_id );
	if ( empty( $sections ) || ! is_array( $sections ) ) {
		return '';
	}

	// Sanitize and validate index.
	$index = lexi_sanitize_section_index( $index, count( $sections ) );
	$section = $sections[ $index ];

	if ( ! is_array( $section ) ) {
		return '';
	}

	// Parse arguments with defaults.
	$defaults = array(
		'show_commentary'  => true,
		'show_cross_refs'  => true,
		'show_case_law'    => true,
		'show_amendments'  => true,
		'classes'          => 'lexi-section',
	);

	$args = wp_parse_args( $args, $defaults );

	// Normalize boolean arguments.
	$args['show_commentary'] = lexi_bool( $args['show_commentary'] );
	$args['show_cross_refs'] = lexi_bool( $args['show_cross_refs'] );
	$args['show_case_law']   = lexi_bool( $args['show_case_law'] );
	$args['show_amendments'] = lexi_bool( $args['show_amendments'] );

	// Extract and sanitize section fields.
	$section_number   = isset( $section['section_number'] ) ? sanitize_text_field( $section['section_number'] ) : '';
	$section_title    = isset( $section['section_title'] ) ? sanitize_text_field( $section['section_title'] ) : '';
	$section_status   = isset( $section['section_status'] ) ? sanitize_text_field( $section['section_status'] ) : '';
	$section_content  = isset( $section['section_content'] ) ? wp_kses_post( $section['section_content'] ) : '';
	$section_commentary = isset( $section['section_commentary'] ) ? wp_kses_post( $section['section_commentary'] ) : '';

	// Extract repeater fields.
	$cross_references = isset( $section['cross_references'] ) && is_array( $section['cross_references'] ) ? $section['cross_references'] : array();
	$case_law_references = isset( $section['case_law_references'] ) && is_array( $section['case_law_references'] ) ? $section['case_law_references'] : array();
	$amendment_history = isset( $section['amendment_history'] ) && is_array( $section['amendment_history'] ) ? $section['amendment_history'] : array();

	// Sanitize wrapper classes.
	$classes = sanitize_html_class( $args['classes'] );

	// Start building HTML.
	$html = '<div class="' . esc_attr( $classes ) . '" data-section-index="' . esc_attr( $index ) . '"';

	if ( ! empty( $section_number ) ) {
		$html .= ' id="sec-' . esc_attr( $post_id ) . '-' . esc_attr( $section_number ) . '"';
	}

	$html .= '>';

	// Section header.
	if ( ! empty( $section_number ) || ! empty( $section_title ) ) {
		$html .= '<div class="lexi-section-header">';
		$html .= '<h2 class="lexi-section-title">';

		if ( ! empty( $section_number ) && ! empty( $section_title ) ) {
			$html .= sprintf(
				/* translators: %1$s: section number, %2$s: section title */
				esc_html__( 'Section %1$s. %2$s', 'letlexi' ),
				esc_html( $section_number ),
				esc_html( $section_title )
			);
		} elseif ( ! empty( $section_number ) ) {
			$html .= sprintf(
				/* translators: %s: section number */
				esc_html__( 'Section %s', 'letlexi' ),
				esc_html( $section_number )
			);
		} else {
			$html .= esc_html( $section_title );
		}

		$html .= '</h2>';

		// Status badge (supports both class systems: lexi-badge--* and lexi-status-*).
		if ( ! empty( $section_status ) ) {
			$status_slug = strtolower( $section_status );
			$badge_class = 'lexi-badge--' . $status_slug;
			$status_class = 'lexi-status-' . $status_slug;
			$html .= '<span class="lexi-badge ' . esc_attr( $badge_class ) . ' lexi-status-badge ' . esc_attr( $status_class ) . '">';
			$html .= esc_html( $section_status );
			$html .= '</span>';
		}

		$html .= '</div>';
	}

	// Main content.
	if ( ! empty( $section_content ) ) {
		$html .= '<div class="lexi-section-content">';
		$html .= $section_content; // Already sanitized with wp_kses_post.
		$html .= '</div>';
	}

	// Commentary (collapsible).
	if ( $args['show_commentary'] && ! empty( $section_commentary ) ) {
		$html .= '<div class="lexi-commentary-section">';
		$html .= '<button type="button" class="lexi-commentary-toggle" aria-expanded="false" aria-controls="commentary-' . esc_attr( $post_id ) . '-' . esc_attr( $index ) . '">';
		$html .= esc_html__( 'Show Commentary', 'letlexi' );
		$html .= '</button>';
		$html .= '<div class="lexi-commentary-content" id="commentary-' . esc_attr( $post_id ) . '-' . esc_attr( $index ) . '" aria-hidden="true">';
		$html .= $section_commentary; // Already sanitized with wp_kses_post.
		$html .= '</div>';
		$html .= '</div>';
	}

	// Cross references.
	if ( $args['show_cross_refs'] && ! empty( $cross_references ) ) {
		$html .= '<div class="lexi-cross-references">';
		$html .= '<h3>' . esc_html__( 'Cross References', 'letlexi' ) . '</h3>';
		$html .= '<ul>';

		foreach ( $cross_references as $ref ) {
			if ( isset( $ref['reference_text'] ) && ! empty( $ref['reference_text'] ) ) {
				$html .= '<li>' . esc_html( sanitize_text_field( $ref['reference_text'] ) ) . '</li>';
			}
		}

		$html .= '</ul>';
		$html .= '</div>';
	}

	// Case law references.
	if ( $args['show_case_law'] && ! empty( $case_law_references ) ) {
		$html .= '<div class="lexi-case-law">';
		$html .= '<h3>' . esc_html__( 'Case Law', 'letlexi' ) . '</h3>';
		$html .= '<ul>';

		foreach ( $case_law_references as $case ) {
			if ( isset( $case['case_title'] ) && ! empty( $case['case_title'] ) ) {
				$html .= '<li>' . esc_html( sanitize_text_field( $case['case_title'] ) ) . '</li>';
			}
		}

		$html .= '</ul>';
		$html .= '</div>';
	}

	// Amendment history.
	if ( $args['show_amendments'] && ! empty( $amendment_history ) ) {
		$html .= '<div class="lexi-amendments">';
		$html .= '<h3>' . esc_html__( 'Amendment History', 'letlexi' ) . '</h3>';
		$html .= '<ul>';

		foreach ( $amendment_history as $amendment ) {
			if ( isset( $amendment['amendment_text'] ) && ! empty( $amendment['amendment_text'] ) ) {
				$html .= '<li>' . esc_html( sanitize_text_field( $amendment['amendment_text'] ) ) . '</li>';
			}
		}

		$html .= '</ul>';
		$html .= '</div>';
	}

	$html .= '</div>';

	/**
	 * Filter the section HTML before returning
	 *
	 * @since 1.0.0
	 *
	 * @param string $html    The section HTML.
	 * @param int    $post_id The post ID.
	 * @param int    $index   The section index.
	 * @param array  $args    The rendering arguments.
	 */
	return apply_filters( 'letlexi/section_html', $html, $post_id, $index, $args );
}

/**
 * Build the complete shell HTML for section navigation
 *
 * @since 1.0.0
 *
 * @param int   $post_id  The post ID containing the sections.
 * @param array $settings Widget/shortcode settings for customization.
 * @return string The complete HTML shell structure.
 */
function lexi_build_shell_html( $post_id, $settings = array() ) {
	// Validate post type.
	if ( ! lexi_is_constitution_article( $post_id ) ) {
		return '<div class="lexi-error">' . esc_html__( 'Invalid post type for section navigation.', 'letlexi' ) . '</div>';
	}

	// Get sections for TOC and validation.
	$sections = lexi_get_sections( $post_id );
	if ( empty( $sections ) ) {
		return '<div class="lexi-error">' . esc_html__( 'No sections found for this document.', 'letlexi' ) . '</div>';
	}

	// Resolve settings with defaults.
	$resolved_settings = lexi_resolve_shell_settings( $settings );

	// Get article-level display settings.
	$display_args = lexi_get_article_display_args( $post_id );

	// Override with widget/shortcode settings if provided.
	$display_args = lexi_merge_display_settings( $display_args, $resolved_settings );

	// Build components.
	$header_html = lexi_build_reader_header( $post_id, $resolved_settings );
	$toc_html = lexi_build_toc_structure( $sections, $resolved_settings );
	$font_controls_html = lexi_build_font_controls( $resolved_settings );
	$content_html = lexi_build_content_area( $post_id, $display_args, $resolved_settings );
	$footer_nav_html = lexi_build_footer_navigation( $post_id, $resolved_settings, count( $sections ) );

	// Assemble the complete shell with 2-column layout.
	$html = '<div class="lexi-doc">';

	// Left Column: TOC.
	$html .= '<div class="lexi-toc">';
	$html .= '<button type="button" class="lexi-toc__toggle" aria-expanded="false" aria-controls="lexi-toc-list-' . esc_attr( $post_id ) . '">';
	$html .= esc_html( $resolved_settings['toc_heading'] );
	$html .= '</button>';
	$html .= '<ul class="lexi-toc__list" id="lexi-toc-list-' . esc_attr( $post_id ) . '">';
	$html .= $toc_html;
	$html .= '</ul>';
	$html .= '</div>';

	// Right Column: Document Info, Controls, and Main Content.
	$html .= '<div class="lexi-doc__main">';
	
	// Document info and controls above content.
	$html .= $header_html;
	$html .= $font_controls_html;
	
	// Main content area.
	$html .= '<div class="lexi-doc__body">';
	$html .= $content_html;
	$html .= '</div>';

	// ARIA Live Region for announcements.
	$html .= '<div id="lexi-announcer-' . esc_attr( $post_id ) . '" class="lexi-announcer" aria-live="polite" aria-atomic="true"></div>';

	// Footer Navigation.
	$html .= $footer_nav_html;
	$html .= '</div>'; // .lexi-doc__main.

	$html .= '</div>'; // .lexi-doc.

	/**
	 * Filter the complete shell HTML before returning
	 *
	 * @since 1.0.0
	 *
	 * @param string $html    The shell HTML.
	 * @param int    $post_id The post ID.
	 * @param array  $settings The resolved settings.
	 */
	return apply_filters( 'letlexi/shell_html', $html, $post_id, $resolved_settings );
}

/**
 * Resolve shell settings with defaults
 *
 * @since 1.0.0
 *
 * @param array $settings Input settings.
 * @return array Resolved settings with defaults.
 */
function lexi_resolve_shell_settings( $settings ) {
	$defaults = array(
		'document_label'        => __( 'Document:', 'letlexi' ),
		'query_format'          => '%constitution% Art. %article%, Section %section%',
		'print_label'           => __( 'Print', 'letlexi' ),
		'copy_citation_label'   => __( 'Copy Citation', 'letlexi' ),
		'toc_heading'           => __( 'Table of Contents', 'letlexi' ),
		'previous_label'        => __( 'Previous', 'letlexi' ),
		'next_label'            => __( 'Next', 'letlexi' ),
		'show_commentary'       => true,
		'show_cross_refs'       => true,
		'show_case_law'         => true,
		'show_amendments'       => true,
		'loading_strategy'      => 'ajax',
		'font_increase_label'   => __( 'Increase Font Size', 'letlexi' ),
		'font_decrease_label'   => __( 'Decrease Font Size', 'letlexi' ),
		'font_reset_label'      => __( 'Reset Font Size', 'letlexi' ),
	);

	return wp_parse_args( $settings, $defaults );
}

/**
 * Get article-level display arguments from ACF fields
 *
 * @since 1.0.0
 *
 * @param int $post_id The post ID to get display settings from.
 * @return array Array of display arguments.
 */
function lexi_get_article_display_args( $post_id ) {
	$args = array(
		'show_commentary'  => true,
		'show_cross_refs'  => true,
		'show_case_law'    => true,
		'show_amendments'  => true,
	);

	// Check if ACF is available.
	if ( ! function_exists( 'get_field' ) ) {
		return $args;
	}

	// Get article-level toggles.
	$show_commentary = get_field( 'show_commentary', $post_id );
	if ( null !== $show_commentary ) {
		$args['show_commentary'] = lexi_bool( $show_commentary );
	}

	$show_cross_references = get_field( 'show_cross_references', $post_id );
	if ( null !== $show_cross_references ) {
		$args['show_cross_refs'] = lexi_bool( $show_cross_references );
	}

	$show_case_law = get_field( 'show_case_law', $post_id );
	if ( null !== $show_case_law ) {
		$args['show_case_law'] = lexi_bool( $show_case_law );
	}

	$show_amendment_history = get_field( 'show_amendment_history', $post_id );
	if ( null !== $show_amendment_history ) {
		$args['show_amendments'] = lexi_bool( $show_amendment_history );
	}

	return $args;
}

/**
 * Merge display settings from different sources
 *
 * @since 1.0.0
 *
 * @param array $article_settings Article-level ACF settings.
 * @param array $widget_settings  Widget/shortcode settings.
 * @return array Merged display settings.
 */
function lexi_merge_display_settings( $article_settings, $widget_settings ) {
	// Widget/shortcode settings take precedence.
	if ( isset( $widget_settings['show_commentary'] ) ) {
		$article_settings['show_commentary'] = lexi_bool( $widget_settings['show_commentary'] );
	}
	if ( isset( $widget_settings['show_cross_refs'] ) ) {
		$article_settings['show_cross_refs'] = lexi_bool( $widget_settings['show_cross_refs'] );
	}
	if ( isset( $widget_settings['show_case_law'] ) ) {
		$article_settings['show_case_law'] = lexi_bool( $widget_settings['show_case_law'] );
	}
	if ( isset( $widget_settings['show_amendments'] ) ) {
		$article_settings['show_amendments'] = lexi_bool( $widget_settings['show_amendments'] );
	}

	return $article_settings;
}

/**
 * Build reader header with document information
 *
 * @since 1.0.0
 *
 * @param int   $post_id  The post ID.
 * @param array $settings Resolved settings.
 * @return string Header HTML.
 */
function lexi_build_reader_header( $post_id, $settings ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}

	$html = '<div class="lexi-reader-header">';

	// Document label and title.
	$html .= '<div class="lexi-document-info">';
	$html .= '<span class="lexi-document-label">' . esc_html( $settings['document_label'] ) . '</span>';
	$html .= '<h1 class="lexi-document-title">' . esc_html( $post->post_title ) . '</h1>';
	$html .= '</div>';

	// Query format display.
	if ( ! empty( $settings['query_format'] ) ) {
		$query_display = lexi_format_query_string( $settings['query_format'], $post );
		$html .= '<div class="lexi-query-display">';
		$html .= '<span class="lexi-query-label">' . esc_html__( 'Query:', 'letlexi' ) . '</span>';
		$html .= '<span class="lexi-query-text">' . esc_html( $query_display ) . '</span>';
		$html .= '</div>';
	}

	// Action buttons.
	$html .= '<div class="lexi-document-actions">';
	$html .= '<button type="button" class="lexi-print-btn" aria-label="' . esc_attr( $settings['print_label'] ) . '">';
	$html .= esc_html( $settings['print_label'] );
	$html .= '</button>';
	$html .= '<button type="button" class="lexi-copy-citation-btn" aria-label="' . esc_attr( $settings['copy_citation_label'] ) . '">';
	$html .= esc_html( $settings['copy_citation_label'] );
	$html .= '</button>';
	$html .= '</div>';

	$html .= '</div>';

	return $html;
}

/**
 * Format query string with placeholders
 *
 * @since 1.0.0
 *
 * @param string $query_format The query format string.
 * @param WP_Post $post The post object.
 * @return string Formatted query string.
 */
function lexi_format_query_string( $query_format, $post ) {
	// Get post meta for placeholders.
	$constitution = get_post_meta( $post->ID, 'constitution', true );
	$article = get_post_meta( $post->ID, 'article', true );
	$section = get_post_meta( $post->ID, 'section', true );

	// Replace placeholders.
	$formatted = str_replace( '%constitution%', $constitution ?: __( 'Constitution', 'letlexi' ), $query_format );
	$formatted = str_replace( '%article%', $article ?: '1', $formatted );
	$formatted = str_replace( '%section%', $section ?: '1', $formatted );

	return $formatted;
}

/**
 * Build Table of Contents structure
 *
 * @since 1.0.0
 *
 * @param array $sections Array of sections.
 * @param array $settings Resolved settings.
 * @return string TOC HTML.
 */
function lexi_build_toc_structure( $sections, $settings ) {
	$html = '';

	foreach ( $sections as $index => $section ) {
		$section_number = isset( $section['section_number'] ) ? sanitize_text_field( $section['section_number'] ) : '';
		$section_title  = isset( $section['section_title'] ) ? sanitize_text_field( $section['section_title'] ) : '';

		$html .= '<li>';
		$html .= '<a href="#" class="lexi-toc__link" data-index="' . esc_attr( $index ) . '" aria-label="';

		if ( ! empty( $section_number ) && ! empty( $section_title ) ) {
			$html .= esc_attr( sprintf(
				/* translators: %1$s: section number, %2$s: section title */
				__( 'Go to Section %1$s: %2$s', 'letlexi' ),
				$section_number,
				$section_title
			) );
			$html .= '">';
			$html .= sprintf(
				/* translators: %1$s: section number, %2$s: section title */
				esc_html__( 'Section %1$s. %2$s', 'letlexi' ),
				esc_html( $section_number ),
				esc_html( $section_title )
			);
		} elseif ( ! empty( $section_number ) ) {
			$html .= esc_attr( sprintf(
				/* translators: %s: section number */
				__( 'Go to Section %s', 'letlexi' ),
				$section_number
			) );
			$html .= '">';
			$html .= sprintf(
				/* translators: %s: section number */
				esc_html__( 'Section %s', 'letlexi' ),
				esc_html( $section_number )
			);
		} else {
			$html .= esc_attr( sprintf(
				/* translators: %s: section title */
				__( 'Go to %s', 'letlexi' ),
				$section_title
			) );
			$html .= '">';
			$html .= esc_html( $section_title );
		}

		$html .= '</a>';
		$html .= '</li>';
	}

	return $html;
}

/**
 * Build font size controls
 *
 * @since 1.0.0
 *
 * @param array $settings Resolved settings.
 * @return string Font controls HTML.
 */
function lexi_build_font_controls( $settings ) {
	$html = '<div class="lexi-font-controls lexi-font">';
	$html .= '<span class="lexi-font-label">' . esc_html__( 'Font Size:', 'letlexi' ) . '</span>';

	$html .= '<button type="button" class="lexi-font__dec" aria-label="' . esc_attr( $settings['font_decrease_label'] ) . '">';
	$html .= '<span aria-hidden="true">A</span>';
	$html .= '</button>';

	$html .= '<button type="button" class="lexi-font__inc" aria-label="' . esc_attr( $settings['font_increase_label'] ) . '">';
	$html .= '<span aria-hidden="true">A</span>';
	$html .= '</button>';

	$html .= '<button type="button" class="lexi-font__reset" aria-label="' . esc_attr( $settings['font_reset_label'] ) . '">';
	$html .= esc_html__( 'Reset', 'letlexi' );
	$html .= '</button>';

	$html .= '</div>';

	return $html;
}

/**
 * Build content area with initial section
 *
 * @since 1.0.0
 *
 * @param int   $post_id      The post ID.
 * @param array $display_args Display arguments.
 * @param array $settings     Resolved settings.
 * @return string Content area HTML.
 */
function lexi_build_content_area( $post_id, $display_args, $settings ) {
	// Build initial section content based on loading strategy.
	if ( $settings['loading_strategy'] === 'preload' ) {
		// Server-side render first section.
		return lexi_render_section_html( $post_id, 0, $display_args );
	} else {
		// AJAX loading - show loading state.
		return '<div class="lexi-loading" aria-live="polite">' . 
			   esc_html__( 'Loading section...', 'letlexi' ) . 
			   '</div>';
	}
}

/**
 * Build footer navigation controls
 *
 * @since 1.0.0
 *
 * @param int   $post_id        The post ID for unique element IDs.
 * @param array $settings       Resolved settings.
 * @param int   $total_sections Total number of sections.
 * @return string Footer navigation HTML.
 */
function lexi_build_footer_navigation( $post_id, $settings, $total_sections ) {
	$html = '<div class="lexi-nav lexi-footer-nav">';

	// Previous button.
	$html .= '<button type="button" class="lexi-nav__prev" disabled aria-label="' . esc_attr( $settings['previous_label'] ) . '">';
	$html .= '<span aria-hidden="true">←</span> ';
	$html .= esc_html( $settings['previous_label'] );
	$html .= '</button>';

	// Jump select.
	$html .= '<div class="lexi-jump">';
	$html .= '<label for="lexi-jump-select-' . esc_attr( $post_id ) . '">' . esc_html__( 'Jump to:', 'letlexi' ) . '</label>';
	$html .= '<select id="lexi-jump-select-' . esc_attr( $post_id ) . '" class="lexi-jump__select" aria-label="' . esc_attr__( 'Select section to jump to', 'letlexi' ) . '">';

	for ( $i = 0; $i < $total_sections; $i++ ) {
		$html .= '<option value="' . esc_attr( $i ) . '">';
		$html .= sprintf(
			/* translators: %d: section number */
			esc_html__( 'Section %d', 'letlexi' ),
			$i + 1
		);
		$html .= '</option>';
	}

	$html .= '</select>';
	$html .= '</div>';

	// Next button.
	$html .= '<button type="button" class="lexi-nav__next" aria-label="' . esc_attr( $settings['next_label'] ) . '">';
	$html .= esc_html( $settings['next_label'] );
	$html .= ' <span aria-hidden="true">→</span>';
	$html .= '</button>';

	$html .= '</div>';

	return $html;
}
