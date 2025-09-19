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
	// Prefer manual sections if provided.
	$manual_sections = isset( $args['manual_sections'] ) && is_array( $args['manual_sections'] ) ? $args['manual_sections'] : null;

	if ( null !== $manual_sections ) {
		$sections = $manual_sections;
	} else {
		// Validate post type.
		if ( ! lexi_supports_section_navigation( $post_id ) ) {
			return '';
		}

		// Load all sections.
		$sections = lexi_get_sections( $post_id );
		if ( empty( $sections ) || ! is_array( $sections ) ) {
			return '';
		}
	}

	// Sanitize and validate index.
	$index = lexi_sanitize_section_index( $index, count( $sections ) );
	$section = $sections[ $index ];

	if ( ! is_array( $section ) ) {
		return '';
	}

	// Parse arguments with defaults.
	$defaults = array(
		'show_commentary'   => true,
		'show_cross_refs'   => true,
		'classes'           => 'lexi-section',
		'history_heading'   => __( 'History', 'letlexi' ),
		'annotations_label' => __( 'Annotations', 'letlexi' ),
	);

	$args = wp_parse_args( $args, $defaults );

	// Normalize boolean arguments.
	$args['show_commentary'] = lexi_bool( $args['show_commentary'] );
	$args['show_cross_refs'] = lexi_bool( $args['show_cross_refs'] );

	// Extract and sanitize section fields.
	$section_number   = isset( $section['section_number'] ) ? sanitize_text_field( $section['section_number'] ) : '';
	$section_title    = isset( $section['section_title'] ) ? sanitize_text_field( $section['section_title'] ) : '';
	$section_content  = isset( $section['section_content'] ) ? wp_kses_post( $section['section_content'] ) : '';
	$section_commentary = isset( $section['section_commentary'] ) ? wp_kses_post( $section['section_commentary'] ) : '';
	$section_source_note = isset( $section['source_note'] ) ? sanitize_textarea_field( $section['source_note'] ) : '';
	$section_cross_references = isset( $section['section_cross_references'] ) ? wp_kses_post( $section['section_cross_references'] ) : '';

	// Extract repeater fields (legacy support for cross references only).
	$cross_references = isset( $section['cross_references'] ) && is_array( $section['cross_references'] ) ? $section['cross_references'] : array();

	// Sanitize wrapper classes.
	$classes = sanitize_html_class( $args['classes'] );

	// Start building HTML.
	$html = '<div class="' . esc_attr( $classes ) . '" data-section-index="' . esc_attr( $index ) . '" data-index="' . esc_attr( $index ) . '"';

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

		$html .= '</div>';
	}

	// Main content (the law text)
	if ( ! empty( $section_content ) ) {
		$html .= '<div class="lexi-section-content">';
		$html .= $section_content; // Already sanitized with wp_kses_post.
		$html .= '</div>';
	}

	// Section History (source note) under its own heading, separate from Annotations
	if ( ! empty( $section_source_note ) ) {
		$history_heading = isset( $args['history_heading'] ) && $args['history_heading'] !== '' ? sanitize_text_field( $args['history_heading'] ) : __( 'History', 'letlexi' );
		$html .= '<div class="lexi-section-history">';
		$html .= '<h3>' . esc_html( $history_heading ) . '</h3>';
		$html .= '<p class="lexi-source-note">' . esc_html( $section_source_note ) . '</p>';
		$html .= '</div>';
	}

	// Annotations tab: includes State Notes, Annotation, and Research References within a full-width tab UI.
	$has_annotations = ( $args['show_commentary'] && ! empty( $section_commentary ) ) || ( $args['show_cross_refs'] && ( ! empty( $section_cross_references ) || ! empty( $cross_references ) ) );
	if ( $has_annotations ) {
		$tab_label = isset( $args['annotations_label'] ) && $args['annotations_label'] !== '' ? sanitize_text_field( $args['annotations_label'] ) : __( 'Annotations', 'letlexi' );
		$tab_id    = 'annotations-tab-' . $post_id . '-' . $index;
		$panel_id  = 'annotations-panel-' . $post_id . '-' . $index;

		$html .= '<div class="lexi-annotations">';
		// Tab header (full width, active by default)
		$html .= '<div class="lexi-tabs" role="tablist">';
		$html .= '<button type="button" class="lexi-tab is-active" id="' . esc_attr( $tab_id ) . '" role="tab" aria-selected="true" aria-controls="' . esc_attr( $panel_id ) . '">';
		$html .= esc_html( $tab_label );
		$html .= '</button>';
		$html .= '</div>';

		// Tab panel content
		$html .= '<div id="' . esc_attr( $panel_id ) . '" class="lexi-tab-panel is-active" role="tabpanel" aria-labelledby="' . esc_attr( $tab_id ) . '">';

		$need_divider = false;

		// Annotation (commentary)
		if ( $args['show_commentary'] && ! empty( $section_commentary ) ) {
			if ( $need_divider ) { $html .= '<div class="lexi-divider" role="separator" aria-hidden="true"></div>'; }
			$html .= '<div class="lexi-annotation-block lexi-annotation--commentary">';
			$html .= '<div class="lexi-commentary-content">';
			$html .= $section_commentary; // Already sanitized with wp_kses_post.
			$html .= '</div>';
			$html .= '</div>';
			$need_divider = false;
		}

		// Research References & Practice Aids (new WYSIWYG and legacy fallback)
		if ( $args['show_cross_refs'] && ( ! empty( $section_cross_references ) || ! empty( $cross_references ) ) ) {
			if ( $need_divider ) {
				$html .= '<div class="lexi-divider" role="separator" aria-hidden="true"></div>';
			}
			$html .= '<div class="lexi-annotation-block lexi-annotation--references">';
			$html .= '<h3>' . esc_html__( 'Research References & Practice Aids', 'letlexi' ) . '</h3>';

			$html .= '<div class="lexi-cross-refs-content">';
			if ( ! empty( $section_cross_references ) ) {
				$html .= $section_cross_references; // Already sanitized with wp_kses_post.
			}
			if ( empty( $section_cross_references ) && ! empty( $cross_references ) ) {
				$html .= '<ul>';
				foreach ( $cross_references as $ref ) {
					if ( isset( $ref['reference_text'] ) && ! empty( $ref['reference_text'] ) ) {
						$html .= '<li>' . esc_html( sanitize_text_field( $ref['reference_text'] ) ) . '</li>';
					}
				}
				$html .= '</ul>';
			}
			$html .= '</div>';
			$html .= '</div>';
		}

		// Add section divider for clear separation between sections (inside tab panel so it hides with annotations)
		$html .= '<div class="lexi-section-divider" role="separator" aria-hidden="true"></div>';

		$html .= '</div>'; // .lexi-tab-panel
		$html .= '</div>'; // .lexi-annotations
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
 * @param array $settings Widget settings for customization.
 * @return string The complete HTML shell structure.
 */
function lexi_build_shell_html( $post_id, $settings = array() ) {
	// Detect manual sections mode.
	$manual_sections = isset( $settings['manual_sections'] ) && is_array( $settings['manual_sections'] ) ? $settings['manual_sections'] : null;

	if ( null !== $manual_sections ) {
		$sections = $manual_sections;
	} else {
		// Validate post type.
		if ( ! lexi_supports_section_navigation( $post_id ) ) {
			return '<div class="lexi-error">' . esc_html__( 'This post does not support section navigation. Please ensure it has ACF sections and is a supported post type.', 'letlexi' ) . '</div>';
		}

		// Get sections for TOC and validation.
		$sections = lexi_get_sections( $post_id );
		if ( empty( $sections ) ) {
			return '<div class="lexi-error">' . esc_html__( 'No sections found for this document.', 'letlexi' ) . '</div>';
		}
	}

	// Resolve settings with defaults.
	$resolved_settings = lexi_resolve_shell_settings( $settings );

	// Get article-level display settings.
	$display_args = lexi_get_article_display_args( $post_id );

	// Override with widget settings if provided.
	$display_args = lexi_merge_display_settings( $display_args, $resolved_settings );

	// Pass through UI labels (History and Annotations) to section renderer
	if ( isset( $resolved_settings['history_heading'] ) ) {
		$display_args['history_heading'] = $resolved_settings['history_heading'];
	}
	if ( isset( $resolved_settings['annotations_label'] ) ) {
		$display_args['annotations_label'] = $resolved_settings['annotations_label'];
	}

	// Build components.
	$header_html = lexi_build_reader_header( $post_id, $resolved_settings );
	$toc_html = lexi_build_toc_structure( $sections, $resolved_settings, $post_id );
	
	// Build navigation components based on visibility settings
	$top_nav_html = '';
	$footer_nav_html = '';
	
	if ( isset( $resolved_settings['show_top_navigation'] ) && $resolved_settings['show_top_navigation'] === 'yes' ) {
		$top_nav_html = lexi_build_top_navigation( $post_id, $resolved_settings, count( $sections ) );
	}
	
	if ( isset( $resolved_settings['show_bottom_navigation'] ) && $resolved_settings['show_bottom_navigation'] === 'yes' ) {
		$footer_nav_html = lexi_build_footer_navigation( $post_id, $resolved_settings, count( $sections ) );
	}
	
	$content_html = lexi_build_content_area( $post_id, $display_args, $resolved_settings, $sections );

	// Assemble the complete shell; add layout classes.
	$doc_classes = array( 'lexi-doc' );
	$position = isset( $resolved_settings['toc_position'] ) ? $resolved_settings['toc_position'] : 'left';
	if ( $position === 'right' ) {
		$doc_classes[] = 'lexi-toc-right';
	} elseif ( $position === 'below' ) {
		$doc_classes[] = 'lexi-toc-below';
	} else {
		$doc_classes[] = 'lexi-toc-left';
	}
	// Allow widget/site to define an extra sticky offset for scrolling (e.g., sticky headers)
	$widget_offset = 0;
	if ( isset( $resolved_settings['sticky_scroll_offset'] ) && $resolved_settings['sticky_scroll_offset'] !== '' ) {
		$raw_offset = $resolved_settings['sticky_scroll_offset'];
		if ( is_array( $raw_offset ) ) {
			$size = isset( $raw_offset['size'] ) ? floatval( $raw_offset['size'] ) : 0;
			$unit = isset( $raw_offset['unit'] ) ? strtolower( (string) $raw_offset['unit'] ) : 'px';
			if ( $size > 0 ) {
				if ( 'rem' === $unit ) {
					$widget_offset = (int) round( $size * 16 ); // assume 1rem = 16px
				} else {
					$widget_offset = (int) round( $size );
				}
			}
		} else {
			$widget_offset = intval( $raw_offset );
		}
	}
	$sticky_offset = apply_filters( 'letlexi/sticky_offset', $widget_offset, $post_id );
	$sticky_attr = '';
	if ( is_numeric( $sticky_offset ) && intval( $sticky_offset ) > 0 ) {
		$sticky_attr = ' data-sticky-offset="' . esc_attr( intval( $sticky_offset ) ) . '"';
	}

	$html = '<div class="' . esc_attr( implode( ' ', $doc_classes ) ) . '"' . $sticky_attr . '>';

	// TOC Pane.
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

	// View mode toggle: Full Text / Single Section
	$html .= '<div class="lexi-view-toggle" role="group" aria-label="View mode">';
	$html .= '<button type="button" class="lexi-view-toggle__btn is-active" data-view="full">' . esc_html__( 'Full text', 'letlexi' ) . '</button>';
	$html .= '<button type="button" class="lexi-view-toggle__btn" data-view="single">' . esc_html__( 'Single section', 'letlexi' ) . '</button>';
	$html .= '</div>';
	
	// Top navigation.
	$html .= $top_nav_html;
	
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
		'print_label'           => __( 'Print', 'letlexi' ),
		'copy_citation_label'   => __( 'Copy Citation', 'letlexi' ),
		'toc_heading'           => __( 'Table of Contents', 'letlexi' ),
		'previous_label'        => __( 'Previous', 'letlexi' ),
		'next_label'            => __( 'Next', 'letlexi' ),
		'show_commentary'       => true,
		'show_cross_refs'       => true,
		'loading_strategy'      => 'ajax',
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
	);

	// Check if ACF is available.
	if ( ! function_exists( 'get_field' ) ) {
		return $args;
	}

	// Get article-level display settings from the new ACF structure.
	// These are now controlled by the display_style field and individual field presence.
	$display_style = get_field( 'display_style', $post_id );
	
	// Check if article-level fields exist to determine what to show.
	$article_commentary = get_field( 'article_commentary', $post_id );
	$article_cross_references = get_field( 'article_cross_references', $post_id );
	
	// Set defaults based on display style and field presence.
	if ( $display_style ) {
		switch ( $display_style ) {
			case 'full':
				$args['show_commentary'] = true;
				$args['show_cross_refs'] = true;
				break;
			case 'sections_only':
				$args['show_commentary'] = false;
				$args['show_cross_refs'] = false;
				break;
			case 'commentary_only':
				$args['show_commentary'] = true;
				$args['show_cross_refs'] = false;
				break;
			case 'combined':
				$args['show_commentary'] = true;
				$args['show_cross_refs'] = true;
				break;
			case 'minimal':
				$args['show_commentary'] = false;
				$args['show_cross_refs'] = false;
				break;
		}
	}
	
	// Override based on field presence (if fields exist, show them).
	if ( ! empty( $article_commentary ) ) {
		$args['show_commentary'] = true;
	}
	if ( ! empty( $article_cross_references ) ) {
		$args['show_cross_refs'] = true;
	}

	return $args;
}

