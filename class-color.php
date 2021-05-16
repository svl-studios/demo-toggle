<?php
/**
 * PHP Color Modification.
 *
 * @package     SVL Demo Toggle
 * @author      SVL Studios
 * @copyright   Copyright (c) 2021, SVL Studios
 * @link        https://www.svlstudios.com
 * @since       SVL Demo Toggle 1.0
 */

/**
 * This code has been mofied from the following source:
 *
 * Author: Arlo Carreon <http://arlocarreon.com>
 * Info: http://mexitek.github.io/phpColors/
 * License: http://arlo.mit-license.org/
 */

namespace SVL\PHPColors;

defined( 'ABSPATH' ) || exit;

use Exception;

/**
 * A color utility that helps manipulate HEX colors
 */
class Color {

	/**
	 * Hex string.
	 *
	 * @var string
	 */
	private $hex;

	/**
	 * HSL array.
	 *
	 * @var array
	 */
	private $hsl;

	/**
	 * RGB array.
	 *
	 * @var array
	 */
	private $rgb;

	/**
	 * Auto darkens/lightens by 10% for sexily-subtle gradients.
	 * Set this to FALSE to adjust automatic shade to be between given color
	 * and black (for darken) or white (for lighten)
	 */
	public const DEFAULT_ADJUST = 10;

	/**
	 * Instantiates the class with a HEX value
	 *
	 * @param string $hex Hex string.
	 */
	public function __construct( string $hex ) {
		$color     = self::sanitize_hex( $hex );
		$this->hex = $color;
		$this->hsl = self::hex_to_hsl( $color );
		$this->rgb = self::hex_to_rgb( $color );
	}

	/**
	 * Given a HEX string returns a HSL array equivalent.
	 *
	 * @param string $color Hex value.
	 *
	 * @return array HSL associative array
	 */
	public static function hex_to_hsl( string $color ): array {
		// Sanity check.
		$color = self::sanitize_hex( $color );

		// Convert HEX to DEC.
		$r = hexdec( $color[0] . $color[1] );
		$g = hexdec( $color[2] . $color[3] );
		$b = hexdec( $color[4] . $color[5] );

		$hsl = array();

		$var_r = ( $r / 255 );
		$var_g = ( $g / 255 );
		$var_b = ( $b / 255 );

		$var_min = min( $var_r, $var_g, $var_b );
		$var_max = max( $var_r, $var_g, $var_b );
		$del_max = $var_max - $var_min;

		$l = ( $var_max + $var_min ) / 2;

		if ( $del_max == 0 ) {
			$h = 0;
			$s = 0;
		} else {
			if ( $l < 0.5 ) {
				$s = $del_max / ( $var_max + $var_min );
			} else {
				$s = $del_max / ( 2 - $var_max - $var_min );
			}

			$del_r = ( ( ( $var_max - $var_r ) / 6 ) + ( $del_max / 2 ) ) / $del_max;
			$del_g = ( ( ( $var_max - $var_g ) / 6 ) + ( $del_max / 2 ) ) / $del_max;
			$del_b = ( ( ( $var_max - $var_b ) / 6 ) + ( $del_max / 2 ) ) / $del_max;

			if ( $var_r == $var_max ) {
				$h = $del_b - $del_g;
			} elseif ( $var_g == $var_max ) {
				$h = ( 1 / 3 ) + $del_r - $del_b;
			} elseif ( $var_b == $var_max ) {
				$h = ( 2 / 3 ) + $del_g - $del_r;
			}

			if ( $h < 0 ) {
				$h ++;
			}
			if ( $h > 1 ) {
				$h --;
			}
		}

		$hsl['H'] = ( $h * 360 );
		$hsl['S'] = $s;
		$hsl['L'] = $l;

		return $hsl;
	}

