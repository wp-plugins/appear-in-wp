<?php
/**
 * Plugin Name: appear.in WP
 * Plugin URI: http://vandercar.net/wp/appear-in-wp
 * Description: Adds appear.in rooms to your site via shortcode
 * Version: 2.6
 * Author: UaMV
 * Author URI: http://vandercar.net
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package appear.in WP
 * @version 2.6
 * @author UaMV
 * @copyright Copyright (c) 2013, UaMV
 * @link http://vandercar.net/wp/appear-in-wp
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Define constants.
 */

define( 'AIWP_VERSION', '2.6' );
define( 'AIWP_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'AIWP_DIR_URL', plugin_dir_url( __FILE__ ) );
! defined( 'AIWP_SHOW_TOGGLE' ) ? define( 'AIWP_SHOW_TOGGLE', TRUE ) : FALSE;
! defined( 'AIWP_SHOW_INVITE' ) ? define( 'AIWP_SHOW_INVITE', TRUE ) : FALSE;

/**
 * Include files.
 */

require_once AIWP_DIR_PATH . 'class-aiwp-admin.php';
require_once AIWP_DIR_PATH . 'mobile-detect.php';
is_admin() ? require_once AIWP_DIR_PATH . 'wp-side-notice/class-wp-side-notice.php' : FALSE;

/**
 * Get instance of class.
 */

Appear_In_WP::get_instance();

/**
 * Glance That Class
 *
 * Extends functionality of the Dashboard's At a Glance metabox
 *
 * @package Glance That
 * @author  UaMV
 */
class Appear_In_WP {

	/*---------------------------------------------------------------------------------*
	 * Attributes
	 *---------------------------------------------------------------------------------*/

	/**
	 * Instance of this class
	 *
	 * @since    1.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Custom plugin settings
	 *
	 * @since    1.0
	 *
	 * @var      object
	 */
	protected $options;

	/**
	 * User device detection
	 *
	 * @since    1.0
	 *
	 * @var      object
	 */
	protected $detect;

	/*---------------------------------------------------------------------------------*
	 * Constructor
	 *---------------------------------------------------------------------------------*/

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0
	 */
	private function __construct() {

		// retrieve custom plugin settings
		$this->options = get_option( 'aiwp_settings', array() );

		// get user device info
		$this->detect = new Mobile_Detect;

		// if admin area, get instance of admin class
		if ( is_admin() ) { Appear_In_WP_Admin::get_instance(); }
		// else, check for shortcode presence and respond accordingly
		else {
			add_action( 'template_redirect', array( $this, 'respond_to_shortcode' ) );
		}

		// add the shortcode
		add_shortcode( 'appear_in', array( $this, 'display_shortcode' ) );

	} // end __construct

	/*---------------------------------------------------------------------------------*
	 * Public Functions
	 *---------------------------------------------------------------------------------*/

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// if the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		} // end if

		return self::$instance;

	} // end get_instance


	/**
	 * Check for shortcode and enqueue scripts if shortcode is present.
	 *
	 * @since     1.0
	 */
	public function respond_to_shortcode() {

		// get post object
		global $post;
		
		// get post object
		$post_obj = get_post( $post->ID );

		// check if shortcode is present in post content
		if ( stripos( $post_obj->post_content, '[appear_in' ) !== FALSE ) {

			// call to enqueue scripts and styles needed for shortcode
			add_action( 'wp_enqueue_scripts', array( $this, 'add_stylesheets_and_javascript' ) );

		} // end if

	} // end respond_to_shortcode

	/**
	 * Registers the front-end stylesheets and scripts
	 *
	 * @since    1.0
	 */
	public function add_stylesheets_and_javascript() {

		// enqueue stylesheet
		wp_enqueue_style( 'aiwp-style', AIWP_DIR_URL . 'aiwp.css', array(), AIWP_VERSION, 'screen' );

		// enqueue jquery on front-end
		wp_enqueue_script( 'jquery' );

		// enqueue appear-in API library
		wp_enqueue_script( 'appearin-library', '//developer.appear.in/scripts/appearin-sdk.0.0.4.min.js', array(), AIWP_VERSION );

		// enqueue script for handling local interaction
		wp_enqueue_script( 'aiwp', AIWP_DIR_URL . 'aiwp.js', array(), AIWP_VERSION );

		// enqueue font-awesome CDN
		wp_enqueue_style( 'aiwp-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css', array(), '4.0.3' );

	} // end add_stylesheets_and_javascript

	/**
	 * Returns the shortcode content
	 *
	 * @since    1.0
	 *
	 * @return   string   HTML content displayed by shortcode.
	 */
	public function display_shortcode( $atts, $content = '' ) {

		// extract the shortcode parameters
		extract( shortcode_atts( array(
			'room' => '',
			'type' => 'public',
			'position' => '',
			'height' => '700',
			'source' => 'shortcode',
		), $atts ) );

		// push the shortcode defined rooom types to an array
		$aiwp_room_types = explode( ',', str_replace( ' ', '', $type ) );

		// get public room name from shortcode, otherwise from custom options, otherwise from plugins randomly assigned & daily dynamic name
		if ( '' != $room ) {
			$custom_room_name = $room;
		} elseif ( isset( $this->options['room'] ) && '' != $this->options['room'] ) {
			$custom_room_name = $this->options['room'];
		} else {
			$custom_room_name = get_option( 'aiwp_public_room' );
		}

		// get position from shortcode, otherwise set to left
		if ( 'bottom' == $position ) {
			$position = 'bottom';
		} elseif ( 'inline' != $position ) {
			$position = 'left';
		}

		// set room height
		if ( 'bottom' == $position ) {
			$height = 275;
		}

		// build room selection wrapper
		// add styling for iconset
		$text_color = $this->is_color_light( $this->options['color'] ) ? 'black' : 'white';

		$html = '<div id="aiwp-container" data-source="' . $source . '" data-position="' . $position . '" data-room-height="' . $height . '"';
			$html .= $this->detect->isiOS() ? ' class="aiwp-ios">' : '>';
			$html .= '<style type="text/css">
						#aiwp-container button {
							background: ' . $this->options['color'] . ';
							color: ' . $text_color . ';
						}			
						#aiwp-container button:hover {
							background: ' . $this->hex_color_mod( $this->options['color'], -16 ) . ';
						}
					</style>';

			$html .= '<div id="aiwp-room-type-selection">';

				foreach ( $aiwp_room_types as $room_type ) {

					$room_button_text = isset( $atts[ $room_type . '_room_button' ] ) ? $atts[ $room_type . '_room_button' ] : ucfirst( $room_type ) . ' Room';
					
					if ( $this->detect->isiOS() ){
						// display room buttons
						$html .= '<a href="#" id="aiwp-' . $room_type . '" style="display:inline-block;width:' . ( 100 / count( $aiwp_room_types ) ) . '%">';
							$html .= '<button id="aiwp-select-' . $room_type . '-room" data-room-type="' . $room_type . '">' . $room_button_text . '</button>';
						$html .= '</a>';
					} else {
						// display room buttons
						$html .= '<div id="aiwp-' . $room_type . '" style="width:' . ( 100 / count( $aiwp_room_types ) ) . '%">';
							$html .= '<button id="aiwp-select-' . $room_type . '-room" data-room-type="' . $room_type . '">' . $room_button_text . '</button>';
						$html .= '</div>';
					}
				
				}

			$html .= '</div>';

			// build compatibility test result
			$html .= '<span id="appearin-incompatibility" style="display:none;">' . apply_filters( 'aiwp_unsupported_browser_message', 'It appears your browser is not capable of displaying this content. Try connecting with Chrome, Firefox, or Opera.' ) . '</span>';

			// include appearin iframe populated by API
			$html .= '<iframe id="appearin-room" data-room-name="' . $custom_room_name . '"></iframe>';	

			$html .= '<div id="aiwp-controls" style="display:none;">';

				if ( AIWP_SHOW_TOGGLE ) {

					$html .= '<div id="aiwp-toggles">';

						// add ui buttons
						$html .= '<div class="aiwp-ui-buttons">';
							$html .= '<a href="#" id="aiwp-minimize"><i class="fa fa-arrow-down"></i></a>';
							$html .= '<a href="#" id="aiwp-move-bottom" ';
								$html .= 'left' == $position ? '' : 'style="display:none;"';
								$html .= '><i class="fa fa-columns"></i></a>';
							$html .= '<a href="#" id="aiwp-move-left" ';
								$html .= 'bottom' == $position ? '' : 'style="display:none;"';
								$html .= '><i class="fa fa-columns"></i></a>';
						$html .= '</div>';

					$html .= '</div>';

				}

				if ( AIWP_SHOW_INVITE ) {

					$html .= '<div id="aiwp-invites">';

						// add social invites
						$html .= '<div class="aiwp-invite-buttons">';
							$html .= '<span style="width:75px;font-family:Helvetica;color: #d1d1d1;font-size: 14px;font-weight: normal;position: relative;display: inline-block;top: -.25em;">Invite via</span>';
							$html .= '<a href="#" id="aiwp-invite-twitter" class="aiwp-social" target="_blank"><i class="fa fa-twitter"></i></a>';
							$html .= '<a href="#" id="aiwp-invite-facebook" class="aiwp-social" target="_blank"><i class="fa fa-facebook-square"></i></a>';
							$html .= '<a href="#" id="aiwp-invite-email" class="aiwp-social" target="_blank"><i class="fa fa-envelope"></i></a>';
						$html .= '</div>';

					$html .= '</div>';

				}

			$html .= '</div>';

		$html .= '</div>';

		$html .= '<img id="aiwp-maximize" src="' . AIWP_DIR_URL . 'appearin-logo-transparent.png" />';

		return $html;

	} // end display_shortcode

	/**
	 * Change the brightness of the passed in color
	 *
	 * $diff should be negative to go darker, positive to go lighter and
	 * is subtracted from the decimal (0-255) value of the color
	 * 
	 * @param string $hex color to be modified
	 * @param string $diff amount to change the color
	 * @return string hex color
	 */
	public function hex_color_mod($hex, $diff) {
		$rgb = str_split(trim($hex, '# '), 2);
	 
		foreach ($rgb as &$hex) {
			$dec = hexdec($hex);
			if ($diff >= 0) {
				$dec += $diff;
			}
			else {
				$dec -= abs($diff);			
			}
			$dec = max(0, min(255, $dec));
			$hex = str_pad(dechex($dec), 2, '0', STR_PAD_LEFT);
		}
	 
		return '#'.implode($rgb);
	}

	/**
     * Returns whether or not given color is considered "light"
     * @param string|Boolean $color
     * @return boolean
     * @link https://github.com/mexitek/phpColors
     */
    public function is_color_light( $color = FALSE ) {

        // Get our color
        $color = ($color) ? $color : $this->_hex;
        
        // Calculate straight from rbg
        $r = hexdec($color[0].$color[1]);
        $g = hexdec($color[2].$color[3]);
        $b = hexdec($color[4].$color[5]);
        
        return (( $r*299 + $g*587 + $b*114 )/1000 > 130);
        
    }

} // end class



