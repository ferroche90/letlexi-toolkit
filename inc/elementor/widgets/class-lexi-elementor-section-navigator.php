<?php
/**
 * Elementor Section Navigator Widget
 *
 * @package LetLexi\Toolkit\Elementor
 * @since 1.0.0
 */

namespace LetLexi\Toolkit\Elementor;

use LetLexi\Toolkit as Toolkit;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lexi Section Navigator Elementor Widget
 *
 * @since 1.0.0
 */
class Lexi_Elementor_Section_Navigator extends Widget_Base {

	/**
	 * Get widget name
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'lexi_section_navigator';
	}

	/**
	 * Get widget title
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Section Navigator (Lexi)', 'letlexi' );
	}

	/**
	 * Get widget icon
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-navigation-horizontal';
	}

	/**
	 * Get widget categories
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'letlexi' );
	}

	/**
	 * Get widget keywords
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'section', 'navigator', 'lexis', 'legal', 'document', 'toc' );
	}

	/**
	 * Get script dependencies
	 *
	 * @since 1.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'letlexi-section-nav' );
	}

	/**
	 * Get style dependencies
	 *
	 * @since 1.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'letlexi-section-nav' );
	}

	/**
	 * Register widget controls
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {

		// Content Section.
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		// Data Source.
		$this->add_control(
			'data_source',
			array(
				'label'   => __( 'Data Source', 'letlexi' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'acf',
				'options' => array(
					'acf'    => __( 'ACF (Automatic)', 'letlexi' ),
					'manual' => __( 'Manual (Enter content)', 'letlexi' ),
				),
			)
		);

		// Manual Sections (when data source = manual).
		$manual_repeater = new \Elementor\Repeater();
		$manual_repeater->add_control(
			'm_section_number',
			array(
				'label'   => __( 'Section Number', 'letlexi' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);
		$manual_repeater->add_control(
			'm_section_title',
			array(
				'label'   => __( 'Section Title', 'letlexi' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);
		$manual_repeater->add_control(
			'm_section_status',
			array(
				'label'   => __( 'Section Status', 'letlexi' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''            => __( 'None', 'letlexi' ),
					'active'      => __( 'Active', 'letlexi' ),
					'repealed'    => __( 'Repealed', 'letlexi' ),
					'superseded'  => __( 'Superseded', 'letlexi' ),
					'pending'     => __( 'Pending', 'letlexi' ),
				),
			)
		);
		$manual_repeater->add_control(
			'm_section_content',
			array(
				'label'   => __( 'Section Content', 'letlexi' ),
				'type'    => Controls_Manager::WYSIWYG,
				'dynamic' => array( 'active' => true ),
			)
		);
		$manual_repeater->add_control(
			'm_section_commentary',
			array(
				'label'   => __( 'Section Commentary', 'letlexi' ),
				'type'    => Controls_Manager::WYSIWYG,
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'manual_sections',
			array(
				'label'       => __( 'Manual Sections', 'letlexi' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $manual_repeater->get_controls(),
				'title_field' => '{{{ m_section_number }}} {{{ m_section_title }}}',
				'condition'   => array( 'data_source' => 'manual' ),
			)
		);

		// Document Label.
		$this->add_control(
			'document_label',
			array(
				'label'   => __( 'Document Label', 'letlexi' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Document:', 'letlexi' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		// Query Format.
		$this->add_control(
			'query_format',
			array(
				'label'       => __( 'Query Format', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '%constitution% Art. %article%, Section %section%',
				'description' => __( 'Use placeholders: %constitution%, %article%, %section%', 'letlexi' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		// Print Label.
		$this->add_control(
			'print_label',
			array(
				'label'   => __( 'Print Label', 'letlexi' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Print', 'letlexi' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		// Copy Citation Label.
		$this->add_control(
			'copy_citation_label',
			array(
				'label'   => __( 'Copy Citation Label', 'letlexi' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Copy Citation', 'letlexi' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		// TOC Heading.
		$this->add_control(
			'toc_heading',
			array(
				'label'   => __( 'TOC Heading', 'letlexi' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Table of Contents', 'letlexi' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		// Previous Label.
		$this->add_control(
			'previous_label',
			array(
				'label'   => __( 'Previous Label', 'letlexi' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Previous', 'letlexi' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		// Next Label.
		$this->add_control(
			'next_label',
			array(
				'label'   => __( 'Next Label', 'letlexi' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Next', 'letlexi' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->end_controls_section();

		// Display Options Section.
		$this->start_controls_section(
			'display_section',
			array(
				'label' => __( 'Display Options', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		// Show Commentary.
		$this->add_control(
			'show_commentary',
			array(
				'label'   => __( 'Show Commentary', 'letlexi' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		// Show Cross References.
		$this->add_control(
			'show_cross_refs',
			array(
				'label'   => __( 'Show Cross References', 'letlexi' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		// Show Case Law.
		$this->add_control(
			'show_case_law',
			array(
				'label'   => __( 'Show Case Law', 'letlexi' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		// Show Amendments.
		$this->add_control(
			'show_amendments',
			array(
				'label'   => __( 'Show Amendments', 'letlexi' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		// Loading Strategy.
		$this->add_control(
			'loading_strategy',
			array(
				'label'   => __( 'Loading Strategy', 'letlexi' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ajax',
				'options' => array(
					'ajax'    => __( 'AJAX (Dynamic Loading)', 'letlexi' ),
					'preload' => __( 'Preload (Server-Side)', 'letlexi' ),
				),
			)
		);

		$this->end_controls_section();

		// ACF Field Mapping Section.
		$this->start_controls_section(
			'acf_mapping_section',
			array(
				'label' => __( 'ACF Field Mapping', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		// Article-level Toggle Fields.
		$this->add_control(
			'acf_toggle_commentary',
			array(
				'label'       => __( 'ACF Toggle: Commentary', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'ACF field name for article-level commentary toggle', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_toggle_crossrefs',
			array(
				'label'       => __( 'ACF Toggle: Cross References', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'ACF field name for article-level cross references toggle', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_toggle_caselaw',
			array(
				'label'       => __( 'ACF Toggle: Case Law', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'ACF field name for article-level case law toggle', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_toggle_amendments',
			array(
				'label'       => __( 'ACF Toggle: Amendments', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'ACF field name for article-level amendments toggle', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		// Repeater Field Name.
		$this->add_control(
			'acf_repeater_sections',
			array(
				'label'       => __( 'ACF Repeater: Sections', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'ACF repeater field name for sections', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		// Repeater Subfields.
		$this->add_control(
			'acf_field_section_number',
			array(
				'label'       => __( 'ACF Field: Section Number', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'ACF subfield name for section number', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_section_title',
			array(
				'label'       => __( 'ACF Field: Section Title', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'ACF subfield name for section title', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_section_status',
			array(
				'label'       => __( 'ACF Field: Section Status', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'ACF subfield name for section status', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_section_content',
			array(
				'label'       => __( 'ACF Field: Section Content', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'ACF subfield name for section content', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_section_commentary',
			array(
				'label'       => __( 'ACF Field: Section Commentary', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'ACF subfield name for section commentary', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_cross_references',
			array(
				'label'       => __( 'ACF Field: Cross References', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'ACF subfield name for cross references', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_case_law_references',
			array(
				'label'       => __( 'ACF Field: Case Law References', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'ACF subfield name for case law references', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_amendment_history',
			array(
				'label'       => __( 'ACF Field: Amendment History', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'ACF subfield name for amendment history', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->end_controls_section();

		// Style Section - Colors
		$this->start_controls_section(
			'style_colors_section',
			array(
				'label' => __( 'Colors', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'primary_color',
			array(
				'label'     => __( 'Primary Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#007cba',
				'selectors' => array(
					'{{WRAPPER}} .lexi-nav__prev, {{WRAPPER}} .lexi-nav__next' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .lexi-toc__link.active' => 'color: {{VALUE}}',
					'{{WRAPPER}} .lexi-commentary-toggle' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .lexi-jump__select:focus' => 'outline-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'secondary_color',
			array(
				'label'     => __( 'Secondary Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#28a745',
				'selectors' => array(
					'{{WRAPPER}} .lexi-commentary-section' => 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} .lexi-badge--active' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#343a40',
				'selectors' => array(
					'{{WRAPPER}} .lexi-doc' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => __( 'Link Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#007cba',
				'selectors' => array(
					'{{WRAPPER}} .lexi-toc__link' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => __( 'Link Hover Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0056b3',
				'selectors' => array(
					'{{WRAPPER}} .lexi-toc__link:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		// Style Section - Backgrounds
		$this->start_controls_section(
			'style_backgrounds_section',
			array(
				'label' => __( 'Backgrounds', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'toc_background',
			array(
				'label'     => __( 'TOC Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8f9fa',
				'selectors' => array(
					'{{WRAPPER}} .lexi-toc' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'content_background',
			array(
				'label'     => __( 'Content Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .lexi-doc__main' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'section_background',
			array(
				'label'     => __( 'Section Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .lexi-section' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		// Style Section - Typography
		$this->start_controls_section(
			'style_typography_section',
			array(
				'label' => __( 'Typography', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'label'    => __( 'Section Headings', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-section h1, {{WRAPPER}} .lexi-section h2, {{WRAPPER}} .lexi-section h3, {{WRAPPER}} .lexi-section h4, {{WRAPPER}} .lexi-section h5, {{WRAPPER}} .lexi-section h6',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'body_typography',
				'label'    => __( 'Body Text', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-section p, {{WRAPPER}} .lexi-section div, {{WRAPPER}} .lexi-section span',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'toc_typography',
				'label'    => __( 'TOC Links', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-toc__link',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Buttons', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-nav__prev, {{WRAPPER}} .lexi-nav__next, {{WRAPPER}} .lexi-commentary-toggle',
			)
		);

		$this->end_controls_section();

		// Style Section - Spacing
		$this->start_controls_section(
			'style_spacing_section',
			array(
				'label' => __( 'Spacing', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'section_margin',
			array(
				'label'      => __( 'Section Margin', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '2rem',
					'left'   => '0',
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'toc_padding',
			array(
				'label'      => __( 'TOC Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '1.5rem',
					'right'  => '1.5rem',
					'bottom' => '1.5rem',
					'left'   => '1.5rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-toc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Content Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0',
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-doc__main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap_between_panes',
			array(
				'label'      => __( 'Gap Between Panes', 'letlexi' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 10,
					),
					'rem' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'default'    => array(
					'unit' => 'rem',
					'size' => 2,
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-doc' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Section - Borders & Shadows
		$this->start_controls_section(
			'style_borders_section',
			array(
				'label' => __( 'Borders & Shadows', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'toc_border',
				'label'    => __( 'TOC Border', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-toc',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'section_border',
				'label'    => __( 'Section Border', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-section',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'toc_shadow',
				'label'    => __( 'TOC Shadow', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-toc',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'section_shadow',
				'label'    => __( 'Section Shadow', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-section',
			)
		);

		$this->end_controls_section();

		// Style Section - Navigation
		$this->start_controls_section(
			'style_navigation_section',
			array(
				'label' => __( 'Navigation', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'nav_button_padding',
			array(
				'label'      => __( 'Button Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '1rem',
					'right'  => '1.5rem',
					'bottom' => '1rem',
					'left'   => '1.5rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-nav__prev, {{WRAPPER}} .lexi-nav__next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'nav_button_radius',
			array(
				'label'      => __( 'Button Border Radius', 'letlexi' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 8,
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-nav__prev, {{WRAPPER}} .lexi-nav__next' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'nav_button_hover_color',
			array(
				'label'     => __( 'Button Hover Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0056b3',
				'selectors' => array(
					'{{WRAPPER}} .lexi-nav__prev:hover:not(:disabled), {{WRAPPER}} .lexi-nav__next:hover:not(:disabled)' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		// Style Section - Header & Document Info
		$this->start_controls_section(
			'style_header_section',
			array(
				'label' => __( 'Header & Document Info', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'header_background',
			array(
				'label'     => __( 'Header Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .lexi-reader-header' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'document_label_typography',
				'label'    => __( 'Document Label Typography', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-document-label',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'query_label_typography',
				'label'    => __( 'Query Label Typography', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-query-label',
			)
		);

		$this->add_responsive_control(
			'header_padding',
			array(
				'label'      => __( 'Header Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '1rem',
					'right'  => '1rem',
					'bottom' => '1rem',
					'left'   => '1rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-reader-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Section - Section Headers
		$this->start_controls_section(
			'style_section_headers_section',
			array(
				'label' => __( 'Section Headers', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'section_header_background',
			array(
				'label'     => __( 'Section Header Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .lexi-section-header' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'section_header_border_color',
			array(
				'label'     => __( 'Section Header Border Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f0f0f0',
				'selectors' => array(
					'{{WRAPPER}} .lexi-section-header' => 'border-bottom-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'section_header_padding',
			array(
				'label'      => __( 'Section Header Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '1rem',
					'left'   => '0',
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-section-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'section_header_margin',
			array(
				'label'      => __( 'Section Header Margin', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '1.5rem',
					'left'   => '0',
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-section-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Section - Status Badges
		$this->start_controls_section(
			'style_badges_section',
			array(
				'label' => __( 'Status Badges', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typography',
				'label'    => __( 'Badge Typography', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-badge',
			)
		);

		$this->add_responsive_control(
			'badge_padding',
			array(
				'label'      => __( 'Badge Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '0.25rem',
					'right'  => '0.75rem',
					'bottom' => '0.25rem',
					'left'   => '0.75rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'badge_border_radius',
			array(
				'label'      => __( 'Badge Border Radius', 'letlexi' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-badge' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'active_badge_color',
			array(
				'label'     => __( 'Active Badge Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#155724',
				'selectors' => array(
					'{{WRAPPER}} .lexi-badge--active' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'active_badge_background',
			array(
				'label'     => __( 'Active Badge Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d4edda',
				'selectors' => array(
					'{{WRAPPER}} .lexi-badge--active' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'repealed_badge_color',
			array(
				'label'     => __( 'Repealed Badge Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#721c24',
				'selectors' => array(
					'{{WRAPPER}} .lexi-badge--repealed' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'repealed_badge_background',
			array(
				'label'     => __( 'Repealed Badge Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8d7da',
				'selectors' => array(
					'{{WRAPPER}} .lexi-badge--repealed' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'superseded_badge_color',
			array(
				'label'     => __( 'Superseded Badge Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#856404',
				'selectors' => array(
					'{{WRAPPER}} .lexi-badge--superseded' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'superseded_badge_background',
			array(
				'label'     => __( 'Superseded Badge Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff3cd',
				'selectors' => array(
					'{{WRAPPER}} .lexi-badge--superseded' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pending_badge_color',
			array(
				'label'     => __( 'Pending Badge Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0c5460',
				'selectors' => array(
					'{{WRAPPER}} .lexi-badge--pending' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pending_badge_background',
			array(
				'label'     => __( 'Pending Badge Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d1ecf1',
				'selectors' => array(
					'{{WRAPPER}} .lexi-badge--pending' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		// Style Section - Commentary
		$this->start_controls_section(
			'style_commentary_section',
			array(
				'label' => __( 'Commentary', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'commentary_background',
			array(
				'label'     => __( 'Commentary Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8f9fa',
				'selectors' => array(
					'{{WRAPPER}} .lexi-commentary-content' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'commentary_border_color',
			array(
				'label'     => __( 'Commentary Border Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#28a745',
				'selectors' => array(
					'{{WRAPPER}} .lexi-commentary-content' => 'border-left-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'commentary_padding',
			array(
				'label'      => __( 'Commentary Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '1rem',
					'right'  => '1rem',
					'bottom' => '1rem',
					'left'   => '1rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-commentary-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'commentary_border_radius',
			array(
				'label'      => __( 'Commentary Border Radius', 'letlexi' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 4,
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-commentary-content' => 'border-radius: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0;',
				),
			)
		);

		$this->end_controls_section();

		// Style Section - Font Controls
		$this->start_controls_section(
			'style_font_controls_section',
			array(
				'label' => __( 'Font Controls', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'font_control_background',
			array(
				'label'     => __( 'Font Control Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6c757d',
				'selectors' => array(
					'{{WRAPPER}} .lexi-font__inc, {{WRAPPER}} .lexi-font__dec, {{WRAPPER}} .lexi-font__reset' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'font_control_hover_background',
			array(
				'label'     => __( 'Font Control Hover Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#5a6268',
				'selectors' => array(
					'{{WRAPPER}} .lexi-font__inc:hover, {{WRAPPER}} .lexi-font__dec:hover, {{WRAPPER}} .lexi-font__reset:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'font_control_padding',
			array(
				'label'      => __( 'Font Control Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '0.5rem',
					'right'  => '0.75rem',
					'bottom' => '0.5rem',
					'left'   => '0.75rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-font__inc, {{WRAPPER}} .lexi-font__dec, {{WRAPPER}} .lexi-font__reset' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'font_control_border_radius',
			array(
				'label'      => __( 'Font Control Border Radius', 'letlexi' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 4,
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-font__inc, {{WRAPPER}} .lexi-font__dec, {{WRAPPER}} .lexi-font__reset' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Section - Jump Selector
		$this->start_controls_section(
			'style_jump_selector_section',
			array(
				'label' => __( 'Jump Selector', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'jump_select_background',
			array(
				'label'     => __( 'Select Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .lexi-jump__select' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'jump_select_border_color',
			array(
				'label'     => __( 'Select Border Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ced4da',
				'selectors' => array(
					'{{WRAPPER}} .lexi-jump__select' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'jump_select_padding',
			array(
				'label'      => __( 'Select Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '0.5rem',
					'right'  => '0.5rem',
					'bottom' => '0.5rem',
					'left'   => '0.5rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-jump__select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'jump_select_border_radius',
			array(
				'label'      => __( 'Select Border Radius', 'letlexi' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 4,
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-jump__select' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Section - Loading & Error States
		$this->start_controls_section(
			'style_states_section',
			array(
				'label' => __( 'Loading & Error States', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'loading_color',
			array(
				'label'     => __( 'Loading Text Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#666666',
				'selectors' => array(
					'{{WRAPPER}} .lexi-loading' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'error_color',
			array(
				'label'     => __( 'Error Text Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#dc3545',
				'selectors' => array(
					'{{WRAPPER}} .lexi-error' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'state_padding',
			array(
				'label'      => __( 'State Message Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '2rem',
					'right'  => '2rem',
					'bottom' => '2rem',
					'left'   => '2rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-loading, {{WRAPPER}} .lexi-error' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$post_id = get_the_ID();

		$is_manual = isset( $settings['data_source'] ) && $settings['data_source'] === 'manual' && ! empty( $settings['manual_sections'] );

		// Validate only when not manual. In frontend, fail silently; in editor, show a hint.
		if ( ! $is_manual && ! Toolkit\lexi_supports_section_navigation( $post_id ) ) {
			$in_editor = ( class_exists( '\\Elementor\\Plugin' ) && 
				isset( \Elementor\Plugin::$instance->editor ) && 
				method_exists( \Elementor\Plugin::$instance->editor, 'is_edit_mode' ) && 
				\Elementor\Plugin::$instance->editor->is_edit_mode() );
			if ( $in_editor ) {
				echo '<div class="lexi-error">' . esc_html__( 'No content found. Add Manual Sections or map ACF fields via dynamic tags.', 'letlexi' ) . '</div>';
			}
			return;
		}

		// Resolve settings and defaults.
		$resolved_settings = $this->resolve_settings( $settings );

		// In manual mode, pass manual sections in settings for renderer to use.
		if ( $is_manual ) {
			$resolved_settings['manual_sections'] = $this->normalize_manual_sections( $settings['manual_sections'] );
			$resolved_settings['loading_strategy'] = 'preload';
		}

		// Build and output the HTML.
		echo Toolkit\lexi_build_shell_html( $post_id, $resolved_settings );
	}

	/**
	 * Resolve widget settings with defaults
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return array Resolved settings.
	 */
	private function resolve_settings( $settings ) {
		$defaults = array(
			'data_source'             => 'acf',
			'manual_sections'         => array(),
			'document_label'        => '',
			'query_format'          => '',
			'print_label'           => '',
			'copy_citation_label'   => '',
			'toc_heading'           => '',
			'previous_label'        => '',
			'next_label'            => '',
			'show_commentary'       => 'yes',
			'show_cross_refs'       => 'yes',
			'show_case_law'         => 'yes',
			'show_amendments'       => 'yes',
			'loading_strategy'      => 'ajax',
			'acf_toggle_commentary' => '',
			'acf_toggle_crossrefs'  => '',
			'acf_toggle_caselaw'    => '',
			'acf_toggle_amendments' => '',
			'acf_repeater_sections' => '',
			'acf_field_section_number'     => '',
			'acf_field_section_title'      => '',
			'acf_field_section_status'     => '',
			'acf_field_section_content'    => '',
			'acf_field_section_commentary' => '',
			'acf_field_cross_references'   => '',
			'acf_field_case_law_references' => '',
			'acf_field_amendment_history'  => '',
		);

		return wp_parse_args( $settings, $defaults );
	}

	/**
	 * Normalize manual sections structure to match renderer expectations
	 *
	 * @since 1.1.0
	 *
	 * @param array $manual_sections Sections from Elementor control.
	 * @return array Normalized sections.
	 */
	private function normalize_manual_sections( $manual_sections ) {
		$normalized = array();
		if ( empty( $manual_sections ) || ! is_array( $manual_sections ) ) {
			return $normalized;
		}

		foreach ( $manual_sections as $section ) {
			$normalized[] = array(
				'section_number'     => isset( $section['m_section_number'] ) ? $section['m_section_number'] : '',
				'section_title'      => isset( $section['m_section_title'] ) ? $section['m_section_title'] : '',
				'section_status'     => isset( $section['m_section_status'] ) ? $section['m_section_status'] : '',
				'section_content'    => isset( $section['m_section_content'] ) ? $section['m_section_content'] : '',
				'section_commentary' => isset( $section['m_section_commentary'] ) ? $section['m_section_commentary'] : '',
				'cross_references'   => array(),
				'case_law_references'=> array(),
				'amendment_history'  => array(),
			);
		}

		return $normalized;
	}
}