	/**
	 * Given a HSL associative array returns the equivalent HEX string
	 *
	 * @param array $hsl HSL array.
	 *
	 * @return string HEX string
	 * @throws Exception "Bad HSL Array".
	 */
	public static function hsl_to_hex( array $hsl = array() ): string {
		// Make sure it's HSL.
		if ( empty( $hsl ) || ! isset( $hsl['H'], $hsl['S'], $hsl['L'] ) ) {
			throw new Exception( 'Param was not an HSL array' );
		}

		list( $h, $s, $l ) = array( $hsl['H'] / 360, $hsl['S'], $hsl['L'] );

		if ( $s == 0 ) {
			$r = $l * 255;
			$g = $l * 255;
			$b = $l * 255;
		} else {
			if ( $l < 0.5 ) {
				$var_2 = $l * ( 1 + $s );
			} else {
				$var_2 = ( $l + $s ) - ( $s * $l );
			}

			$var_1 = 2 * $l - $var_2;

			$r = round( 255 * self::hue_to_rgb( $var_1, $var_2, $h + ( 1 / 3 ) ) );
			$g = round( 255 * self::hue_to_rgb( $var_1, $var_2, $h ) );
			$b = round( 255 * self::hue_to_rgb( $var_1, $var_2, $h - ( 1 / 3 ) ) );
		}

		// Convert to hex.
		$r = dechex( $r );
		$g = dechex( $g );
		$b = dechex( $b );

		// Make sure we get 2 digits for decimals.
		$r = ( strlen( '' . $r ) === 1 ) ? '0' . $r : $r;
		$g = ( strlen( '' . $g ) === 1 ) ? '0' . $g : $g;
		$b = ( strlen( '' . $b ) === 1 ) ? '0' . $b : $b;

		return $r . $g . $b;
	}


	/**
	 * Given a HEX string returns a RGB array equivalent.
	 *
	 * @param string $color Hex string.
	 *
	 * @return array RGB associative array
	 */
	public static function hex_to_rgb( string $color ): array {
		// Sanity check.
		$color = self::sanitize_hex( $color );

		// Convert HEX to DEC.
		$r = hexdec( $color[0] . $color[1] );
		$g = hexdec( $color[2] . $color[3] );
		$b = hexdec( $color[4] . $color[5] );

		$rgb['R'] = $r;
		$rgb['G'] = $g;
		$rgb['B'] = $b;

		return $rgb;
	}


	/**
	 * Given an RGB associative array returns the equivalent HEX string
	 *
	 * @param array $rgb RGB array.
	 *
	 * @return string Hex string
	 * @throws Exception "Bad RGB Array".
	 */
	public static function rgb_to_hex( array $rgb = array() ): string {
		// Make sure it's RGB.
		if ( empty( $rgb ) || ! isset( $rgb['R'], $rgb['G'], $rgb['B'] ) ) {
			throw new Exception( 'Param was not an RGB array' );
		}

		// https://github.com/mexitek/phpColors/issues/25#issuecomment-88354815
		// Convert RGB to HEX.
		$hex[0] = str_pad( dechex( $rgb['R'] ), 2, '0', STR_PAD_LEFT );
		$hex[1] = str_pad( dechex( $rgb['G'] ), 2, '0', STR_PAD_LEFT );
		$hex[2] = str_pad( dechex( $rgb['B'] ), 2, '0', STR_PAD_LEFT );

		// Make sure that 2 digits are allocated to each color.
		$hex[0] = ( strlen( $hex[0] ) === 1 ) ? '0' . $hex[0] : $hex[0];
		$hex[1] = ( strlen( $hex[1] ) === 1 ) ? '0' . $hex[1] : $hex[1];
		$hex[2] = ( strlen( $hex[2] ) === 1 ) ? '0' . $hex[2] : $hex[2];

		return implode( '', $hex );
	}

	/**
	 * Given an RGB associative array, returns CSS string output.
	 *
	 * @param array $rgb RGB array.
	 *
	 * @return string rgb(r,g,b) string
	 * @throws Exception "Bad RGB Array".
	 */
	public static function rgb_to_string( array $rgb = array() ): string {
		// Make sure it's RGB.
		if ( empty( $rgb ) || ! isset( $rgb['R'], $rgb['G'], $rgb['B'] ) ) {
			throw new Exception( 'Param was not an RGB array' );
		}

		return 'rgb(' . $rgb['R'] . ', ' . $rgb['G'] . ', ' . $rgb['B'] . ')';
	}

