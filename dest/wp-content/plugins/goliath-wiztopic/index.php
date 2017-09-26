<?php
/**
 * Plugin Name: Beneteau - Wiztopic api integration
 * Description: Intègre au site Beneteau les apis wiztopic
 * Version: 1.0
 * Author: Alain Diart
 */

defined( 'ABSPATH' ) or die( 'Boom!' );

define('GGWZTP_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ));

const GWZTP_BASE_API_URI = 'https://www.wiztopic.com'; // No trailing slash
const GWZTP_AUTHORIZE_URI = 'https://www.wiztopic.com/oauth/v2/authorize'; // en vrai sans doute pas utilisé mais requis par la classe oauth
const GWZTP_ACCESSTOKEN_URI = 'https://www.wiztopic.com/oauth/v2/token';
const GWZTP_OWNERDETAILS_URI = 'https://www.wiztopic.com/oauth/v2/resource'; // en vrai sans doute pas utilisé mais requis par la classe oauth

require_once GGWZTP_PLUGIN_DIR_PATH.'/vendor/autoload.php';
require_once GGWZTP_PLUGIN_DIR_PATH.'/inc/importer-base.class.php';
require_once GGWZTP_PLUGIN_DIR_PATH.'/sync.php';
require_once GGWZTP_PLUGIN_DIR_PATH.'/options.php';


function gwztp_cron_interval( $schedules ) {
    $schedules['gwztp_five_minutes'] = array(
        'interval' => 5 * 60,
        'display'  => esc_html__( 'Every Five Minutes' ),
    );
    return $schedules;
}
add_filter('cron_schedules', 'gwztp_cron_interval');

function gwztp_cron_exec() {
    $sync = new WiztopicSync();
    $sync->start();
}
add_action( 'gwztp_cron_hook', 'gwztp_cron_exec' );

if ( ! wp_next_scheduled( 'gwztp_cron_hook' ) ) {
    wp_schedule_event( time(), 'gwztp_five_minutes', 'gwztp_cron_hook' );
}