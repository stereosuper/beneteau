<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.eolia-consulting.com
 * @since             1.0.0
 * @package           Wp_Eolia_App
 *
 * @wordpress-plugin
 * Plugin Name:       Espace Candidats Wordpress par Eolia Software
 * Plugin URI:        http://www.eolia-consulting.com
 * Description:       Fonctionnalités de l'Espace Candidat par Eolia Software
 * Version:           2.0.80
 * Author:            Ronan Pozzi (Eolia Software)
 * Author URI:        http://www.eolia-consulting.com
 * License:           Private
 * Text Domain:       eolia-app
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-eolia-app-activator.php
 */
function activate_wp_eolia_app() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-eolia-app-activator.php';
	Wp_Eolia_App_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-eolia-app-deactivator.php
 */
function deactivate_wp_eolia_app() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-eolia-app-deactivator.php';
	Wp_Eolia_App_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_eolia_app' );
register_deactivation_hook( __FILE__, 'deactivate_wp_eolia_app' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-eolia-app.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_eolia_app() {
	if ( is_admin() ) {
		require __DIR__ . '/includes/class-eolia-app-updater.php';
		Wp_Eolia_App_Updater::check_update();
	}

	require_once __DIR__ . '/App/EoliaWordpress.php';
	\Eolia\EoliaWordpress::get_instance();
	$plugin = new Wp_Eolia_App();
	$plugin->run();
}

add_action( 'rest_api_init',
	function () {
		require_once __DIR__ . '/App/Controllers/ApiController.php';
	} );

run_wp_eolia_app();
