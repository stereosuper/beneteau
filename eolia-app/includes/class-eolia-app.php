<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Eolia_App
 * @subpackage Wp_Eolia_App/includes
 * @author     Eolia Software / Luc DIDIER <contact@lucdidier.com>
 */
class Wp_Eolia_App {


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Eolia_App_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'eolia-app';
		$this->version     = '1.3.4';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Eolia_App_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Eolia_App_i18n. Defines internationalization functionality.
	 * - Wp_Eolia_App_Admin. Defines all hooks for the admin area.
	 * - Wp_Eolia_App_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-eolia-app-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-eolia-app-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-eolia-app-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-eolia-app-public.php';

		$this->loader = new Wp_Eolia_App_Loader();

		/**
		 * The class responsible for defining all eolia widgets
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'eolia-app-widget.php';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Eolia_App_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Eolia_App_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Eolia_App_Admin(
			$this->get_plugin_name(), $this->get_version(),
			$this->get_eolia_options()
		);

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'options_update' );

		if ( isset( $_REQUEST['action'] ) && in_array(
				$_REQUEST['action'], array( 'update_jobs', 'import_datas' ), false
			) ) {
			if ( is_admin() ) {
				$this->loader->add_action( 'admin_init', \Eolia\EoliaWordpress::get_instance(), 'update_jobs' );
			} else {
				$this->loader->add_action( 'init', \Eolia\EoliaWordpress::get_instance(), 'update_jobs' );
			}
		}

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function get_eolia_options() {
		$default_options = array(
			'offers_feed_url'        => '',
			'application_fields_url' => '',
			'offer_fields_url'       => '',
			'offer_fields'           => '',
			'application_fields'     => '',
			'application_email'      => get_option( 'admin_email' ),
			'thanks_offer'           => false,
			'thanks_application'     => false,
			'keywordsearch'          => false,
			'geoloc'                 => false,
			'gmap_key'               => '',
			'gmap_lat'               => '46.116667',
			'gmap_lng'               => '3.083333',
			'gmap_zoom'              => '5',
			'gmap_autozoom'          => true,
			'search_criteria'        => '',
			'result_headers'         => '',
			'res_order'              => 'liste4',
			'res_orderby'            => 'asc',
			'res_main_category'      => 'bu',
			'description_field'      => false,
			'is_share_btn'           => false,
			'share_btn_pos'          => 'bottom',
			'offer_layout'           => '',
			'application_layout'     => '',
			'jobapply_layout'        => '',
			'is_applygoogle'         => false,
			'is_applydropbox'        => false,
			'is_applyindeed'         => false,
			'is_applyviadeo'         => false,
			'is_applylinkedin'       => false,
			'is_textkernel'          => false,
			'google_api_key'         => '',
			'google_id_client'       => '',
			'dropbox_app_key'        => '',
			'dropbox_secret'         => '',
			'indeed_token'           => '',
			'indeed_secret'          => '',
			'viadeo_id'              => '',
			'viadeo_secret'          => '',
			'linkedin_id'            => '',
			'linkedin_secret'        => '',
			'textkernel_url'         => 'http://home.textkernel.nl/sourcebox/soap/documentProcessor?wsdl',
			'textkernel_account'     => 'ZW9saWE=',
			'textkernel_login'       => 'ZW9saWE=',
			'textkernel_mdp'         => 'dGtzYjE1NjE',
			'custom_form_param'      => 'form_id',
			'marker_img'             => false,
			'marker_level1_img'      => false,
			'marker_level2_img'      => false,
			'marker_level3_img'      => false,
			'marker_level4_img'      => false,
			'marker_level5_img'      => false,
			'maxfilesize'            => (int) ini_get( 'post_max_size' ) * 1000,
			'recaptcha_key'          => '',
			'recaptcha_secret'       => '',
			'accordion_limit'        => 50,
		);
		if ( ! $saved_options = get_option( $this->plugin_name ) ) {
			add_option( 'eolia-app', $default_options );
		}

		$saved_options = (array) get_option( $this->plugin_name );

		return array_merge( $default_options, $saved_options );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Eolia_App_Public(
			$this->get_plugin_name(), $this->get_version(),
			$this->get_eolia_options()
		);

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles', 9 );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', 9 );
		$this->loader->add_action( 'init', $plugin_public, 'declare_shortcodes' );
		$this->loader->add_action( 'init', $plugin_public, 'redirect_form_submission' );
		$this->loader->add_action( 'init', $plugin_public, 'set_origin' );

		$this->loader->add_action( 'wp_ajax_parse_cv', $plugin_public, 'parse_cv_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_parse_cv', $plugin_public, 'parse_cv_callback' );

		$this->loader->add_action( 'wp_ajax_get_linkedin', $plugin_public, 'get_linkedin_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_linkedin', $plugin_public, 'get_linkedin_callback' );

		$this->loader->add_action( 'wp_ajax_get_viadeo', $plugin_public, 'get_viadeo_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_viadeo', $plugin_public, 'get_viadeo_callback' );

		$this->loader->add_action( 'wp_ajax_get_offers', $plugin_public, 'ajax_get_results_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_offers', $plugin_public, 'ajax_get_results_callback' );

		if ( isset( $_REQUEST['action'] ) && 'indeed' === $_REQUEST['action'] ) {
			$this->loader->add_action( 'init', $plugin_public, 'get_indeed_datas' );
		}

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Eolia_App_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

}