/**
 * Merge display settings from different sources
 *
 * @since 1.0.0
 *
 * @param array $article_settings Article-level ACF settings.
 * @param array $widget_settings  Widget settings.
 * @return array Merged display settings.
 */
function lexi_merge_display_settings( $article_settings, $widget_settings ) {
	// Widget settings take precedence.
	if ( isset( $widget_settings['show_commentary'] ) ) {
		$article_settings['show_commentary'] = lexi_bool( $widget_settings['show_commentary'] );
	}
	if ( isset( $widget_settings['show_cross_refs'] ) ) {
		$article_settings['show_cross_refs'] = lexi_bool( $widget_settings['show_cross_refs'] );
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
	$html .= '<h1 class="lexi-document-title">' . esc_html( lexi_extract_article_name( $post->post_title ) ) . '</h1>';
	$html .= '</div>';


	// Action buttons (conditional based on settings).
	$show_print = ! isset( $settings['show_print_button'] ) || $settings['show_print_button'] === 'yes';
	$show_copy = ! isset( $settings['show_copy_button'] ) || $settings['show_copy_button'] === 'yes';
	
	if ( $show_print || $show_copy ) {
		$html .= '<div class="lexi-document-actions">';
		
		if ( $show_print ) {
			$html .= '<button type="button" class="lexi-print-btn" aria-label="' . esc_attr( $settings['print_label'] ) . '">';
			$html .= esc_html( $settings['print_label'] );
			$html .= '</button>';
		}
		
		if ( $show_copy ) {
			$html .= '<button type="button" class="lexi-copy-citation-btn" aria-label="' . esc_attr( $settings['copy_citation_label'] ) . '">';
			$html .= esc_html( $settings['copy_citation_label'] );
			$html .= '</button>';
		}
		
		$html .= '</div>';
	}

	$html .= '</div>';

	return $html;
}

/**
 * Extract article name from post title format: "%constitution_type% - %Article name%"
 *
 * @since 1.0.0
 *
 * @param string $post_title The full post title.
 * @return string The article name only.
 */
function lexi_extract_article_name( $post_title ) {
	if ( empty( $post_title ) ) {
		return __( 'Article', 'letlexi' );
	}
	
	// Split by " - " and take the last part (article name)
	$parts = explode( ' - ', $post_title );
	if ( count( $parts ) > 1 ) {
		return trim( end( $parts ) );
	}
	
	// If no " - " found, return the original title
	return $post_title;
}

/**
 * Build Table of Contents structure
 *
 * @since 1.0.0
 *
 * @param array $sections Array of sections.
 * @param array $settings Resolved settings.
 * @param int   $post_id  The post ID to get the document title from.
 * @return string TOC HTML.
 */
function lexi_build_toc_structure( $sections, $settings, $post_id ) {
	$html = '';

	// Get the document title and extract article name
	$post = get_post( $post_id );
	$full_title = $post ? $post->post_title : __( 'Article', 'letlexi' );
	$document_title = lexi_extract_article_name( $full_title );

	// Check if article has content that should be treated as a section
	$has_article_content = lexi_has_article_content( $post_id );

	// Add Article link at the top (only if no article content section)
	if ( ! $has_article_content ) {
		$html .= '<li>';
		$html .= '<a href="#" class="lexi-toc__link lexi-toc__link--article" data-index="-1" aria-label="' . esc_attr( sprintf( __( 'Go to top of %s', 'letlexi' ), $full_title ) ) . '">';
		$html .= esc_html( $document_title );
		$html .= '</a>';
		$html .= '</li>';
	}

	// Add article content as first section if it exists
	if ( $has_article_content ) {
		$html .= '<li>';
		$html .= '<a href="#" class="lexi-toc__link lexi-toc__link--article" data-index="-1" aria-label="' . esc_attr( sprintf( __( 'Go to %s', 'letlexi' ), $full_title ) ) . '">';
		$html .= esc_html( $document_title );
		$html .= '</a>';
		$html .= '</li>';
	}

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
 * Build content area with initial section
 *
 * @since 1.0.0
 *
 * @param int   $post_id      The post ID.
 * @param array $display_args Display arguments.
 * @param array $settings     Resolved settings.
 * @return string Content area HTML.
 */
function lexi_build_content_area( $post_id, $display_args, $settings, $sections = null ) {
	// Determine sections source.
	$has_manual = isset( $settings['manual_sections'] ) && is_array( $settings['manual_sections'] );

	if ( $has_manual ) {
		$sections = $settings['manual_sections'];
	}

	if ( null === $sections ) {
		$sections = lexi_get_sections( $post_id );
	}

	if ( empty( $sections ) || ! is_array( $sections ) ) {
		return '<div class="lexi-error">' . esc_html__( 'No sections found for this document.', 'letlexi' ) . '</div>';
	}

	// Render all sections server-side for full-text view.
	$html = '';
	
	// First, render article content as a section if it exists
	if ( lexi_has_article_content( $post_id ) ) {
		$html .= lexi_render_article_section_html( $post_id, $display_args );
	}
	
	// Then render regular sections
	$count = count( $sections );
	for ( $i = 0; $i < $count; $i++ ) {
		$html .= lexi_render_section_html( $post_id, $i, $display_args );
	}

	return $html;
}

/**
 * Build top navigation controls
 *
 * @since 1.0.0
 *
 * @param int   $post_id        The post ID for unique element IDs.
 * @param array $settings       Resolved settings.
 * @param int   $total_sections Total number of sections.
 * @return string Top navigation HTML.
 */
function lexi_build_top_navigation( $post_id, $settings, $total_sections ) {
	$html = '<div class="lexi-nav lexi-top-nav">';

	// Previous button.
	$html .= '<button type="button" class="lexi-nav__prev" disabled aria-label="' . esc_attr( $settings['previous_label'] ) . '">';
	$html .= '<span aria-hidden="true">←</span> ';
	$html .= esc_html( $settings['previous_label'] );
	$html .= '</button>';

	// Jump select.
	$html .= '<div class="lexi-jump">';
	$html .= '<label for="lexi-jump-select-top-' . esc_attr( $post_id ) . '">' . esc_html__( 'Jump to:', 'letlexi' ) . '</label>';
	$html .= '<select id="lexi-jump-select-top-' . esc_attr( $post_id ) . '" class="lexi-jump__select" aria-label="' . esc_attr__( 'Select section to jump to', 'letlexi' ) . '">';

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

/**
 * Build article-level information section
 *
 * @since 1.2.0
 *
 * @param int   $post_id  The post ID.
 * @param array $settings Resolved settings.
 * @return string Article info HTML.
 */
function lexi_build_article_info( $post_id, $settings ) {
	// Check if ACF is available.
	if ( ! function_exists( 'get_field' ) ) {
		return '';
	}

	$html = '';
	$has_content = false;

	// Get article-level fields.
	$constitution_type = get_field( 'constitution_type', $post_id );
	$article_name = get_field( 'article_name', $post_id );
	$article_number = get_field( 'article_number', $post_id );
	$article_order = get_field( 'article_order', $post_id );
	$article_status = get_field( 'article_status', $post_id );
	$article_commentary = get_field( 'article_commentary', $post_id );
	$article_source_note = get_field( 'article_source_note', $post_id );
	$article_cross_references = get_field( 'article_cross_references', $post_id );
	$effective_date = get_field( 'effective_date', $post_id );
	$last_updated = get_field( 'last_updated', $post_id );
	$version = get_field( 'version', $post_id );

	// Start building HTML if we have content.
	if ( ! empty( $article_commentary ) || ! empty( $article_cross_references ) || ! empty( $article_source_note ) ) {
		$html .= '<div class="lexi-article-info">';
		$has_content = true;

		// Article commentary (Editor's Note).
		if ( ! empty( $article_commentary ) ) {
			$html .= '<div class="lexi-article-commentary">';
			$html .= '<h3>' . esc_html__( 'Notes:', 'letlexi' ) . '</h3>';
			$html .= '<div class="lexi-commentary-content">';
			$html .= wp_kses_post( $article_commentary );
			$html .= '</div>';
			$html .= '</div>';
		}

		// Article source note (State Notes).
		if ( ! empty( $article_source_note ) ) {
			$html .= '<div class="lexi-article-source-note">';
			$html .= '<h3>' . esc_html__( 'State Notes', 'letlexi' ) . '</h3>';
			$html .= '<div class="lexi-source-note-content">';
			$html .= '<h4>' . esc_html__( 'Notes', 'letlexi' ) . '</h4>';
			$html .= '<p class="lexi-source-note">' . esc_html( $article_source_note ) . '</p>';
			$html .= '</div>';
			$html .= '</div>';
		}

		// Article cross references (Research References & Practice Aids).
		if ( ! empty( $article_cross_references ) ) {
			$html .= '<div class="lexi-article-cross-references">';
			$html .= '<h3>' . esc_html__( 'Research References & Practice Aids', 'letlexi' ) . '</h3>';
			$html .= '<div class="lexi-cross-refs-content">';
			$html .= wp_kses_post( $article_cross_references );
			$html .= '</div>';
			$html .= '</div>';
		}

		// Article metadata (dates and version).
		if ( ! empty( $effective_date ) || ! empty( $last_updated ) || ! empty( $version ) ) {
			$html .= '<div class="lexi-article-dates">';
			
			if ( ! empty( $effective_date ) ) {
				$html .= '<span class="lexi-meta-item lexi-effective-date">';
				$html .= '<strong>' . esc_html__( 'Effective:', 'letlexi' ) . '</strong> ';
				$html .= esc_html( $effective_date );
				$html .= '</span>';
			}
			
			if ( ! empty( $last_updated ) ) {
				$html .= '<span class="lexi-meta-item lexi-last-updated">';
				$html .= '<strong>' . esc_html__( 'Updated:', 'letlexi' ) . '</strong> ';
				$html .= esc_html( $last_updated );
				$html .= '</span>';
			}
			
			$html .= '</div>';
		}

		$html .= '</div>';
	}

	return $html;
}

/**
 * Check if article has content that should be treated as a section
 *
 * @since 1.0.0
 *
 * @param int $post_id The post ID to check.
 * @return bool True if article has content to display as a section.
 */
function lexi_has_article_content( $post_id ) {
	// Check if ACF is available.
	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}

	// Check if any article-level fields have content.
	$article_commentary = get_field( 'article_commentary', $post_id );
	$article_source_note = get_field( 'article_source_note', $post_id );
	$article_cross_references = get_field( 'article_cross_references', $post_id );

	return ! empty( $article_commentary ) || ! empty( $article_source_note ) || ! empty( $article_cross_references );
}

/**
 * Render article content as a section with History/Annotations structure
 *
 * @since 1.0.0
 *
 * @param int   $post_id      The post ID.
 * @param array $display_args Display arguments.
 * @return string Article section HTML.
 */
function lexi_render_article_section_html( $post_id, $display_args ) {
	// Check if ACF is available.
	if ( ! function_exists( 'get_field' ) ) {
		return '';
	}

	// Get article-level fields.
	$article_commentary = get_field( 'article_commentary', $post_id );
	$article_source_note = get_field( 'article_source_note', $post_id );
	$article_cross_references = get_field( 'article_cross_references', $post_id );

	// Get the document title for the section heading
	$post = get_post( $post_id );
	$full_title = $post ? $post->post_title : __( 'Article', 'letlexi' );
	$document_title = lexi_extract_article_name( $full_title );

	// Generate unique IDs for this article section
	$section_id = 'lexi-article-section-' . $post_id;
	$tab_id = 'lexi-article-tab-' . $post_id;
	$panel_id = 'lexi-article-panel-' . $post_id;

	// Get tab label from settings
	$tab_label = isset( $display_args['annotations_label'] ) ? $display_args['annotations_label'] : __( 'Annotations', 'letlexi' );

	$html = '<div id="' . esc_attr( $section_id ) . '" class="lexi-section" data-index="-1">';

	// Main content (if any) - this would be the actual article text if it exists
	// For now, we'll focus on the annotations structure

	// Annotations container with tab
	if ( ( $display_args['show_commentary'] && ! empty( $article_commentary ) ) || 
		 ( $display_args['show_cross_refs'] && ! empty( $article_cross_references ) ) ||
		 ! empty( $article_source_note ) ) {

		$html .= '<div class="lexi-annotations">';
		$html .= '<div class="lexi-tabs" role="tablist">';
		$html .= '<button type="button" class="lexi-tab is-active" id="' . esc_attr( $tab_id ) . '" role="tab" aria-selected="true" aria-controls="' . esc_attr( $panel_id ) . '">';
		$html .= esc_html( $tab_label );
		$html .= '</button>';
		$html .= '</div>';

		// Tab panel content
		$html .= '<div id="' . esc_attr( $panel_id ) . '" class="lexi-tab-panel is-active" role="tabpanel" aria-labelledby="' . esc_attr( $tab_id ) . '">';

		$need_divider = false;

		// Notes (h4) - appears first
		if ( ! empty( $article_source_note ) ) {
			$html .= '<div class="lexi-annotation-block lexi-annotation--notes">';
			$html .= '<h4>' . esc_html__( 'Notes', 'letlexi' ) . '</h4>';
			$html .= '<p class="lexi-source-note">' . esc_html( $article_source_note ) . '</p>';
			$html .= '</div>';
			$need_divider = true;
		}

		// Editor's note (article commentary)
		if ( $display_args['show_commentary'] && ! empty( $article_commentary ) ) {
			if ( $need_divider ) { $html .= '<div class="lexi-divider" role="separator" aria-hidden="true"></div>'; }
			$html .= '<div class="lexi-annotation-block lexi-annotation--commentary">';
			$html .= '<h3>' . esc_html__( 'Notes:', 'letlexi' ) . '</h3>';
			$html .= '<div class="lexi-commentary-content">';
			$html .= wp_kses_post( $article_commentary );
			$html .= '</div>';
			$html .= '</div>';
			$need_divider = true;
		}

		// Research References & Practice Aids (article cross references)
		if ( $display_args['show_cross_refs'] && ! empty( $article_cross_references ) ) {
			if ( $need_divider ) {
				$html .= '<div class="lexi-divider" role="separator" aria-hidden="true"></div>';
			}
			$html .= '<div class="lexi-annotation-block lexi-annotation--references">';
			$html .= '<h3>' . esc_html__( 'Research References & Practice Aids', 'letlexi' ) . '</h3>';
			$html .= '<div class="lexi-cross-refs-content">';
			$html .= wp_kses_post( $article_cross_references );
			$html .= '</div>';
			$html .= '</div>';
		}

		// Add final divider after all content for clear section separation (inside tab panel so it hides with annotations)
		$html .= '<div class="lexi-section-divider" role="separator" aria-hidden="true"></div>';

		$html .= '</div>'; // .lexi-tab-panel
		$html .= '</div>'; // .lexi-annotations
	}

	$html .= '</div>'; // .lexi-section

	return $html;
}
