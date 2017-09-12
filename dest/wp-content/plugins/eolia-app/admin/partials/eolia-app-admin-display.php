<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.studio-atlantic.com
 * @since      1.0.0
 *
 * @package    Wp_Eolia_App
 * @subpackage Wp_Eolia_App/admin/partials
 */
$options    = get_option( 'eolia-app' );
$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'eolia_general';
$tabs       = array(
	'eolia_general' => [
		'title'       => _x( 'General', 'admin tab title', $this->plugin_name ),
		'long_title'  => _x( 'General settings', 'admin tab long-title', $this->plugin_name ),
		'description' => '',
		'view'        => 'eolia-app-admin-display-general',
	],
);
if ( ! empty( $options['offers_feed_url'] ) && ! empty( $options['offer_fields_url'] ) ) {
	$tabs['eolia_search']   = [
		'title'       => _x( 'Search engine', 'admin tab title', $this->plugin_name ),
		'long_title'  => _x( 'Search engine setup', 'admin tab long-title', $this->plugin_name ),
		'description' => '',
		'view'        => 'eolia-app-admin-display-search',
	];
	$tabs['eolia_results']  = [
		'title'       => _x( 'Search engine results page', 'admin tab title', $this->plugin_name ),
		'long_title'  => _x( 'Displaying search engine results', 'admin tab long-title', $this->plugin_name ),
		'description' => '',
		'view'        => 'eolia-app-admin-display-results',
	];
	$tabs['eolia_joboffer'] = [
		'title'       => _x( 'Job Offers', 'admin tab title', $this->plugin_name ),
		'long_title'  => _x( 'Show all job offers page setup', 'admin tab long-title', $this->plugin_name ),
		'description' => '',
		'view'        => 'eolia-app-admin-display-joboffer',
	];
}
if ( ! empty( $options['application_fields_url'] ) ) {
	$tabs['eolia_apply_job'] = [
		'title'       => _x( 'Offer questionnaire', 'admin tab title', $this->plugin_name ),
		'long_title'  => _x( 'Offer questionnaire form setup', 'admin tab long-title', $this->plugin_name ),
		'description' => '',
		'view'        => 'eolia-app-admin-display-apply-job',
	];

	$tabs['eolia_apply_free'] = [
		'title'       => _x( 'Unsolicited application questionnaire', 'admin tab title', $this->plugin_name ),
		'long_title'  => _x( 'Unsolicited application questionnaire setup', 'admin tab long-title', $this->plugin_name ),
		'description' => '',
		'view'        => 'eolia-app-admin-display-apply-free',
	];

	$tabs['eolia_formfunc'] = [
		'title'       => _x( 'Advanced setup', 'admin tab title', $this->plugin_name ),
		'long_title'  => _x( 'Advanced setup', 'admin tab long-title', $this->plugin_name ),
		'description' => '',
		'view'        => 'eolia-app-admin-display-formfunc',
	];
}

?>
<div class="wrap eolia_admin_page">

	<h1><?php _ex( 'Eolia Candidate Front-End', 'admin title', $this->plugin_name ); ?></h1>

	<h2 class="nav-tab-wrapper">
		<?php foreach ( $tabs as $tid => $t ) { ?>
			<a href="?page=<?php echo $this->plugin_name; ?>&tab=<?php echo $tid; ?>"
			   class="nav-tab <?php echo $active_tab === $tid ? 'nav-tab-active' : ''; ?>"><?php echo $t['title']; ?></a>
		<?php } ?>
	</h2>

	<div class="tab-content">
		<h2><?php echo $tabs[ $active_tab ]['long_title']; ?></h2>
		<p><?php echo $tabs[ $active_tab ]['description']; ?></p>

		<?php
		if ( ! empty( $tabs[ $active_tab ]['view'] ) && is_file( dirname( __FILE__ ) . '/' . $tabs[ $active_tab ]['view'] . '.php' ) ) {
			include( $tabs[ $active_tab ]['view'] . '.php' );
		} else {
			echo '<p>' . __( 'Coming soon...', $this->plugin_name ) . '</p>';
		}
		?>
	</div>

</div>