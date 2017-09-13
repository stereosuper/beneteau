<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Eolia_App
 * @subpackage Wp_Eolia_App/includes
 * @author     Eolia Software / Luc DIDIER <contact@lucdidier.com>
 */
class Wp_Eolia_App_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 *
	 * @param string $wp
	 * @param string $php
	 */
	public static function activate() {
		return self::checkRequirement();
	}

	public static function checkRequirement( $wp = '4.5', $php = '5.6' ) {
		global $wp_version;
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( version_compare( PHP_VERSION, $php, '<' ) ) {
			$flag           = 'PHP';
			$actual_version = PHP_VERSION;
		} elseif ( version_compare( $wp_version, $wp, '<' ) ) {
			$flag           = 'WordPress';
			$actual_version = $wp_version;
		} else {
			return true;
		}
		$version = 'PHP' === $flag ? $php : $wp;
		deactivate_plugins( 'eolia-app/eolia-app.php' );
		add_action(
			'admin_notices',
			function () use ( $flag, $version, $actual_version ) {
				echo '<div class="notice notice-error is-dismissible">
							<button type="button" class="notice-dismiss">
								<span class="screen-reader-text">' . __( 'Dismiss this notice.', 'eolia-app' ) . '</span>
							</button><p>The <strong>Eolia Espace Candidat</strong> plugin requires ' . $flag . '  version ' . $version . ' or greater.</p><p>Your actual ' . $flag . ' version is : ' . $actual_version . '</p></div>';
			}
		);

		wp_die( '<p>The <strong>Eolia Espace Candidat</strong> plugin requires ' . $flag . '  version ' . $version . ' or greater.</p><p>Your actual ' . $flag . ' version is : ' . $actual_version . '</p>',
			'Plugin Activation Error',
			array( 'response' => 200, 'back_link' => true ) );

		return false;
	}

}
