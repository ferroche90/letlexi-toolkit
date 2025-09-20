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

		// STYLE: TOC
		$this->start_controls_section(
			'lexi_style_toc_section',
			array(
				'label' => __( 'TOC', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'toc_background_ctrl',
				'label'    => __( 'Background', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-toc',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'toc_border_ctrl',
				'label'    => __( 'Border', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-toc',
			)
		);

		$this->add_control(
			'toc_radius',
			array(
				'label'     => __( 'Border Radius', 'letlexi' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units'=> array( 'px', '%' ),
				'range'     => array(
					'px' => array( 'min' => 0, 'max' => 50 ),
					'%'  => array( 'min' => 0, 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}} .lexi-toc' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'toc_padding_ctrl',
			array(
				'label'      => __( 'Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-toc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'toc_link_typography',
				'label'    => __( 'Link Typography', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-toc__link',
			)
		);

		$this->add_control(
			'toc_link_color',
			array(
				'label'     => __( 'Link Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-toc__link' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'toc_link_hover_color',
			array(
				'label'     => __( 'Link Hover Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-toc__link:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'toc_link_active_color',
			array(
				'label'     => __( 'Active Link Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-toc__link.active' => 'color: {{VALUE}}',
				),
			)
		);

		// TOC link backgrounds and radius
		$this->add_control(
			'toc_link_bg',
			array(
				'label'     => __( 'Link Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-toc__link' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'toc_link_bg_hover',
			array(
				'label'     => __( 'Link Background (Hover)', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-toc__link:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'toc_link_bg_active',
			array(
				'label'     => __( 'Link Background (Active)', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-toc__link.active' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'toc_link_radius',
			array(
				'label'     => __( 'Link Border Radius', 'letlexi' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units'=> array( 'px', '%' ),
				'range'     => array(
					'px' => array( 'min' => 0, 'max' => 30 ),
					'%'  => array( 'min' => 0, 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}} .lexi-toc__link' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'toc_item_spacing',
			array(
				'label'     => __( 'Item Spacing', 'letlexi' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units'=> array( 'px', 'em', 'rem' ),
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors' => array(
					'{{WRAPPER}} .lexi-toc__link' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();


		// STYLE: Header (Document & Query)
		$this->start_controls_section(
			'lexi_style_header_section',
			array(
				'label' => __( 'Header', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'document_label_typo_new',
				'label'    => __( 'Document Label Typography', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-document-label',
			)
		);

		$this->add_control(
			'document_label_color_new',
			array(
				'label'     => __( 'Document Label Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-document-label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'document_title_typo_new',
				'label'    => __( 'Document Title Typography', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-document-title',
			)
		);

		$this->add_control(
			'document_title_color_new',
			array(
				'label'     => __( 'Document Title Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-document-title' => 'color: {{VALUE}}',
				),
			)
		);


		$this->add_responsive_control(
			'header_spacing_stack',
			array(
				'label'      => __( 'Header Spacing', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-reader-header' => 'margin-bottom: {{BOTTOM}}{{UNIT}}; padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// STYLE: Print & Copy buttons
		$this->start_controls_section(
			'lexi_style_action_buttons_section',
			array(
				'label' => __( 'Print & Copy Buttons', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'action_btn_typo',
				'label'    => __( 'Typography', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-print-btn, {{WRAPPER}} .lexi-copy-citation-btn',
			)
		);

		$this->add_control(
			'action_btn_text_color',
			array(
				'label'     => __( 'Text Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-print-btn, {{WRAPPER}} .lexi-copy-citation-btn' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'action_btn_bg',
			array(
				'label'     => __( 'Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-print-btn, {{WRAPPER}} .lexi-copy-citation-btn' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'action_btn_bg_hover',
			array(
				'label'     => __( 'Background Hover', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-print-btn:hover, {{WRAPPER}} .lexi-copy-citation-btn:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'action_btn_border',
				'selector' => '{{WRAPPER}} .lexi-print-btn, {{WRAPPER}} .lexi-copy-citation-btn',
			)
		);

		$this->add_control(
			'action_btn_radius',
			array(
				'label'     => __( 'Border Radius', 'letlexi' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units'=> array( 'px', '%' ),
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 50 ) ),
				'selectors' => array(
					'{{WRAPPER}} .lexi-print-btn, {{WRAPPER}} .lexi-copy-citation-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'action_btn_padding',
			array(
				'label'      => __( 'Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-print-btn, {{WRAPPER}} .lexi-copy-citation-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();


		// STYLE: View Toggle (Full text / Single section)
		$this->start_controls_section(
			'lexi_style_view_toggle_section',
			array(
				'label' => __( 'View Toggle', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'view_toggle_typo',
				'selector' => '{{WRAPPER}} .lexi-view-toggle__btn, {{WRAPPER}} .lexi-view-toggle--floating .lexi-view-toggle__btn',
			)
		);

		$this->add_responsive_control(
			'view_toggle_gap',
			array(
				'label'      => __( 'Button Gap', 'letlexi' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 50 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-view-toggle, {{WRAPPER}} .lexi-view-toggle--floating' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'view_toggle_margin',
			array(
				'label'      => __( 'Container Margin', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-view-toggle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'view_toggle_style_tabs' );

		$this->start_controls_tab(
			'view_toggle_tab_normal',
			array( 'label' => __( 'Normal', 'letlexi' ) )
		);
		$this->add_control(
			'view_toggle_text_color',
			array(
				'label'     => __( 'Text Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-view-toggle__btn, {{WRAPPER}} .lexi-view-toggle--floating .lexi-view-toggle__btn' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'view_toggle_bg_color',
			array(
				'label'     => __( 'Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-view-toggle__btn, {{WRAPPER}} .lexi-view-toggle--floating .lexi-view-toggle__btn' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'view_toggle_tab_hover',
			array( 'label' => __( 'Hover', 'letlexi' ) )
		);
		$this->add_control(
			'view_toggle_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-view-toggle__btn:hover, {{WRAPPER}} .lexi-view-toggle--floating .lexi-view-toggle__btn:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'view_toggle_bg_color_hover',
			array(
				'label'     => __( 'Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-view-toggle__btn:hover, {{WRAPPER}} .lexi-view-toggle--floating .lexi-view-toggle__btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'view_toggle_tab_active',
			array( 'label' => __( 'Active', 'letlexi' ) )
		);
		$this->add_control(
			'view_toggle_text_color_active',
			array(
				'label'     => __( 'Text Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-view-toggle__btn.is-active, {{WRAPPER}} .lexi-view-toggle--floating .lexi-view-toggle__btn.is-active' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'view_toggle_bg_color_active',
			array(
				'label'     => __( 'Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-view-toggle__btn.is-active, {{WRAPPER}} .lexi-view-toggle--floating .lexi-view-toggle__btn.is-active' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'view_toggle_border',
				'selector' => '{{WRAPPER}} .lexi-view-toggle__btn, {{WRAPPER}} .lexi-view-toggle--floating .lexi-view-toggle__btn',
			)
		);

		$this->add_control(
			'view_toggle_radius',
			array(
				'label'     => __( 'Border Radius', 'letlexi' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units'=> array( 'px', '%' ),
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 50 ) ),
				'selectors' => array(
					'{{WRAPPER}} .lexi-view-toggle__btn, {{WRAPPER}} .lexi-view-toggle--floating .lexi-view-toggle__btn' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'view_toggle_padding',
			array(
				'label'      => __( 'Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-view-toggle__btn, {{WRAPPER}} .lexi-view-toggle--floating .lexi-view-toggle__btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();


		// STYLE: Section Content & Commentary
		$this->start_controls_section(
			'lexi_style_section_content',
			array(
				'label' => __( 'Section Content', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'content_bg',
				'selector' => '{{WRAPPER}} .lexi-doc__body',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'content_border',
				'selector' => '{{WRAPPER}} .lexi-doc__body',
			)
		);
		$this->add_control(
			'content_radius',
			array(
				'label'     => __( 'Border Radius', 'letlexi' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units'=> array( 'px', '%' ),
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 50 ) ),
				'selectors' => array(
					'{{WRAPPER}} .lexi-doc__body' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-doc__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'section_main_heading_typo',
				'label'    => __( 'Main Section Heading Typography', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-section-title, {{WRAPPER}} .lexi-section h1, {{WRAPPER}} .lexi-section h2',
			)
		);

		$this->add_control(
			'section_main_heading_color',
			array(
				'label'     => __( 'Main Section Heading Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-section-title, {{WRAPPER}} .lexi-section h1, {{WRAPPER}} .lexi-section h2' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'section_sub_heading_typo',
				'label'    => __( 'Sub Heading Typography', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-section h3, {{WRAPPER}} .lexi-section h4, {{WRAPPER}} .lexi-section h5, {{WRAPPER}} .lexi-section h6',
			)
		);

		$this->add_control(
			'section_sub_heading_color',
			array(
				'label'     => __( 'Sub Heading Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-section h3, {{WRAPPER}} .lexi-section h4, {{WRAPPER}} .lexi-section h5, {{WRAPPER}} .lexi-section h6' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'section_content_typo',
				'label'    => __( 'Section Content Typography', 'letlexi' ),
				'selector' => '{{WRAPPER}} .lexi-section p, {{WRAPPER}} .lexi-section div, {{WRAPPER}} .lexi-section span, {{WRAPPER}} .lexi-section li, {{WRAPPER}} .lexi-section td, {{WRAPPER}} .lexi-section th',
			)
		);

		$this->add_control(
			'section_content_color',
			array(
				'label'     => __( 'Content Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-section p, {{WRAPPER}} .lexi-section div, {{WRAPPER}} .lexi-section span, {{WRAPPER}} .lexi-section li, {{WRAPPER}} .lexi-section td, {{WRAPPER}} .lexi-section th' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'commentary_heading',
			array(
				'label' => __( 'Commentary Button', 'letlexi' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'commentary_btn_typo',
				'selector' => '{{WRAPPER}} .lexi-commentary-toggle',
			)
		);

		$this->add_control(
			'commentary_btn_text_color',
			array(
				'label'     => __( 'Text Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-commentary-toggle' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'commentary_btn_bg',
			array(
				'label'     => __( 'Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-commentary-toggle' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'commentary_btn_bg_hover',
			array(
				'label'     => __( 'Background Hover', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-commentary-toggle:hover' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'commentary_btn_border',
				'selector' => '{{WRAPPER}} .lexi-commentary-toggle',
			)
		);
		$this->add_control(
			'commentary_btn_radius',
			array(
				'label'     => __( 'Border Radius', 'letlexi' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units'=> array( 'px', '%' ),
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 50 ) ),
				'selectors' => array(
					'{{WRAPPER}} .lexi-commentary-toggle' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// STYLE: Jump To
		$this->start_controls_section(
			'lexi_style_jump_section',
			array(
				'label' => __( 'Jump To', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'jump_label_typo',
				'selector' => '{{WRAPPER}} .lexi-jump label',
			)
		);
		$this->add_control(
			'jump_label_color',
			array(
				'label'     => __( 'Label Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-jump label' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'jump_select_typo',
				'selector' => '{{WRAPPER}} .lexi-jump__select',
			)
		);
		$this->add_control(
			'jump_select_text_color',
			array(
				'label'     => __( 'Select Text Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-jump__select' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'jump_select_bg',
			array(
				'label'     => __( 'Select Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-jump__select' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'jump_select_border',
				'selector' => '{{WRAPPER}} .lexi-jump__select',
			)
		);
		$this->add_control(
			'jump_select_radius',
			array(
				'label'     => __( 'Select Border Radius', 'letlexi' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units'=> array( 'px', '%' ),
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors' => array(
					'{{WRAPPER}} .lexi-jump__select' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

		// STYLE: Next & Previous buttons
		$this->start_controls_section(
			'lexi_style_nav_buttons_section',
			array(
				'label' => __( 'Next & Previous Buttons', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'nav_btn_typo',
				'selector' => '{{WRAPPER}} .lexi-nav__prev, {{WRAPPER}} .lexi-nav__next',
			)
		);
		$this->add_control(
			'nav_btn_text_color',
			array(
				'label'     => __( 'Text Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-nav__prev, {{WRAPPER}} .lexi-nav__next' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'nav_btn_bg',
			array(
				'label'     => __( 'Background', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-nav__prev, {{WRAPPER}} .lexi-nav__next' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'nav_btn_bg_hover',
			array(
				'label'     => __( 'Background Hover', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lexi-nav__prev:hover:not(:disabled), {{WRAPPER}} .lexi-nav__next:hover:not(:disabled)' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'nav_btn_border',
				'selector' => '{{WRAPPER}} .lexi-nav__prev, {{WRAPPER}} .lexi-nav__next',
			)
		);
		$this->add_control(
			'nav_btn_radius',
			array(
				'label'     => __( 'Border Radius', 'letlexi' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units'=> array( 'px', '%' ),
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 50 ) ),
				'selectors' => array(
					'{{WRAPPER}} .lexi-nav__prev, {{WRAPPER}} .lexi-nav__next' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'nav_btn_padding',
			array(
				'label'      => __( 'Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-nav__prev, {{WRAPPER}} .lexi-nav__next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		// Layout Section.
		$this->start_controls_section(
			'layout_section',
			array(
				'label' => __( 'Layout', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'toc_position',
			array(
				'label'   => __( 'TOC Position', 'letlexi' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => __( 'Left', 'letlexi' ),
					'right' => __( 'Right', 'letlexi' ),
					'below' => __( 'Below Content', 'letlexi' ),
				),
			)
		);

		$this->add_responsive_control(
			'toc_width',
			array(
				'label'      => __( 'TOC Width', 'letlexi' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px'  => array( 'min' => 160, 'max' => 600 ),
					'rem' => array( 'min' => 10, 'max' => 40 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 280 ),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-doc' => '--lexi-toc-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'max_width',
			array(
				'label'      => __( 'Container Max Width', 'letlexi' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem', '%' ),
				'range'      => array(
					'px'  => array( 'min' => 600, 'max' => 2000 ),
					'rem' => array( 'min' => 40, 'max' => 160 ),
					'%'   => array( 'min' => 50, 'max' => 100 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-doc' => '--lexi-max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'toc_sticky',
			array(
				'label'   => __( 'Sticky TOC', 'letlexi' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_responsive_control(
			'toc_sticky_offset',
			array(
				'label'      => __( 'Sticky Top Offset', 'letlexi' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px'  => array( 'min' => 0, 'max' => 200 ),
					'rem' => array( 'min' => 0, 'max' => 10 ),
				),
				'default'    => array( 'unit' => 'rem', 'size' => 2 ),
				'selectors'  => array(
					'{{WRAPPER}} .lexi-doc' => '--lexi-toc-sticky-top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'toc_sticky' => 'yes' ),
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

		// History and Annotations labels
		$this->add_control(
			'history_heading',
			array(
				'label'   => __( 'History Heading', 'letlexi' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'History', 'letlexi' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'annotations_label',
			array(
				'label'   => __( 'Annotations Tab Label', 'letlexi' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Annotations', 'letlexi' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		// Show Commentary (Section Annotations).
		$this->add_control(
			'show_commentary',
			array(
				'label'   => __( 'Show Section Annotations', 'letlexi' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		// Show Cross References (Research References & Practice Aids).
		$this->add_control(
			'show_cross_refs',
			array(
				'label'   => __( 'Show Research References & Practice Aids', 'letlexi' ),
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

		// Navigation Controls Section
		$this->add_control(
			'navigation_controls_heading',
			array(
				'label' => __( 'Navigation Controls', 'letlexi' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Sticky Scroll Offset (for sticky headers) – responsive slider like Sticky TOC Top Offset
		$this->add_responsive_control(
			'sticky_scroll_offset',
			array(
				'label'      => __( 'Sticky Scroll Offset', 'letlexi' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px'  => array( 'min' => 0, 'max' => 200 ),
					'rem' => array( 'min' => 0, 'max' => 10 ),
				),
				'description'=> __( 'Additional top offset when scrolling to sections (e.g., sticky header height).', 'letlexi' ),
			)
		);



		// Show Top Navigation.
		$this->add_control(
			'show_top_navigation',
			array(
				'label'   => __( 'Show Top Navigation', 'letlexi' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => __( 'Show Previous/Next buttons above the content', 'letlexi' ),
			)
		);

		// Show Bottom Navigation.
		$this->add_control(
			'show_bottom_navigation',
			array(
				'label'   => __( 'Show Bottom Navigation', 'letlexi' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => __( 'Show Previous/Next buttons below the content', 'letlexi' ),
			)
		);

		// Action Buttons Section
		$this->add_control(
			'action_buttons_heading',
			array(
				'label' => __( 'Action Buttons', 'letlexi' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Show Print Button.
		$this->add_control(
			'show_print_button',
			array(
				'label'   => __( 'Show Print Button', 'letlexi' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => __( 'Show print button in the document header', 'letlexi' ),
			)
		);

		// Show Copy Button.
		$this->add_control(
			'show_copy_button',
			array(
				'label'   => __( 'Show Copy Citation Button', 'letlexi' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => __( 'Show copy citation button in the document header', 'letlexi' ),
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

		// Article-level Fields.
		$this->add_control(
			'acf_field_article_commentary',
			array(
				'label'       => __( 'ACF Field: Article Commentary', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'article_commentary',
				'description' => __( 'ACF field name for article-level commentary', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_article_cross_references',
			array(
				'label'       => __( 'ACF Field: Article Cross References', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'article_cross_references',
				'description' => __( 'ACF field name for article-level cross references', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_article_status',
			array(
				'label'       => __( 'ACF Field: Article Status', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'article_status',
				'description' => __( 'ACF field name for article status', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_display_style',
			array(
				'label'       => __( 'ACF Field: Display Style', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'display_style',
				'description' => __( 'ACF field name for display style setting', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		// Repeater Field Name.
		$this->add_control(
			'acf_repeater_sections',
			array(
				'label'       => __( 'ACF Repeater: Sections', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'sections',
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
				'default'     => 'section_number',
				'description' => __( 'ACF subfield name for section number', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_section_title',
			array(
				'label'       => __( 'ACF Field: Section Title', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'section_title',
				'description' => __( 'ACF subfield name for section title', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_section_content',
			array(
				'label'       => __( 'ACF Field: Section Content', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'section_content',
				'description' => __( 'ACF subfield name for section content', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_section_commentary',
			array(
				'label'       => __( 'ACF Field: Section Commentary', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'section_commentary',
				'description' => __( 'ACF subfield name for section commentary/annotations', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_section_source_note',
			array(
				'label'       => __( 'ACF Field: Section Source Note', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'source_note',
				'description' => __( 'ACF subfield name for section source note', 'letlexi' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'acf_field_section_cross_references',
			array(
				'label'       => __( 'ACF Field: Section Cross References', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'section_cross_references',
				'description' => __( 'ACF subfield name for section cross references', 'letlexi' ),
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

		// Apply layout class to widget wrapper for live preview and frontend.
		$toc_position = isset( $settings['toc_position'] ) ? $settings['toc_position'] : 'left';
		if ( in_array( $toc_position, array( 'left', 'right', 'below' ), true ) ) {
			$this->add_render_attribute( '_wrapper', 'class', 'lexi-toc-' . $toc_position );
		}

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
			'print_label'           => '',
			'copy_citation_label'   => '',
			'toc_heading'           => '',
			'previous_label'        => '',
			'next_label'            => '',
			'show_commentary'       => 'yes',
			'show_cross_refs'       => 'yes',
			'show_top_navigation'   => 'yes',
			'show_bottom_navigation' => 'yes',
			'show_print_button'     => 'yes',
			'show_copy_button'      => 'yes',
			'loading_strategy'      => 'ajax',
			'history_heading'       => '',
			'annotations_label'     => '',
			'sticky_scroll_offset'  => '',
			'acf_field_article_commentary' => '',
			'acf_field_article_cross_references' => '',
			'acf_field_article_status' => '',
			'acf_field_display_style' => '',
			'acf_repeater_sections' => '',
			'acf_field_section_number'     => '',
			'acf_field_section_title'      => '',
			'acf_field_section_content'    => '',
			'acf_field_section_commentary' => '',
			'acf_field_section_source_note' => '',
			'acf_field_section_cross_references' => '',
			'acf_field_cross_references'   => '',
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
			);
		}

		return $normalized;
	}
}
