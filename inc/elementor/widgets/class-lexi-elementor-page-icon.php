<?php
/**
 * Elementor widget: Page Icon (Meta Compatible)
 *
 * Renders Font Awesome icons or uploaded SVG from page meta. Includes editor
 * preview via content_template so icons render inside Elementor editor.
 *
 * @package LetLexi\Toolkit\Elementor
 * @since 1.2.0
 */

namespace LetLexi\Toolkit\Elementor;

use Elementor\Controls_Manager;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Page Icon Elementor Widget Class
 *
 * This widget allows users to display icons from either Elementor's icon picker
 * or from page meta data (FontAwesome icons or uploaded SVG files). It provides
 * full editor preview support and integrates with the LetLexi page icon system.
 *
 * @since 1.2.0
 */
class Lexi_Elementor_Page_Icon extends Lexi_Elementor_Widget_Base {

    /**
     * Get widget name
     *
     * Retrieve the widget name used in Elementor.
     *
     * @since 1.2.0
     * @access public
     * @return string Widget name.
     */
    public function get_name() {
        return 'lexi-page-icon';
    }

    /**
     * Get widget title
     *
     * Retrieve the widget title displayed in Elementor.
     *
     * @since 1.2.0
     * @access public
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Page Icon (Meta Compatible)', 'letlexi' );
    }

    /**
     * Get widget icon
     *
     * Retrieve the widget icon displayed in Elementor.
     *
     * @since 1.2.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-favorite';
    }

    /**
     * Get widget categories
     *
     * Retrieve the list of categories the widget belongs to.
     *
     * @since 1.2.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories() {
        return array( 'letlexi' );
    }

    /**
     * Get widget keywords
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 1.2.0
     * @access public
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return array( 'icon', 'svg', 'meta', 'page', 'letlexi', 'fontawesome' );
    }

    /**
     * Check if widget has inner wrapper
     *
     * Determines if the widget should have an inner wrapper based on Elementor's
     * optimized markup experiment setting.
     *
     * @since 1.2.0
     * @access public
     * @return bool Whether the widget has an inner wrapper.
     */
    public function has_widget_inner_wrapper(): bool {
        return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
    }

    /**
     * Check if widget is dynamic content
     *
     * Determines if the widget uses dynamic content.
     *
     * @since 1.2.0
     * @access protected
     * @return bool Whether the widget is dynamic content.
     */
    protected function is_dynamic_content(): bool {
        return false;
    }

    /**
     * Get widget style dependencies
     *
     * Retrieve the list of style dependencies the widget requires.
     * Ensures FontAwesome CSS is loaded in both editor and frontend.
     *
     * @since 1.2.0
     * @access public
     * @return array Widget style dependencies.
     */
    public function get_style_depends() {
        return array( 'letlexi-fa', 'letlexi-fa-v4-shims' );
    }