	/**
	 * Given a standard color name, return hex code
	 *
	 * @param string $color_name Color name.
	 *
	 * @return string
	 */
	public static function name_to_hex( string $color_name ): string {
		$colors = array(
			'aliceblue'            => 'F0F8FF',
			'antiquewhite'         => 'FAEBD7',
			'aqua'                 => '00FFFF',
			'aquamarine'           => '7FFFD4',
			'azure'                => 'F0FFFF',
			'beige'                => 'F5F5DC',
			'bisque'               => 'FFE4C4',
			'black'                => '000000',
			'blanchedalmond'       => 'FFEBCD',
			'blue'                 => '0000FF',
			'blueviolet'           => '8A2BE2',
			'brown'                => 'A52A2A',
			'burlywood'            => 'DEB887',
			'cadetblue'            => '5F9EA0',
			'chartreuse'           => '7FFF00',
			'chocolate'            => 'D2691E',
			'coral'                => 'FF7F50',
			'cornflowerblue'       => '6495ED',
			'cornsilk'             => 'FFF8DC',
			'crimson'              => 'DC143C',
			'cyan'                 => '00FFFF',
			'darkblue'             => '00008B',
			'darkcyan'             => '008B8B',
			'darkgoldenrod'        => 'B8860B',
			'darkgray'             => 'A9A9A9',
			'darkgreen'            => '006400',
			'darkgrey'             => 'A9A9A9',
			'darkkhaki'            => 'BDB76B',
			'darkmagenta'          => '8B008B',
			'darkolivegreen'       => '556B2F',
			'darkorange'           => 'FF8C00',
			'darkorchid'           => '9932CC',
			'darkred'              => '8B0000',
			'darksalmon'           => 'E9967A',
			'darkseagreen'         => '8FBC8F',
			'darkslateblue'        => '483D8B',
			'darkslategray'        => '2F4F4F',
			'darkslategrey'        => '2F4F4F',
			'darkturquoise'        => '00CED1',
			'darkviolet'           => '9400D3',
			'deeppink'             => 'FF1493',
			'deepskyblue'          => '00BFFF',
			'dimgray'              => '696969',
			'dimgrey'              => '696969',
			'dodgerblue'           => '1E90FF',
			'firebrick'            => 'B22222',
			'floralwhite'          => 'FFFAF0',
			'forestgreen'          => '228B22',
			'fuchsia'              => 'FF00FF',
			'gainsboro'            => 'DCDCDC',
			'ghostwhite'           => 'F8F8FF',
			'gold'                 => 'FFD700',
			'goldenrod'            => 'DAA520',
			'gray'                 => '808080',
			'green'                => '008000',
			'greenyellow'          => 'ADFF2F',
			'grey'                 => '808080',
			'honeydew'             => 'F0FFF0',
			'hotpink'              => 'FF69B4',
			'indianred'            => 'CD5C5C',
			'indigo'               => '4B0082',
			'ivory'                => 'FFFFF0',
			'khaki'                => 'F0E68C',
			'lavender'             => 'E6E6FA',
			'lavenderblush'        => 'FFF0F5',
			'lawngreen'            => '7CFC00',
			'lemonchiffon'         => 'FFFACD',
			'lightblue'            => 'ADD8E6',
			'lightcoral'           => 'F08080',
			'lightcyan'            => 'E0FFFF',
			'lightgoldenrodyellow' => 'FAFAD2',
			'lightgray'            => 'D3D3D3',
			'lightgreen'           => '90EE90',
			'lightgrey'            => 'D3D3D3',
			'lightpink'            => 'FFB6C1',
			'lightsalmon'          => 'FFA07A',
			'lightseagreen'        => '20B2AA',
			'lightskyblue'         => '87CEFA',
			'lightslategray'       => '778899',
			'lightslategrey'       => '778899',
			'lightsteelblue'       => 'B0C4DE',
			'lightyellow'          => 'FFFFE0',
			'lime'                 => '00FF00',
			'limegreen'            => '32CD32',
			'linen'                => 'FAF0E6',
			'magenta'              => 'FF00FF',
			'maroon'               => '800000',
			'mediumaquamarine'     => '66CDAA',
			'mediumblue'           => '0000CD',
			'mediumorchid'         => 'BA55D3',
			'mediumpurple'         => '9370D0',
			'mediumseagreen'       => '3CB371',
			'mediumslateblue'      => '7B68EE',
			'mediumspringgreen'    => '00FA9A',
			'mediumturquoise'      => '48D1CC',
			'mediumvioletred'      => 'C71585',
			'midnightblue'         => '191970',
			'mintcream'            => 'F5FFFA',
			'mistyrose'            => 'FFE4E1',
			'moccasin'             => 'FFE4B5',
			'navajowhite'          => 'FFDEAD',
			'navy'                 => '000080',
			'oldlace'              => 'FDF5E6',
			'olive'                => '808000',
			'olivedrab'            => '6B8E23',
			'orange'               => 'FFA500',
			'orangered'            => 'FF4500',
			'orchid'               => 'DA70D6',
			'palegoldenrod'        => 'EEE8AA',
			'palegreen'            => '98FB98',
			'paleturquoise'        => 'AFEEEE',
			'palevioletred'        => 'DB7093',
			'papayawhip'           => 'FFEFD5',
			'peachpuff'            => 'FFDAB9',
			'peru'                 => 'CD853F',
			'pink'                 => 'FFC0CB',
			'plum'                 => 'DDA0DD',
			'powderblue'           => 'B0E0E6',
			'purple'               => '800080',
			'red'                  => 'FF0000',
			'rosybrown'            => 'BC8F8F',
			'royalblue'            => '4169E1',
			'saddlebrown'          => '8B4513',
			'salmon'               => 'FA8072',
			'sandybrown'           => 'F4A460',
			'seagreen'             => '2E8B57',
			'seashell'             => 'FFF5EE',
			'sienna'               => 'A0522D',
			'silver'               => 'C0C0C0',
			'skyblue'              => '87CEEB',
			'slateblue'            => '6A5ACD',
			'slategray'            => '708090',
			'slategrey'            => '708090',
			'snow'                 => 'FFFAFA',
			'springgreen'          => '00FF7F',
			'steelblue'            => '4682B4',
			'tan'                  => 'D2B48C',
			'teal'                 => '008080',
			'thistle'              => 'D8BFD8',
			'tomato'               => 'FF6347',
			'turquoise'            => '40E0D0',
			'violet'               => 'EE82EE',
			'wheat'                => 'F5DEB3',
			'white'                => 'FFFFFF',
			'whitesmoke'           => 'F5F5F5',
			'yellow'               => 'FFFF00',
			'yellowgreen'          => '9ACD32',
		);

		$color_name = strtolower( $color_name );
		if ( isset( $colors[ $color_name ] ) ) {
			return '#' . $colors[ $color_name ];
		}

		return $color_name;
	}

