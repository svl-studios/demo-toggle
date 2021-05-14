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
								'heading'    => esc_html__( 'Theme Name', 'svl-demos' ),
								'param_name' => 'theme',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Purchase URL', 'svl-demos' ),
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
						),
					)
				);
			}
		}

		/**
		 * Add the demo toggler code to the footer of the page.
		 */
		public function add_demo_toggle() {
			?>
			<div data-id="<?php echo intval( get_the_ID() ); ?>" data-theme="<?php echo esc_attr( $this->theme ); ?>" class='svl-demo-select-wrap init-onload' >
				<span href = '#' class='svl-demo-toggle' >
					<i class='fa fa-plus' ></i > DEMOS
				</span >
				<div class='svl-demos-info-box' >
					<div class='buy-now-btn' >
						<a href="<?php echo esc_url( $this->purchase_url ); ?>" > Purchase <?php echo esc_html( $this->theme ); ?> </a >
					</div >
					<span class='demos-count' ></span >
					<span class='svl-more-demos-text' > Loading Demos </span >
				</div >
				<div class='svl-demo-window' >
					<i class='loading-demos fa fa-spin fa-refresh' ></i >
					<ul style = 'height: 376px;' ></ul >
				</div >
			</div >
			<?php
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
			$arr = array(
				'theme'          => '',
				'purchase_link'  => '',
				'display_toggle' => 'yes',
			);

			// phpcs:ignore WordPress.PHP.DontExtract
			extract( shortcode_atts( $arr, $atts ) );

			if ( 'yes' === $display_toggle ) {
				$this->theme        = $theme;
				$this->purchase_url = $purchase_link;

				add_action( 'wp_footer', array( $this, 'add_demo_toggle' ) );
			}

			$output  = '<div class="svl-demos" style="visibility: visible;">';
			$output .= do_shortcode( $content );
			$output .= '</div>';

			return $output;
		}
	}

	new Svl_Demos();

	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {

		/**
		 * Class WPBakeryShortCode_Svl_Demos
		 */
		class WPBakeryShortCode_Svl_Demos extends WPBakeryShortCodesContainer {} // phpcs:ignore
	}
}
