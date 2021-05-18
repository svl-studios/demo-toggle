<?php
/**
 * Demo grid shortcode.
 *
 * @package     SVL Demo Toggle
 * @author      SVL Studios
 * @copyright   Copyright (c) 2021, SVL Studios
 * @link        https://www.svlstudios.com
 * @since       SVL Demo Toggle 1.0
 */

defined( 'ABSPATH' ) || exit;

use SVL\PHPColors\Color;

if ( ! class_exists( 'Svl_Demos' ) ) {

	/**
	 * Class Requite_Social_Icons
	 */
	class Svl_Demos {

		/**
		 * Theme name.
		 *
		 * @var string
		 */
		private $theme = '';

		/**
		 * Theme purchase URL.
		 *
		 * @var string
		 */
		private $purchase_url = '';

		/**
		 * Primary Color.
		 *
		 * @var string
		 */
		public $primary_color = '';

		/**
		 * Contrasting Color.
		 *
		 * @var string
		 */
		public $contrasting_color = '';

		/**
		 * Overlay Color.
		 *
		 * @var string
		 */
		public $overlay_color = '';

		/**
		 * New Banner Color.
		 *
		 * @var string
		 */
		public $new_color = '';

		/**
		 * New Banner Text Color.
		 *
		 * @var string
		 */
		public $new_text_color = '';

		/**
		 * Coming Soon Banner Color.
		 *
		 * @var string
		 */
		public $coming_soon_color = '';

		/**
		 * Coming Soon Banner Text Color.
		 *
		 * @var string
		 */
		public $coming_soon_text_color = '';

		/**
		 * Requite_Social_Icons constructor.
		 */
		public function __construct() {
			add_action( 'vc_before_init', array( $this, 'init' ) );
			add_shortcode( 'svl_demos', array( $this, 'shortcode' ) );
		}

		/**
		 * Init.
		 */
		public function init() {
			if ( function_exists( 'vc_map' ) ) {
				vc_map(
					array(
						'name'                    => esc_html__( 'Demo Grid', 'svl-demos' ),
						'base'                    => 'svl_demos',
						'description'             => esc_html__( 'Add a Demo Grid', 'svl-demos' ),
						'icon'                    => 'vc_icon-vc-media-grid',
						'category'                => 'SVL Studios',
						'as_parent'               => array( 'only' => 'svl_demo' ),
						'content_element'         => true,
						'show_settings_on_create' => true,
						'js_view'                 => 'VcColumnView',
						'params'                  => array(
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Theme Name.', 'svl-demos' ),
								'param_name' => 'theme',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Purchase URL.', 'svl-demos' ),
								'param_name' => 'purchase_link',
							),
							array(
								'type'        => 'checkbox',
								'heading'     => '',
								'param_name'  => 'display_toggle',
								'description' => esc_html__( 'Display the demo toggle.', 'svl-demos' ),
								'value'       => array( esc_html__( 'Toggle', 'svl_demos' ) => 'yes' ),
								'std'         => 'yes',
							),
							array(
								'type'        => 'checkbox',
								'heading'     => '',
								'param_name'  => 'randomize',
								'description' => esc_html__( 'Randomize the demo black order.', 'svl-demos' ),
								'value'       => array( esc_html__( 'Randomize', 'svl_demos' ) => 'yes' ),
								'std'         => 'yes',
								'group'      => esc_html('Randomize', 'svl-demos'),
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Randomize interval (in seconds).', 'svl-demos' ),
								'param_name' => 'rand_interval',
								'value'      => '60',
								'group'      => esc_html('Randomize', 'svl-demos'),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__( 'Primary color (links and buttons).', 'svl-demos' ),
								'param_name' => 'primary_color',
								'value'      => '#82b440',
								'group'      => esc_html('Colors', 'svl-demos'),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__( 'Demo block overlay color.', 'svl-demos' ),
								'param_name' => 'overlay_color',
								'value'      => '#2b2b2b',
								'group'      => esc_html('Colors', 'svl-demos'),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__( 'Demo block "New" banner color.', 'svl-demos' ),
								'param_name' => 'new_color',
								'value'      => '#fa2222',
								'group'      => esc_html('Colors', 'svl-demos'),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__( 'Demo block "Coming Soon" banner color.', 'svl-demos' ),
								'param_name' => 'coming_soon_color',
								'value'      => '#82b440',
								'group'      => esc_html('Colors', 'svl-demos'),
							),
						),
					)
				);
			}
		}

		/**
		 * Add the demo toggler code to the footer of the page.
		 */
		public function add_demo_toggle() {
			$shadow_color = new Color( $this->primary_color );

			?>
			<div data-id="<?php echo intval( get_the_ID() ); ?>" data-hover_color="<?php esc_attr( $this->primary_color ); ?>"
					data-theme="<?php echo esc_attr( $this->theme ); ?>" class='svl-demo-select-wrap init-onload'>
				<span href='#' class='svl-demo-toggle'>
					<i class='fa fa-plus'></i> DEMOS
				</span>
				<div class='svl-demos-info-box'>
					<div class='buy-now-btn'>
						<a style="box-shadow:0 2px 0 #<?php echo esc_attr( $shadow_color->darken() ); ?>;color:<?php echo esc_attr( $this->contrasting_color ); ?>!important;background-color:<?php echo esc_attr( $this->primary_color ); ?>" href="<?php echo esc_url( $this->purchase_url ); ?>">
							Purchase <?php echo esc_html( $this->theme ); ?> </a>
					</div>
					<span class='demos-count'></span>
					<span class='svl-more-demos-text'> Loading Demos </span>
				</div>
				<div class='svl-demo-window'>
					<i class='loading-demos fa fa-spin fa-refresh'></i>
					<ul style='height: 376px;'></ul>
				</div>
			</div>

			<style>.svl-demo-toggle:hover{color: <?php echo esc_attr( $this->primary_color ); ?> !important;}.svl-coming-soon .demo-wrap::after{color:<?php echo esc_attr( $this->coming_soon_text_color ); ?>;background-color:<?php echo esc_attr( $this->coming_soon_color ); ?>;}.btn-primary{border-color:<?php echo esc_attr( $this->primary_color ); ?>;}</style>
			<?php

		}

		/**
		 * Shortcode.
		 *
		 * @param array $atts    Attributes.
		 * @param null  $content Content.
		 *
		 * @return string
		 */
		public function shortcode( $atts = '', $content = null ): string {
			$arr = array(
				'theme'             => '',
				'purchase_link'     => '',
				'display_toggle'    => 'yes',
				'randomize'         => 'yes',
				'rand_interval'     => '60',
				'primary_color'     => '#82b440',
				'overlay_color'     => '#2b2b2b',
				'new_color'         => '#fa2222',
				'coming_soon_color' => '#82b440',
			);

			// phpcs:ignore WordPress.PHP.DontExtract
			extract( shortcode_atts( $arr, $atts ) );

			$this->primary_color          = $primary_color;
			$this->contrasting_color      = $this->contrasting_color( $primary_color, '#000000', '#ffffff' );
			$this->overlay_color          = $overlay_color;
			$this->new_color              = $new_color;
			$this->new_text_color         = $this->contrasting_color( $new_color );
			$this->coming_soon_color      = $coming_soon_color;
			$this->coming_soon_text_color = $this->contrasting_color( $coming_soon_color );

			if ( 'yes' === $display_toggle ) {
				$this->theme        = $theme;
				$this->purchase_url = $purchase_link;

				add_action( 'wp_footer', array( $this, 'add_demo_toggle' ) );
			}

			$rand_interval = intval( $rand_interval ) * 1000;

			$output  = '<div data-interval="' . intval( $rand_interval ) . '" data-randomize="' . esc_attr( $randomize ) . '" class="svl-demos" style="visibility: visible;">';
			$output .= do_shortcode( $content );
			$output .= '</div>';

			return $output;
		}

		/**
		 * Contrasting color.
		 *
		 * @param string $hexcolor Hex color.
		 * @param string $dark Dark value.
		 * @param string $light Light value.
		 *
		 * @return string
		 */
		private function contrasting_color( string $hexcolor, string $dark = '#000000', string $light = '#FFFFFF' ):string {
			$hexcolor = str_replace( $hexcolor, '', '#' );

			$r = hexdec( substr( $hexcolor, 1, 2 ) );
			$g = hexdec( substr( $hexcolor, 3, 2 ) );
			$b = hexdec( substr( $hexcolor, 5, 2 ) );

			$yiq = ( ( $r * 299 ) + ( $g * 587 ) + ( $b * 114 ) ) / 1000;

			return ( $yiq >= 128 ) ? $dark : $light;
		}
	}

	global $svl_demos;

	$svl_demos = new Svl_Demos();

	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {

		/**
		 * Class WPBakeryShortCode_Svl_Demos
		 */
		class WPBakeryShortCode_Svl_Demos extends WPBakeryShortCodesContainer {} // phpcs:ignore
	}
}