	/**
	 * Given a HEX value, returns a darker color. If no desired amount provided, then the color halfway between
	 * given HEX and black will be returned.
	 *
	 * @param int $amount Lum amount.
	 *
	 * @return string Darker HEX value
	 */
	public function darken( int $amount = self::DEFAULT_ADJUST ): string {
		// Darken.
		$darker_hsl = $this->darken_hsl( $this->hsl, $amount );

		// Return as HEX.
		return self::hsl_to_hex( $darker_hsl );
	}

	/**
	 * Given a HEX value, returns a lighter color. If no desired amount provided, then the color halfway between
	 * given HEX and white will be returned.
	 *
	 * @param int $amount Lum amount.
	 *
	 * @return string Lighter HEX value
	 */
	public function lighten( int $amount = self::DEFAULT_ADJUST ): string {
		// Lighten.
		$lighter_hsl = $this->lighten_hsl( $this->hsl, $amount );

		// Return as HEX.
		return self::hsl_to_hex( $lighter_hsl );
	}

	/**
	 * Given a HEX value, returns a mixed color. If no desired amount provided, then the color mixed by this ratio
	 *
	 * @param string $hex2   Secondary HEX value to mix with.
	 * @param int    $amount = -100..0..+100.
	 *
	 * @return string mixed HEX value
	 */
	public function mix( string $hex2, int $amount = 0 ): string {
		$rgb2  = self::hex_to_rgb( $hex2 );
		$mixed = $this->mix_rgb( $this->rgb, $rgb2, $amount );

		// Return as HEX.
		return self::rgb_to_hex( $mixed );
	}

