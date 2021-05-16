<?php
/**
 * Plugin Name: SVL Demo Toggle
 * Description: Adds a toggle to your website to display an array of demo sites set via shortcodes.  Useful for selling themes.
 * Version: 1.0.0
 * Author: SVL Studios
 * Author URI: https://github.com/svl-studios/svl-demo-toggle
 * Plugin URI: https://github.com/svl-studios/svl-demo-toggle
 * Text Domain: svl-demos
 *
 * Copyright 2021, Kevin Provance <kevin.provance@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package   SVL Demo Toggle
 * @author    Kevin Provance <kevin.provance@gmail.com>
 * @license   GNU General Public License, version 3
 * @copyright Copyright 2021, SVL Studios.  All Rights Reserved.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Svl_Demo_Toggle' ) ) {

	/**
	 * Class Svl_Demo_Toggle
	 */
	class Svl_Demo_Toggle {

		/**
		 * Svl_Demo_Toggle constructor.
		 */
		public function __construct() {
			require_once plugin_dir_path( __FILE__ ) . '/class-color.php';
			require_once plugin_dir_path( __FILE__ ) . '/class-svl-demos.php';
			require_once plugin_dir_path( __FILE__ ) . '/class-svl-demo.php';

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		}

		/**
		 * Enqueue support files.
		 */
		public function enqueue() {
			wp_enqueue_style(
				'svl-demo-toggle',
				plugin_dir_url( __FILE__ ) . '/svl-demo-toggle.css',
				array(),
				'1.0.0'
			);

			wp_enqueue_script(
				'svl-demo-toggle',
				plugin_dir_url( __FILE__ ) . '/svl-demo-toggle.js',
				array(),
				'1.0.0',
				true
			);

			wp_localize_script(
				'svl-demo-toggle',
				'svlDemoOptions',
				array(
					'baseURL' => home_url(),
					'pageID'  => get_the_ID(),
				)
			);
		}
	}

	new Svl_Demo_Toggle();
}
