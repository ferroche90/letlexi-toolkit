<?php
/**
 * Test Elementor Widget for LetLexi Toolkit
 *
 * This is a simple test widget to verify the auto-loading system works correctly.
 * It extends the standard Elementor Widget_Base directly.
 *
 * @package LetLexi\Toolkit\Elementor
 * @since 1.0.0
 */

namespace LetLexi\Toolkit\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Test LetLexi Elementor Widget
 *
 * This widget is used to test the auto-loading system.
 *
 * @since 1.0.0
 */
class Lexi_Elementor_Test_Widget extends Widget_Base {

	/**
	 * Get widget name
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'lexi_test_widget';
	}

	/**
	 * Get widget title
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Test Widget (Lexi)', 'letlexi' );
	}

	/**
	 * Get widget icon
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-check-circle';
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
		return array( 'letlexi', 'test', 'auto-loading' );
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
			'test_message',
			array(
				'label'       => __( 'Test Message', 'letlexi' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Auto-loading system is working!', 'letlexi' ),
				'placeholder' => __( 'Enter your test message', 'letlexi' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		?>
		<div class="lexi-test-widget">
			<div class="lexi-test-message">
				<?php echo esc_html( $settings['test_message'] ); ?>
			</div>
			<div class="lexi-test-info">
				<small><?php esc_html_e( 'This widget was loaded automatically by the LetLexi auto-loading system.', 'letlexi' ); ?></small>
			</div>
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
		<div class="lexi-test-widget">
			<div class="lexi-test-message">
				{{{ settings.test_message }}}
			</div>
			<div class="lexi-test-info">
				<small><?php esc_html_e( 'This widget was loaded automatically by the LetLexi auto-loading system.', 'letlexi' ); ?></small>
			</div>
		</div>
		<?php
	}
}
