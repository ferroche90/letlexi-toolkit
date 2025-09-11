<?php
/**
 * Example Elementor Widget for LetLexi Toolkit
 *
 * This is an example widget that demonstrates how to create new widgets
 * using the auto-loading system and the base widget class.
 *
 * @package LetLexi\Toolkit\Elementor
 * @since 1.0.0
 */

namespace LetLexi\Toolkit\Elementor;

use LetLexi\Toolkit as Toolkit;
use Elementor\Controls_Manager;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Example LetLexi Elementor Widget
 *
 * This widget demonstrates the auto-loading system and serves as a template
 * for creating new widgets. It extends the base widget class for common functionality.
 *
 * @since 1.0.0
 */
class Lexi_Elementor_Example_Widget extends Lexi_Elementor_Widget_Base {

	/**
	 * Get widget name
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'lexi_example_widget';
	}

	/**
	 * Get widget title
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Example Widget (Lexi)', 'letlexi' );
	}

	/**
	 * Get widget icon
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-info-circle-o';
	}

	/**
	 * Get widget keywords
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'letlexi', 'example', 'demo', 'template' );
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

		$this->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Example Widget Title', 'letlexi' ),
				'placeholder' => __( 'Enter your title', 'letlexi' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'       => __( 'Description', 'letlexi' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'This is an example widget that demonstrates the auto-loading system.', 'letlexi' ),
				'placeholder' => __( 'Enter your description', 'letlexi' ),
			)
		);

		$this->add_control(
			'show_icon',
			array(
				'label'        => __( 'Show Icon', 'letlexi' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'letlexi' ),
				'label_off'    => __( 'Hide', 'letlexi' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		// Style Section.
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'letlexi' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Title Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => array(
					'{{WRAPPER}} .lexi-example-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Description Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#666666',
				'selectors' => array(
					'{{WRAPPER}} .lexi-example-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => __( 'Background Color', 'letlexi' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8f9fa',
				'selectors' => array(
					'{{WRAPPER}} .lexi-example-widget' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Use the base class methods for common controls.
		$this->add_typography_controls( '{{WRAPPER}} .lexi-example-title', __( 'Title Typography', 'letlexi' ) );
		$this->add_border_controls( '{{WRAPPER}} .lexi-example-widget', __( 'Widget Border', 'letlexi' ) );
		$this->add_spacing_controls( '{{WRAPPER}} .lexi-example-widget', __( 'Widget Spacing', 'letlexi' ) );

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$css_classes = $this->get_lexi_css_classes( $settings );

		?>
		<div class="lexi-example-widget <?php echo esc_attr( $css_classes ); ?>">
			<?php if ( 'yes' === $settings['show_icon'] ) : ?>
				<div class="lexi-example-icon">
					<i class="eicon-info-circle-o"></i>
				</div>
			<?php endif; ?>
			
			<?php if ( ! empty( $settings['title'] ) ) : ?>
				<h3 class="lexi-example-title"><?php echo esc_html( $settings['title'] ); ?></h3>
			<?php endif; ?>
			
			<?php if ( ! empty( $settings['description'] ) ) : ?>
				<div class="lexi-example-description">
					<?php echo wp_kses_post( $settings['description'] ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render widget output in the editor
	 *
	 * @since 1.0.0
	 */
	protected function content_template() {
		?>
		<#
		var cssClasses = 'lexi-example-widget lexi-widget lexi-' + settings.widget_type;
		#>
		<div class="{{{ cssClasses }}}">
			<# if ( 'yes' === settings.show_icon ) { #>
				<div class="lexi-example-icon">
					<i class="eicon-info-circle-o"></i>
				</div>
			<# } #>
			
			<# if ( settings.title ) { #>
				<h3 class="lexi-example-title">{{{ settings.title }}}</h3>
			<# } #>
			
			<# if ( settings.description ) { #>
				<div class="lexi-example-description">
					{{{ settings.description }}}
				</div>
			<# } #>
		</div>
		<?php
	}
}
