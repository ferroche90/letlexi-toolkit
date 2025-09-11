<?php
/**
 * Elementor Section Navigator Widget
 *
 * @package LetLexi\Toolkit\Elementor
 * @since 1.0.0
 */

namespace LetLexi\Toolkit\Elementor;

use LetLexi\Toolkit as Toolkit;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lexi Section Navigator Elementor Widget
 *
 * @since 1.0.0
 */
class Lexi_Elementor_Section_Navigator extends \Elementor\Widget_Base {

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
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		// Document Label.
		$this->add_control(
			'document_label',
			array(
				'label'   => __( 'Document Label', 'letlexi' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
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
				'type'        => \Elementor\Controls_Manager::TEXT,
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
				'type'    => \Elementor\Controls_Manager::TEXT,
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
				'type'    => \Elementor\Controls_Manager::TEXT,
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
				'type'    => \Elementor\Controls_Manager::TEXT,
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
				'type'    => \Elementor\Controls_Manager::TEXT,
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
				'type'    => \Elementor\Controls_Manager::TEXT,
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
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		// Show Commentary.
		$this->add_control(
			'show_commentary',
			array(
				'label'   => __( 'Show Commentary', 'letlexi' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		// Show Cross References.
		$this->add_control(
			'show_cross_refs',
			array(
				'label'   => __( 'Show Cross References', 'letlexi' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		// Show Case Law.
		$this->add_control(
			'show_case_law',
			array(
				'label'   => __( 'Show Case Law', 'letlexi' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		// Show Amendments.
		$this->add_control(
			'show_amendments',
			array(
				'label'   => __( 'Show Amendments', 'letlexi' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		// Loading Strategy.
		$this->add_control(
			'loading_strategy',
			array(
				'label'   => __( 'Loading Strategy', 'letlexi' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
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
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		// Article-level Toggle Fields.
		$this->add_control(
			'acf_toggle_commentary',
			array(
				'label'       => __( 'ACF Toggle: Commentary', 'letlexi' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'show_commentary',
				'description' => __( 'ACF field name for article-level commentary toggle', 'letlexi' ),
			)
		);

		$this->add_control(
			'acf_toggle_crossrefs',
			array(
				'label'       => __( 'ACF Toggle: Cross References', 'letlexi' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'show_cross_references',
				'description' => __( 'ACF field name for article-level cross references toggle', 'letlexi' ),
			)
		);

		$this->add_control(
			'acf_toggle_caselaw',
			array(
				'label'       => __( 'ACF Toggle: Case Law', 'letlexi' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'show_case_law',
				'description' => __( 'ACF field name for article-level case law toggle', 'letlexi' ),
			)
		);

		$this->add_control(
			'acf_toggle_amendments',
			array(
				'label'       => __( 'ACF Toggle: Amendments', 'letlexi' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'show_amendment_history',
				'description' => __( 'ACF field name for article-level amendments toggle', 'letlexi' ),
			)
		);

		// Repeater Field Name.
		$this->add_control(
			'acf_repeater_sections',
			array(
				'label'       => __( 'ACF Repeater: Sections', 'letlexi' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'sections',
				'description' => __( 'ACF repeater field name for sections', 'letlexi' ),
			)
		);

		// Repeater Subfields.
		$this->add_control(
			'acf_field_section_number',
			array(
				'label'       => __( 'ACF Field: Section Number', 'letlexi' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'section_number',
				'description' => __( 'ACF subfield name for section number', 'letlexi' ),
			)
		);

		$this->add_control(
			'acf_field_section_title',
			array(
				'label'       => __( 'ACF Field: Section Title', 'letlexi' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'section_title',
				'description' => __( 'ACF subfield name for section title', 'letlexi' ),
			)
		);

		$this->add_control(
			'acf_field_section_status',
			array(
				'label'       => __( 'ACF Field: Section Status', 'letlexi' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'section_status',
				'description' => __( 'ACF subfield name for section status', 'letlexi' ),
			)
		);

		$this->add_control(
			'acf_field_section_content',
			array(
				'label'       => __( 'ACF Field: Section Content', 'letlexi' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'section_content',
				'description' => __( 'ACF subfield name for section content', 'letlexi' ),
			)
		);

		$this->add_control(
			'acf_field_section_commentary',
			array(
				'label'       => __( 'ACF Field: Section Commentary', 'letlexi' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'section_commentary',
				'description' => __( 'ACF subfield name for section commentary', 'letlexi' ),
			)
		);

		$this->add_control(
			'acf_field_cross_references',
			array(
				'label'       => __( 'ACF Field: Cross References', 'letlexi' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'cross_references',
				'description' => __( 'ACF subfield name for cross references', 'letlexi' ),
			)
		);

		$this->add_control(
			'acf_field_case_law_references',
			array(
				'label'       => __( 'ACF Field: Case Law References', 'letlexi' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'case_law_references',
				'description' => __( 'ACF subfield name for case law references', 'letlexi' ),
			)
		);

		$this->add_control(
			'acf_field_amendment_history',
			array(
				'label'       => __( 'ACF Field: Amendment History', 'letlexi' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'amendment_history',
				'description' => __( 'ACF subfield name for amendment history', 'letlexi' ),
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

		// Validate post type.
		if ( ! Toolkit\lexi_is_constitution_article( $post_id ) ) {
			echo '<div class="lexi-error">' . esc_html__( 'This widget can only be used on constitution article pages.', 'letlexi' ) . '</div>';
			return;
		}

		// Resolve settings and defaults.
		$resolved_settings = $this->resolve_settings( $settings );

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
			'document_label'        => __( 'Document:', 'letlexi' ),
			'query_format'          => '%constitution% Art. %article%, Section %section%',
			'print_label'           => __( 'Print', 'letlexi' ),
			'copy_citation_label'   => __( 'Copy Citation', 'letlexi' ),
			'toc_heading'           => __( 'Table of Contents', 'letlexi' ),
			'previous_label'        => __( 'Previous', 'letlexi' ),
			'next_label'            => __( 'Next', 'letlexi' ),
			'show_commentary'       => 'yes',
			'show_cross_refs'       => 'yes',
			'show_case_law'         => 'yes',
			'show_amendments'       => 'yes',
			'loading_strategy'      => 'ajax',
			'acf_toggle_commentary' => 'show_commentary',
			'acf_toggle_crossrefs'  => 'show_cross_references',
			'acf_toggle_caselaw'    => 'show_case_law',
			'acf_toggle_amendments' => 'show_amendment_history',
			'acf_repeater_sections' => 'sections',
			'acf_field_section_number'     => 'section_number',
			'acf_field_section_title'      => 'section_title',
			'acf_field_section_status'     => 'section_status',
			'acf_field_section_content'    => 'section_content',
			'acf_field_section_commentary' => 'section_commentary',
			'acf_field_cross_references'   => 'cross_references',
			'acf_field_case_law_references' => 'case_law_references',
			'acf_field_amendment_history'  => 'amendment_history',
		);

		return wp_parse_args( $settings, $defaults );
	}
}
