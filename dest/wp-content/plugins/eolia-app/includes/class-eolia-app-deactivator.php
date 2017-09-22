<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Eolia_App
 * @subpackage Wp_Eolia_App/includes
 * @author     Eolia Software / Luc DIDIER <contact@lucdidier.com>
 */
class Wp_Eolia_App_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wp_rewrite;
		if ( null !== $wp_rewrite ) {
			flush_rewrite_rules();
		}
	}

}