/**
 * Call to include room form
 */
function aiwp_include( $args ) {

	$aiwp_defaults = array(
		'room' => '',
		'type' => 'public',
		'source' => 'include',
		);

	$args = wp_parse_args( $args, $aiwp_defaults );

	$shortcode = '[appear_in ';

	foreach ( $args as $parameter => $value ) {
		if ( ! empty( $value ) && ! is_null( $value ) ) {
			$shortcode .= $parameter . '="' . $value . '" ';
		}
	}

	$shortcode .= ']';

	Appear_In_WP::add_stylesheets_and_javascript();
	echo do_shortcode( $shortcode );

}

/**
 * Create a random room code
 */
function aiwp_random_room() {

	// predefine the alphabet used
	$aiwp_alphabet = 'qwertyuiopasdfghjklzxcvbnm1234567890';

	// set the length of the string
	$aiwp_stringLength = 30;

	// initialize the room name as an empty string
	$aiwp_randomString = '';

	// select and add 30 random character to the string
	for ( $i=0; $i<$aiwp_stringLength; $i++) {

		$aiwp_character = $aiwp_alphabet[ round( ( rand(0,100)/100 )*(strlen($aiwp_alphabet)-1) ) ];
		$aiwp_randomString .= $aiwp_character;

	} // end for

	// return the result
	return $aiwp_randomString;
}

/**
 * Creates a new room code and saves to db
 */
function aiwp_expire_room() {
	$aiwp_room = aiwp_random_room();
	update_option( 'aiwp_public_room', $aiwp_room );
}
// Hook function to expireroom cron event (found in admin class)
add_action( 'expireroom', 'aiwp_expire_room' );

?>