	/**
	 * Creates an array with two shades that can be used to make a gradient
	 *
	 * @param int $amount Optional percentage amount you want your contrast color.
	 *
	 * @return array An array with a 'light' and 'dark' index
	 */
	public function make_gradient( int $amount = self::DEFAULT_ADJUST ): array {
		// Decide which color needs to be made.
		if ( $this->is_light() ) {
			$lightcolor = $this->hex;
			$darkcolor  = $this->darken( $amount );
		} else {
			$lightcolor = $this->lighten( $amount );
			$darkcolor  = $this->hex;
		}

		// Return our gradient array.
		return array(
			'light' => $lightcolor,
			'dark'  => $darkcolor,
		);
	}


	/**
	 * Returns whether or not given color is considered "light"
	 *
	 * @param string|bool $color Value.
	 * @param int         $lighter_than Value.
	 *
	 * @return boolean
	 */
	public function is_light( $color = false, int $lighter_than = 130 ): bool {
		// Get our color.
		$color = ( $color ) ? $color : $this->hex;

		// Calculate straight from rbg.
		$r = hexdec( $color[0] . $color[1] );
		$g = hexdec( $color[2] . $color[3] );
		$b = hexdec( $color[4] . $color[5] );

		return ( ( $r * 299 + $g * 587 + $b * 114 ) / 1000 > $lighter_than );
	}

	/**
	 * Returns whether or not a given color is considered "dark"
	 *
	 * @param string|bool $color Value.
	 * @param int         $darker_than Value.
	 *
	 * @return boolean
	 */
	public function is_dark( $color = false, int $darker_than = 130 ): bool {
		// Get our color.
		$color = ( $color ) ? $color : $this->hex;

		// Calculate straight from rbg.
		$r = hexdec( $color[0] . $color[1] );
		$g = hexdec( $color[2] . $color[3] );
		$b = hexdec( $color[4] . $color[5] );

		return ( ( $r * 299 + $g * 587 + $b * 114 ) / 1000 <= $darker_than );
	}

	/**
	 * Returns the complimentary color
	 *
	 * @return string Complementary hex color
	 */
	public function complementary(): string {
		// Get our HSL.
		$hsl = $this->hsl;

		// Adjust Hue 180 degrees.
		$hsl['H'] += ( $hsl['H'] > 180 ) ? - 180 : 180;

		// Return the new value in HEX.
		return self::hsl_to_hex( $hsl );
	}

	/**
	 * Returns the HSL array of your color
	 */
	public function get_hsl(): array {
		return $this->hsl;
	}

	/**
	 * Returns your original color
	 */
	public function get_hex(): string {
		return $this->hex;
	}

	/**
	 * Returns the RGB array of your color
	 */
	public function get_rgb(): array {
		return $this->rgb;
	}