    /**
     * Register widget controls
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     * This method includes controls for icon source selection, styling options, and meta data handling.
     *
     * @since 1.2.0
     * @access protected
     * @return void
     */
    protected function register_controls() {
        // Main
        $this->start_controls_section( 'section_icon', array( 'label' => esc_html__( 'Icon', 'elementor' ) ) );

        // Extra: Source selector
        $this->add_control(
            'source',
            array(
                'label'       => esc_html__( 'Source', 'elementor' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'options'     => array(
                    'elementor' => esc_html__( 'Selected Icon (default)', 'elementor' ),
                    'meta'      => esc_html__( 'From Page Meta (SVG or Icon Library)', 'elementor' ),
                ),
                'default'     => 'elementor',
                'description' => esc_html__( 'When set to "From Page Meta", the widget will automatically detect and display either uploaded SVG files or selected icon library icons from the page\'s meta data.', 'elementor' ),
            )
        );

        // Native icon picker (shown when Source=elementor)
        $this->add_control(
            'selected_icon',
            array(
                'label'            => esc_html__( 'Icon', 'elementor' ),
                'type'             => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default'          => array(
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ),
                'condition'        => array( 'source' => 'elementor' ),
            )
        );

        // Meta controls (shown when Source=meta)
        $this->add_control(
            'meta_key',
            array(
                'label'       => esc_html__( 'Meta Key (SVG attachment ID)', 'elementor' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'placeholder' => '_page_icon_attachment_id',
                'default'     => '_page_icon_attachment_id',
                'dynamic'     => array( 'active' => true ),
                'condition'   => array( 'source' => 'meta' ),
            )
        );

        $this->add_control(
            'meta_attachment_id',
            array(
                'label'     => esc_html__( 'Attachment ID', 'elementor' ),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'default'   => '',
                'dynamic'   => array( 'active' => true ),
                'condition' => array( 'source' => 'meta' ),
            )
        );

        // Native: view/shape/link
        $this->add_control(
            'view',
            array(
                'label'        => esc_html__( 'View', 'elementor' ),
                'type'         => \Elementor\Controls_Manager::SELECT,
                'options'      => array(
                    'default' => esc_html__( 'Default', 'elementor' ),
                    'stacked' => esc_html__( 'Stacked', 'elementor' ),
                    'framed'  => esc_html__( 'Framed', 'elementor' ),
                ),
                'default'      => 'default',
                'prefix_class' => 'elementor-view-',
            )
        );

        $this->add_control(
            'shape',
            array(
                'label'        => esc_html__( 'Shape', 'elementor' ),
                'type'         => \Elementor\Controls_Manager::SELECT,
                'options'      => array(
                    'square'  => esc_html__( 'Square', 'elementor' ),
                    'rounded' => esc_html__( 'Rounded', 'elementor' ),
                    'circle'  => esc_html__( 'Circle', 'elementor' ),
                ),
                'default'      => 'circle',
                'condition'    => array( 'view!' => 'default' ),
                'prefix_class' => 'elementor-shape-',
            )
        );

        $this->add_control(
            'link',
            array(
                'label'   => esc_html__( 'Link', 'elementor' ),
                'type'    => \Elementor\Controls_Manager::URL,
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->end_controls_section();

        // Style (match native)
        $this->start_controls_section(
            'section_style_icon',
            array(
                'label' => esc_html__( 'Icon', 'elementor' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'align',
            array(
                'label'     => esc_html__( 'Alignment', 'elementor' ),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'elementor' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'elementor' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'elementor' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'default'   => 'center',
                'selectors' => array( '{{WRAPPER}} .elementor-icon-wrapper' => 'text-align: {{VALUE}};' ),
            )
        );

        $this->start_controls_tabs( 'icon_colors' );

        $this->start_controls_tab(
            'icon_colors_normal',
            array(
                'label' => esc_html__( 'Normal', 'elementor' ),
            )
        );

        $this->add_control(
            'primary_color',
            array(
                'label'     => esc_html__( 'Primary Color', 'elementor' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon svg' => 'fill: {{VALUE}};',
                ),
                'global'    => array( 'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY ),
            )
        );

        $this->add_control(
            'secondary_color',
            array(
                'label'     => esc_html__( 'Secondary Color', 'elementor' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'condition' => array( 'view!' => 'default' ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon svg' => 'fill: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_colors_hover',
            array(
                'label' => esc_html__( 'Hover', 'elementor' ),
            )
        );

        $this->add_control(
            'hover_primary_color',
            array(
                'label'     => esc_html__( 'Primary Color', 'elementor' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover, {{WRAPPER}}.elementor-view-default .elementor-icon:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover, {{WRAPPER}}.elementor-view-default .elementor-icon:hover svg' => 'fill: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'hover_secondary_color',
            array(
                'label'     => esc_html__( 'Secondary Color', 'elementor' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'condition' => array( 'view!' => 'default' ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover svg' => 'fill: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'hover_animation',
            array(
                'label' => esc_html__( 'Hover Animation', 'elementor' ),
                'type'  => \Elementor\Controls_Manager::HOVER_ANIMATION,
            )
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'size',
            array(
                'label'      => esc_html__( 'Size', 'elementor' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
                'range'      => array(
                    'px' => array(
                        'min' => 6,
                        'max' => 300,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-icon svg' => 'height: {{SIZE}}{{UNIT}};',
                ),
                'separator'  => 'before',
            )
        );

        $this->add_control(
            'fit_to_size',
            array(
                'label'       => esc_html__( 'Fit to Size', 'elementor' ),
                'type'        => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__( 'Avoid gaps around icons when width and height aren\'t equal', 'elementor' ),
                'label_off'   => esc_html__( 'Off', 'elementor' ),
                'label_on'    => esc_html__( 'On', 'elementor' ),
                'conditions'  => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'selected_icon[library]',
                            'operator' => '===',
                            'value'    => 'svg',
                        ),
                        array(
                            'name'     => 'source',
                            'operator' => '===',
                            'value'    => 'meta',
                        ),
                    ),
                ),
                'selectors'   => array( '{{WRAPPER}} .elementor-icon-wrapper svg' => 'width: auto;' ),
            )
        );

        $this->add_control(
            'icon_padding',
            array(
                'label'      => esc_html__( 'Padding', 'elementor' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
                'selectors'  => array( '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};' ),
                'range'      => array(
                    'px'  => array( 'max' => 50 ),
                    'em'  => array(
                        'min' => 0,
                        'max' => 5,
                    ),
                    'rem' => array(
                        'min' => 0,
                        'max' => 5,
                    ),
                ),
                'condition'  => array( 'view!' => 'default' ),
            )
        );

        $this->add_responsive_control(
            'rotate',
            array(
                'label'          => esc_html__( 'Rotate', 'elementor' ),
                'type'           => \Elementor\Controls_Manager::SLIDER,
                'size_units'     => array( 'deg', 'grad', 'rad', 'turn', 'custom' ),
                'default'        => array( 'unit' => 'deg' ),
                'tablet_default' => array( 'unit' => 'deg' ),
                'mobile_default' => array( 'unit' => 'deg' ),
                'selectors'      => array(
                    '{{WRAPPER}} .elementor-icon i, {{WRAPPER}} .elementor-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ),
            )
        );

        $this->add_control(
            'border_width',
            array(
                'label'      => esc_html__( 'Border Width', 'elementor' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'  => array( 'view' => 'framed' ),
            )
        );

        $this->add_responsive_control(
            'border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'elementor' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'  => array( 'view!' => 'default' ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend
     *
     * Written in PHP and used to generate the final HTML. This method handles
     * the rendering of both Elementor-selected icons and meta-sourced icons
     * (FontAwesome or SVG files from page meta data).
     *
     * @since 1.2.0
     * @access protected
     * @return void
     */
    protected function render() {
        /** @var array $settings Widget settings for display */
        $settings = $this->get_settings_for_display();

        // Add render attributes for wrapper and icon elements
        $this->add_render_attribute( 'wrapper', 'class', 'elementor-icon-wrapper' );
        $this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-icon' );

        // Add hover animation class if specified
        if ( ! empty( $settings['hover_animation'] ) ) {
            $this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-animation-' . $settings['hover_animation'] );
        }

        // Determine icon tag (div or anchor)
        $icon_tag = 'div';
        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_link_attributes( 'icon-wrapper', $settings['link'] );
            $icon_tag = 'a';
        }

        /**
         * Get the correct post ID for meta data retrieval
         * Handles Elementor loops, queried objects, and editor context
         */
        $post_id = 0;

        // Check if we're in a loop context (Elementor Loop)
        if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->documents->get_current() ) {
            $current_document = \Elementor\Plugin::$instance->documents->get_current();

            // If we're in a loop template, get the current loop item ID
            if ( $current_document && method_exists( $current_document, 'get_main_id' ) ) {
                $main_post_id = $current_document->get_main_id();

                // Check if we're in a loop by looking at the current post ID vs main post ID
                $current_post_id = get_the_ID();

                // If current post ID is different from main post ID, we're likely in a loop
                if ( $current_post_id && $current_post_id !== $main_post_id ) {
                    $post_id = $current_post_id;
                }
            }
        }

        // Fallback to queried object if not in loop
        if ( ! $post_id ) {
            $post_id = get_queried_object_id();
        }

        // Final fallback to GET parameter (for editor)
        if ( ! $post_id && isset( $_GET['post'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
            $post_id = (int) $_GET['post'];
        }

        /** @var string $meta_icon_content HTML content for meta-sourced icon */
        $meta_icon_content = '';
        /** @var bool $is_icon_library Whether the meta icon is from icon library */
        $is_icon_library   = false;

        // Process meta-sourced icons if source is set to 'meta' and we have a post ID
        if ( 'meta' === ( $settings['source'] ?? '' ) && $post_id ) {
            /**
             * First, check for icon library data (FontAwesome icons)
             * This handles the _page_icon_library meta field
             */
            $icon_library_raw = get_post_meta( $post_id, '_page_icon_library', true );
            if ( $icon_library_raw ) {
                /** @var array|null $icon_library_data Parsed icon library data */
                $icon_library_data = null;
                if ( is_string( $icon_library_raw ) ) {
                    $icon_library_data = json_decode( $icon_library_raw, true );
                } elseif ( is_array( $icon_library_raw ) ) {
                    $icon_library_data = $icon_library_raw;
                }

                if ( is_array( $icon_library_data ) && ! empty( $icon_library_data['value'] ) ) {
                    // We have icon library data, render FontAwesome icon
                    $icon_class        = $icon_library_data['value'];
                    $meta_icon_content = '<i class="' . esc_attr( $icon_class ) . '" aria-hidden="true"></i>';
                    $is_icon_library   = true;
                }
            }

            /**
             * If no icon library data, check for SVG attachment
             * This handles uploaded SVG files from the page icon meta
             */
            if ( ! $is_icon_library ) {
                /** @var int $attachment_id SVG attachment ID */
                $attachment_id = 0;

                if ( ! empty( $settings['meta_attachment_id'] ) ) {
                    $attachment_id = (int) $settings['meta_attachment_id'];
                } else {
                    $meta_key      = ! empty( $settings['meta_key'] ) ? $settings['meta_key'] : '_page_icon_attachment_id';
                    $attachment_id = (int) get_post_meta( $post_id, $meta_key, true );
                }

                if ( $attachment_id ) {
                    // Get the SVG file content
                    $svg_file = get_attached_file( $attachment_id );
                    if ( $svg_file && file_exists( $svg_file ) ) {
                        $svg_content = file_get_contents( $svg_file );
                        if ( $svg_content ) {
                            // Remove XML declaration and comments, keep only the SVG element
                            $svg_content = preg_replace( '/<\?xml[^>]*\?>/', '', $svg_content );
                            $svg_content = preg_replace( '/<!--.*?-->/s', '', $svg_content );
                            $svg_content = trim( $svg_content );

                            // Ensure it's a valid SVG
                            if ( strpos( $svg_content, '<svg' ) === 0 ) {
                                $meta_icon_content = $svg_content;
                            }
                        }
                    }
                }
            }
        }

        // Output the widget HTML structure
        echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>';
        echo '<' . \Elementor\Utils::validate_html_tag( $icon_tag ) . ' ' . $this->get_render_attribute_string( 'icon-wrapper' ) . '>';

        if ( $meta_icon_content ) {
            // Render the meta icon (either FontAwesome or SVG)
            echo $meta_icon_content;
        } else {
            // Fallback to widget's own selected icon
            if ( empty( $settings['selected_icon']['value'] ) ) {
                echo '</' . \Elementor\Utils::validate_html_tag( $icon_tag ) . '></div>';
                return;
            }
            \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], array( 'aria-hidden' => 'true' ) );
        }

        echo '</' . \Elementor\Utils::validate_html_tag( $icon_tag ) . '>';
        echo '</div>';
    }

    /**
     * Render widget output in the editor
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     * This method provides the editor preview functionality so icons are visible
     * in the Elementor editor interface.
     *
     * @since 1.2.0
     * @access protected
     * @return void
     */
    protected function content_template() {
        ?>
    <#
    // Early return if no icon is selected
    if ( '' === settings.selected_icon.value ) {
        return;
    }

    // Add link attributes if URL is provided
    if ( settings.link && settings.link.url ) {
        view.addRenderAttribute( 'link_url', 'href', elementor.helpers.sanitizeUrl( settings.link.url ) );
    }

    // Generate icon HTML using Elementor's helper
    const iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
        migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' ),
        iconTag = ( settings.link && settings.link.url ) ? 'a' : 'div';

    // Add icon classes and attributes
    view.addRenderAttribute( 'icon', 'class', 'elementor-icon' );

    // Add hover animation class if specified
    if ( settings.hover_animation && '' !== settings.hover_animation ) {
        view.addRenderAttribute( 'icon', 'class', 'elementor-animation-' + settings.hover_animation );
    }
    #>
    <div class="elementor-icon-wrapper">
        <{{{ iconTag }}} {{{ view.getRenderAttributeString( 'icon' ) }}}  {{{ view.getRenderAttributeString( 'link_url' ) }}}>
            <# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
                {{{ iconHTML.value }}}
            <# } else { #>
                <i class="{{ settings.icon }}" aria-hidden="true"></i>
            <# } #>
        </{{{ iconTag }}}>
    </div>
        <?php
    }
}


