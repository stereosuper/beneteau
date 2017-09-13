<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Eolia_App
 * @subpackage Wp_Eolia_App/includes
 * @author     Eolia Software / Luc DIDIER <contact@lucdidier.com>
 */
class Wp_Eolia_App_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'eolia-app', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
	}
}
