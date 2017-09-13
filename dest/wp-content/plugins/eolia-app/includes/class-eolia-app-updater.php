<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}
/**
 * Created by PhpStorm.
 * User: ronan
 * Date: 24/02/2017
 * Time: 10:11
 */
class Wp_Eolia_App_Updater {
	/**
	 * The dist plugin json URL
	 */
	const PLUGIN_JSON = 'https://s3.eu-central-1.amazonaws.com/eolia-wordpress/updates/plugins/eolia-app/plugin.json';

	/**
	 * Check for update from S3
	 */
	public static function check_update() {
		if ( class_exists( Puc_v4_Factory::class ) ) {
			return Puc_v4_Factory::buildUpdateChecker(
				self::PLUGIN_JSON,
				dirname( __DIR__ ) . '/eolia-app.php',
				'eolia-app'
			);
		}
	}
}