	/**
	 * Returns the cross browser CSS3 gradient
	 *
	 * @param int     $amount           Optional: percentage amount to light/darken the gradient.
	 * @param boolean $vintage_browsers Optional: include vendor prefixes for browsers that almost died out already.
	 * @param string  $suffix           Optional: prefix for every lines.
	 * @param string  $prefix           Optional: suffix for every lines.
	 *
	 * @return string CSS3 gradient for chrome, safari, firefox, opera and IE10
	 * @link http://caniuse.com/css-gradients Resource for the browser support
	 */
	public function get_css_gradient( int $amount = self::DEFAULT_ADJUST, bool $vintage_browsers = false, string $suffix = '', string $prefix = '' ): string {
		// Get the recommended gradient.
		$g = $this->make_gradient( $amount );

		$css = '';
		/* fallback/image non-cover color */
		$css .= "{$prefix}background-color: #" . $this->hex . ";$suffix";

		/* IE Browsers */
		$css .= "{$prefix}filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#" . $g['light'] . "', endColorstr='#" . $g['dark'] . "');$suffix";

		/* Safari 4+, Chrome 1-9 */
		if ( $vintage_browsers ) {
			$css .= "{$prefix}background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#" . $g['light'] . '), to(#' . $g['dark'] . "));$suffix";
		}

		/* Safari 5.1+, Mobile Safari, Chrome 10+ */
		$css .= "{$prefix}background-image: -webkit-linear-gradient(top, #" . $g['light'] . ', #' . $g['dark'] . ");$suffix";

		if ( $vintage_browsers ) {
			/* Firefox 3.6+ */
			$css .= "{$prefix}background-image: -moz-linear-gradient(top, #" . $g['light'] . ', #' . $g['dark'] . ");$suffix";

			/* Opera 11.10+ */
			$css .= "{$prefix}background-image: -o-linear-gradient(top, #" . $g['light'] . ', #' . $g['dark'] . ");$suffix";
		}

		/* Unprefixed version (standards): FF 16+, IE10+, Chrome 26+, Safari 7+, Opera 12.1+ */
		$css .= "{$prefix}background-image: linear-gradient(to bottom, #" . $g['light'] . ', #' . $g['dark'] . ");$suffix";

		// Return our CSS.
		return $css;
	}

	/**
	 * Darkens a given HSL array
	 *
	 * @param array $hsl HSL array.
	 * @param int   $amount Lum amount.
	 *
	 * @return array $hsl
	 */
	private function darken_hsl( array $hsl, int $amount = self::DEFAULT_ADJUST ): array {
		// Check if we were provided a number.
		if ( $amount ) {
			$hsl['L'] = ( $hsl['L'] * 100 ) - $amount;
			$hsl['L'] = ( $hsl['L'] < 0 ) ? 0 : $hsl['L'] / 100;
		} else {
			// We need to find out how much to darken.
			$hsl['L'] /= 2;
		}

		return $hsl;
	}

	/**
	 * Lightens a given HSL array
	 *
	 * @param array $hsl HSL array.
	 * @param int   $amount Lum amount.
	 *
	 * @return array
	 */
	private function lighten_hsl( array $hsl, int $amount = self::DEFAULT_ADJUST ): array {
		// Check if we were provided a number.
		if ( $amount ) {
			$hsl['L'] = ( $hsl['L'] * 100 ) + $amount;
			$hsl['L'] = ( $hsl['L'] > 100 ) ? 1 : $hsl['L'] / 100;
		} else {
			// We need to find out how much to lighten.
			$hsl['L'] += ( 1 - $hsl['L'] ) / 2;
		}

		return $hsl;
	}

	/**
	 * Mix two RGB colors and return the resulting RGB color
	 * ported from http://phpxref.pagelines.com/nav.html?includes/class.colors.php.source.html
	 *
	 * @param array $rgb1 First TDG value.
	 * @param array $rgb2 Second RGB value.
	 * @param int   $amount ranged -100..0..+100.
	 *
	 * @return array
	 */
	private function mix_rgb( array $rgb1, array $rgb2, int $amount = 0 ): array {
		$r1 = ( $amount + 100 ) / 100;
		$r2 = 2 - $r1;

		$rmix = ( ( $rgb1['R'] * $r1 ) + ( $rgb2['R'] * $r2 ) ) / 2;
		$gmix = ( ( $rgb1['G'] * $r1 ) + ( $rgb2['G'] * $r2 ) ) / 2;
		$bmix = ( ( $rgb1['B'] * $r1 ) + ( $rgb2['B'] * $r2 ) ) / 2;

		return array(
			'R' => $rmix,
			'G' => $gmix,
			'B' => $bmix,
		);
	}

