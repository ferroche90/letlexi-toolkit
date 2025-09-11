<?php
/**
 * Base Elementor Widget Class for LetLexi Toolkit
 *
 * This class provides common functionality and structure for all LetLexi Elementor widgets.
 * It extends the standard Elementor Widget_Base and adds LetLexi-specific features.
 *
 * @package LetLexi\Toolkit\Elementor
 * @since 1.0.0
 */

namespace LetLexi\Toolkit\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base class for LetLexi Elementor widgets
 *
 * @since 1.0.0
 */
abstract class Lexi_Elementor_Widget_Base extends Widget_Base {

	/**
	 * Get widget category
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget category.
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
		return array( 'letlexi', 'legal', 'constitution', 'document' );
	}

	/**
	 * Get widget icon
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-document-file';
	}

	/**
	 * Get widget script dependencies
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget script dependencies.
	 */
	public function get_script_depends() {
		return array( 'lexi-section-nav' );
	}

	/**
	 * Get widget style dependencies
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends() {
		return array( 'lexi-section-nav' );
	}

	/**
	 * Add common typography controls
	 *
	 * @since 1.0.0
	 *
	 * @param string $selector CSS selector for the typography control.
	 * @param string $label Label for the control group.
	 */
	protected function add_typography_controls( $selector, $label = '' ) {
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => $this->get_name() . '_typography',
				'label'    => $label ? $label : __( 'Typography', 'letlexi' ),
				'selector' => $selector,
			)
		);
	}

	/**
	 * Add common border controls
	 *
	 * @since 1.0.0
	 *
	 * @param string $selector CSS selector for the border control.
	 * @param string $label Label for the control group.
	 */
	protected function add_border_controls( $selector, $label = '' ) {
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => $this->get_name() . '_border',
				'label'    => $label ? $label : __( 'Border', 'letlexi' ),
				'selector' => $selector,
			)
		);
	}

	/**
	 * Add common box shadow controls
	 *
	 * @since 1.0.0
	 *
	 * @param string $selector CSS selector for the box shadow control.
	 * @param string $label Label for the control group.
	 */
	protected function add_box_shadow_controls( $selector, $label = '' ) {
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => $this->get_name() . '_box_shadow',
				'label'    => $label ? $label : __( 'Box Shadow', 'letlexi' ),
				'selector' => $selector,
			)
		);
	}

	/**
	 * Add common background controls
	 *
	 * @since 1.0.0
	 *
	 * @param string $selector CSS selector for the background control.
	 * @param string $label Label for the control group.
	 */
	protected function add_background_controls( $selector, $label = '' ) {
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => $this->get_name() . '_background',
				'label'    => $label ? $label : __( 'Background', 'letlexi' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => $selector,
			)
		);
	}

	/**
	 * Add common spacing controls
	 *
	 * @since 1.0.0
	 *
	 * @param string $selector CSS selector for the spacing control.
	 * @param string $label Label for the control.
	 */
	protected function add_spacing_controls( $selector, $label = '' ) {
		$this->add_responsive_control(
			$this->get_name() . '_margin',
			array(
				'label'      => $label ? $label : __( 'Margin', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'selectors'  => array(
					$selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			$this->get_name() . '_padding',
			array(
				'label'      => __( 'Padding', 'letlexi' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'selectors'  => array(
					$selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Get common CSS classes for LetLexi widgets
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return string CSS classes.
	 */
	protected function get_lexi_css_classes( $settings = array() ) {
		$classes = array( 'lexi-widget', 'lexi-' . $this->get_name() );

		// Add responsive classes if needed.
		if ( ! empty( $settings['hide_on_mobile'] ) ) {
			$classes[] = 'lexi-hide-mobile';
		}

		if ( ! empty( $settings['hide_on_tablet'] ) ) {
			$classes[] = 'lexi-hide-tablet';
		}

		if ( ! empty( $settings['hide_on_desktop'] ) ) {
			$classes[] = 'lexi-hide-desktop';
		}

		return implode( ' ', $classes );
	}

	/**
	 * Render widget output
	 *
	 * This method should be implemented by child classes.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		// This method should be implemented by child classes.
		_e( 'Widget render method not implemented.', 'letlexi' );
	}

	/**
	 * Render widget output in the editor
	 *
	 * This method can be overridden by child classes for custom editor rendering.
	 *
	 * @since 1.0.0
	 */
	protected function content_template() {
		// Default editor template - can be overridden by child classes.
		?>
		<div class="lexi-widget-editor">
			<h3><?php echo esc_html( $this->get_title() ); ?></h3>
			<p><?php esc_html_e( 'This widget will be rendered on the frontend.', 'letlexi' ); ?></p>
		</div>
		<?php
	}
}
