<?php
/**
 * Demo Container
 *
 * @package     SVL Demo Toggle
 * @author      SVL Studios
 * @copyright   Copyright (c) 2021, SVL Studios
 * @link        https://www.svlstudios.com
 * @since       SVL Demo Toggle 1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Svl_Demo' ) ) {

	/**
	 * Class Svl_Demo
	 */
	class Svl_Demo {

		/**
		 * Svl_Demo constructor.
		 */
		public function __construct() {
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			add_action( 'vc_before_init', array( $this, 'init' ), 10 );
			add_shortcode( 'svl_demo', array( $this, 'shortcode' ) );
		}

		/**
		 * Init.
		 */
		public function init() {
			if ( function_exists( 'vc_map' ) ) {
				vc_map(
					array(
						'name'            => esc_html__( 'Demo Block', 'svl-demos' ),
						'base'            => 'svl_demo',
						'description'     => esc_html__( 'Add a Demo Block', 'svl-demos' ),
						'icon'            => 'icon-wpb-row',
						'category'        => 'SVL Studios',
						'content_element' => true,
						'as_child'        => array( 'only' => 'svl_demos' ),
						'params'          => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Demo Title', 'svl-demos' ),
								'admin_label' => true,
								'param_name'  => 'demo_title',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Demo URL', 'svl-demos' ),
								'param_name' => 'demo_url',
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__( 'Open link in', 'svl-demos' ),
								'param_name' => 'link_target',
								'value'      => array(
									esc_html__( 'Same window', 'svl-demos' ) => '_self',
									esc_html__( 'New window', 'svl-demos' )  => '_blank',
								),
							),
							array(
								'type'        => 'dropdown',
								'class'       => '',
								'heading'     => esc_html__( 'Image Source', 'svl-demos' ),
								'description' => esc_html__( 'Load image from upload or url.', 'svl-demos' ),
								'param_name'  => 'source',
								'value'       => array(
									esc_html__( 'Media Library', 'svl-demos' ) => '',
									esc_html__( 'External Link', 'svl-demos' ) => 'external_link',
								),
							),
							array(
								'type'       => 'attach_image',
								'class'      => '',
								'heading'    => esc_html__( 'Demo Image', 'svl-demos' ),
								'param_name' => 'demo_image',
								'dependency' => array(
									'element'            => 'source',
									'value_not_equal_to' => 'external_link',
								),
							),
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Demo Image URL', 'svl-demos' ),
								'param_name'  => 'custom_demo_image',
								'dependency'  => array(
									'element' => 'source',
									'value'   => array( 'external_link' ),
								),
								'description' => esc_html__( 'Must input full valid image URL', 'svl-demos' ),
							),
							array(
								'type'        => 'dropdown',
								'heading'     => esc_html__( 'Builder Used', 'svl-demos' ),
								'admin_label' => true,
								'param_name'  => 'builder_used',
								'value'       => array(
									esc_html__( 'WPBakery', 'svl-demos' ) => 'wpbakery',
									esc_html__( 'Elementor', 'svl-demos' ) => 'elementor',
								),
							),
							array(
								'type'        => 'checkbox',
								'heading'     => '',
								'admin_label' => true,
								'param_name'  => 'new',
								'description' => esc_html__( 'Mark demo as "New".', 'svl-demos' ),
								'value'       => array( esc_html__( 'New', 'svl_demos' ) => 'yes' ),
							),
							array(
								'type'        => 'checkbox',
								'heading'     => '',
								'param_name'  => 'coming_soon',
								'description' => esc_html__( 'Mark demo as "Coming Soon".', 'svl-demos' ),
								'value'       => array( esc_html__( 'Coming Soon', 'svl_demos' ) => 'yes' ),
							),
						),
					)
				);
			}
		}

		/**
		 * Shortcode.
		 *
		 * @param array $atts Attributes.
		 * @param null  $content Content.
		 *
		 * @return string
		 */
		public function shortcode( $atts = '', $content = null ): string {
			global $svl_demos;

			$arr = array(
				'demo_title'        => '',
				'demo_url'          => '',
				'link_target'       => '_blank',
				'source'            => '',
				'demo_image'        => '',
				'custom_demo_image' => '',
				'builder_used'      => 'wpbakery',
				'new'               => '',
				'coming_soon'       => '',
			);

			// phpcs:ignore WordPress.PHP.DontExtract
			extract( shortcode_atts( $arr, $atts ) );

			$has_custom = false;

			if ( isset( $source ) && 'external_link' === $source && isset( $custom_demo_image ) && ! empty( $custom_demo_image ) ) {
				$has_custom = true;
			}

			if ( ! empty( $demo_image ) || true === $has_custom ) {
				if ( $has_custom ) {
					$demo_image = esc_url( $custom_demo_image );
				} else {
					$demo_image = wp_get_attachment_image_src( $demo_image, 'full' );
					$demo_image = $demo_image[0];
				}
			}

			if ( 'wpbakery' === $builder_used ) {
				$builder_img = get_stylesheet_directory_uri() . '/demo/img/wpbakery.png';
			} else {
				$builder_img = get_stylesheet_directory_uri() . '/demo/img/elementor.png';
			}

			if ( 'yes' === $coming_soon ) {
				$coming_soon = 'svl-coming-soon';
			}

			$slug = strtolower( str_replace( ' ', '-', $demo_title ) );

			$primary_color     = $svl_demos->primary_color;
			$contrasting_color = $svl_demos->contrasting_color;
			$overlay_color     = $svl_demos->overlay_color;

			$html  = '    <div class="col-xs-6 col-sm-4 col-md-3 demo-block ' . esc_attr( $slug ) . ' ' . esc_attr( $coming_soon ) . '">';
			$html .= '		<div class="demo-wrap">';

			if ( '' === $coming_soon ) {
				$html .= '			<a target="_blank" href="' . esc_url( $demo_url ) . '" style="background-color:' . esc_attr( $overlay_color ) . ';" class="overlay"></a>';
				$html .= '			<a target="_blank" href="' . esc_url( $demo_url ) . '" style="color:' . esc_attr( $contrasting_color ) . ';background-color:' . esc_attr( $primary_color ) . ';" class="svl-button button btn-primary no-hover">View Demo</a>';
			}

			$html .= '			<img src="' . esc_url( $demo_image ) . '">';
			$html .= '			<a target="' . esc_attr( $link_target ) . '" href="' . esc_url( $demo_url ) . '" class="title-link">';
			$html .= '				<h4>' . esc_html( $demo_title ) . '</h4>';
			$html .= '			</a>';

			if ( 'yes' === $new ) {
				$html .= '<span class="new-demo">NEW!</span>';
			}

			$html .= '			<span class="svl-builder ' . esc_attr( $builder_used ) . '">';
			$html .= '              <img src="' . esc_url( $builder_img ) . '">';
			$html .= '	        </span>';
			$html .= '		</div>';
			$html .= '	</div>';

			$html .= '<style>.btn-primary{border-color:' . esc_attr( $primary_color ) . ';}</style>';

			return $html;
		}
	}

	new Svl_Demo();

	if ( class_exists( 'WPBakeryShortCode' ) ) {

		/**
		 * Class WPBakeryShortCode_Svl_Demo
		 */
		class WPBakeryShortCode_Svl_Demo extends WPBakeryShortCode {} // phpcs:ignore
	}
}