	/**
	 * Given a Hue, returns corresponding RGB value
	 *
	 * @param float $v1 One.
	 * @param float $v2 Two.
	 * @param float $vh Three.
	 *
	 * @return float
	 */
	private static function hue_to_rgb( float $v1, float $v2, float $vh ): float {
		if ( $vh < 0 ) {
			++ $vh;
		}

		if ( $vh > 1 ) {
			-- $vh;
		}

		if ( ( 6 * $vh ) < 1 ) {
			return ( $v1 + ( $v2 - $v1 ) * 6 * $vh );
		}

		if ( ( 2 * $vh ) < 1 ) {
			return $v2;
		}

		if ( ( 3 * $vh ) < 2 ) {
			return ( $v1 + ( $v2 - $v1 ) * ( ( 2 / 3 ) - $vh ) * 6 );
		}

		return $v1;
	}

	/**
	 * Checks the HEX string for correct formatting and converts short format to long
	 *
	 * @param string $hex Hex string.
	 *
	 * @return string
	 * @throws Exception "Bad format".
	 */
	private static function sanitize_hex( string $hex ): string {
		// Strip # sign if it is present.
		$color = str_replace( '#', '', $hex );

		// Validate hex string.
		if ( ! preg_match( '/^[a-fA-F0-9]+$/', $color ) ) {
			throw new Exception( 'HEX color does not match format' );
		}

		// Make sure it's 6 digits.
		if ( strlen( $color ) === 3 ) {
			$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
		} elseif ( strlen( $color ) !== 6 ) {
			throw new Exception( 'HEX color needs to be 6 or 3 digits long' );
		}

		return $color;
	}

	/**
	 * Converts object into its string representation
	 *
	 * @return string
	 */
	public function __toString() {
		return '#' . $this->get_hex();
	}

	/**
	 * _get.
	 *
	 * @param string $name Name.
	 *
	 * @return mixed|null
	 */
	public function __get( string $name ) {
		switch ( strtolower( $name ) ) {
			case 'red':
			case 'r':
				return $this->rgb['R'];
			case 'green':
			case 'g':
				return $this->rgb['G'];
			case 'blue':
			case 'b':
				return $this->rgb['B'];
			case 'hue':
			case 'h':
				return $this->hsl['H'];
			case 'saturation':
			case 's':
				return $this->hsl['S'];
			case 'lightness':
			case 'l':
				return $this->hsl['L'];
		}

		$trace = debug_backtrace(); // phpcs:ignore

		// phpcs:ignore
		trigger_error(
			'Undefined property via __get(): ' . esc_html( $name ) . ' in ' . esc_html( $trace[0]['file'] ) . ' on line ' . esc_html( $trace[0]['line'] ),
			E_USER_NOTICE
		);

		return null;
	}

	/**
	 * _Set.
	 *
	 * @param string $name Name.
	 * @param mixed  $value Value.
	 */
	public function __set( string $name, $value ) {
		switch ( strtolower( $name ) ) {
			case 'red':
			case 'r':
				$this->rgb['R'] = $value;
				$this->hex      = self::rgb_to_hex( $this->rgb );
				$this->hsl      = self::hex_to_hsl( $this->hex );
				break;
			case 'green':
			case 'g':
				$this->rgb['G'] = $value;
				$this->hex      = self::rgb_to_hex( $this->rgb );
				$this->hsl      = self::hex_to_hsl( $this->hex );
				break;
			case 'blue':
			case 'b':
				$this->rgb['B'] = $value;
				$this->hex      = self::rgb_to_hex( $this->rgb );
				$this->hsl      = self::hex_to_hsl( $this->hex );
				break;
			case 'hue':
			case 'h':
				$this->hsl['H'] = $value;
				$this->hex      = self::hsl_to_hex( $this->hsl );
				$this->rgb      = self::hex_to_rgb( $this->hex );
				break;
			case 'saturation':
			case 's':
				$this->hsl['S'] = $value;
				$this->hex      = self::hsl_to_hex( $this->hsl );
				$this->rgb      = self::hex_to_rgb( $this->hex );
				break;
			case 'lightness':
			case 'light':
			case 'l':
				$this->hsl['L'] = $value;
				$this->hex      = self::hsl_to_hex( $this->hsl );
				$this->rgb      = self::hex_to_rgb( $this->hex );
				break;
		}
	}
}
