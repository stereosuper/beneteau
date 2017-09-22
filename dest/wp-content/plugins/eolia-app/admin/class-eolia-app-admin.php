<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.studio-atlantic.com
 * @since      1.0.0
 *
 * @package    Wp_Eolia_App
 * @subpackage Wp_Eolia_App/admin
 */


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Eolia_App
 * @subpackage Wp_Eolia_App/admin
 * @author     Eolia Software / Luc DIDIER <contact@lucdidier.com>
 */
class Wp_Eolia_App_Admin {


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	private $current_options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name     The name of this plugin.
	 * @param      string $version         The version of this plugin.
	 * @param      array  $current_options The saved options of this plugin.
	 */
	public function __construct( $plugin_name, $version, $current_options ) {

		$this->plugin_name     = $plugin_name;
		$this->version         = $version;
		$this->current_options = $current_options;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Eolia_App_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Eolia_App_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_register_style(
			$this->plugin_name . '-admin',
			plugin_dir_url( __DIR__ ) . 'dist/stylesheets/admin.css',
			array(),
			$this->version,
			'all'
		);
		wp_register_style(
			'font-awesome',
			'//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
			'',
			'4.7.0',
			'all'
		);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Eolia_App_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Eolia_App_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_register_script(
			$this->plugin_name . '-admin',
			plugin_dir_url( __DIR__ ) . 'dist/javascripts/admin.js',
			array(),
			$this->version,
			true
		);
		wp_enqueue_media();
	}

	public function add_plugin_admin_menu() {
		add_options_page(
			__( 'Eolia Espace Candidat - Setup' ),
			'Eolia Software',
			'manage_options',
			$this->plugin_name,
			array(
				$this,
				'display_plugin_setup_page',
			)
		);
	}

	public function display_plugin_setup_page() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( $this->plugin_name . '-admin' );
		wp_enqueue_style( $this->plugin_name . '-admin' );
		wp_enqueue_style( 'font-awesome' );
		include_once __DIR__ . '/partials/eolia-app-admin-display.php';
	}


	public function validate( $input ) {
		$options = get_option( $this->plugin_name );

		/* GENERAL PARAMS */
		if ( isset( $input['offers_feed_url'] ) ) {
			$options['offers_feed_url'] = esc_url( trim( $input['offers_feed_url'] ) );
		}
		if ( isset( $input['application_fields_url'] ) ) {
			$options['application_fields_url'] = esc_url( trim( $input['application_fields_url'] ) );
		}
		if ( isset( $input['offer_fields_url'] ) ) {
			$options['offer_fields_url'] = esc_url( trim( $input['offer_fields_url'] ) );
		}
		if ( isset( $input['application_email'] ) && is_email( $input['application_email'] ) ) {
			$options['application_email'] = $input['application_email'];
		}
		if ( isset( $input['thanks_offer'] ) ) {
			$options['thanks_offer'] = esc_url( $input['thanks_offer'] );
		}
		if ( isset( $input['thanks_application'] ) ) {
			$options['thanks_application'] = esc_url( $input['thanks_application'] );
		}

		if ( isset( $input['recaptcha_key'] ) ) {
			$options['recaptcha_key'] = esc_sql( trim( $input['recaptcha_key'] ) );
		}
		if ( isset( $input['recaptcha_secret'] ) ) {
			$options['recaptcha_secret'] = esc_sql( trim( $input['recaptcha_secret'] ) );
		}


		/* SEARCH ENGINE PARAMS */
		if ( isset( $input['keywordsearch'] ) ) {
			$options['keywordsearch'] = ( (int) $input['keywordsearch'] > 0 ) ? 1 : 0;
		}
		if ( isset( $input['geoloc'] ) ) {
			$options['geoloc'] = ( (int) $input['geoloc'] > 0 ) ? 1 : 0;
		}
		if ( isset( $input['gmap_key'] ) ) {
			$options['gmap_key'] = esc_sql( trim( $input['gmap_key'] ) );
		}
		if ( isset( $input['gmap_lat'] ) ) {
			$options['gmap_lat'] = esc_sql( trim( $input['gmap_lat'] ) );
		}
		if ( isset( $input['gmap_lng'] ) ) {
			$options['gmap_lng'] = esc_sql( trim( $input['gmap_lng'] ) );
		}
		if ( isset( $input['gmap_zoom'] ) ) {
			$options['gmap_zoom'] = esc_sql( trim( $input['gmap_zoom'] ) );
		}
		if ( isset( $input['gmap_autozoom'] ) ) {
			$options['gmap_autozoom'] = true;
		} else {
			$options['gmap_autozoom'] = false;
		}
		if ( isset( $input['search_criteria'] ) ) {
			$options['search_criteria'] = $input['search_criteria'];
		}
		if ( isset( $input['marker_img'] ) ) {
			$options['marker_img'] = $input['marker_img'];
		}
		if ( isset( $input['marker_level1_img'] ) ) {
			$options['marker_level1_img'] = $input['marker_level1_img'];
		}
		if ( isset( $input['marker_level2_img'] ) ) {
			$options['marker_level2_img'] = $input['marker_level2_img'];
		}
		if ( isset( $input['marker_level3_img'] ) ) {
			$options['marker_level3_img'] = $input['marker_level3_img'];
		}
		if ( isset( $input['marker_level4_img'] ) ) {
			$options['marker_level4_img'] = $input['marker_level4_img'];
		}
		if ( isset( $input['marker_level5_img'] ) ) {
			$options['marker_level5_img'] = $input['marker_level5_img'];
		}

		/* RESULTS PARAMS */
		if ( isset( $input['result_headers'] ) && json_decode( $input['result_headers'] ) ) {
			$options['result_headers'] = $input['result_headers'];
		}
		if ( isset( $input['res_order'] ) ) {
			$options['res_order'] = ( $input['res_order'] === 'asc' ) ? 'asc' : 'desc';
		}
		if ( isset( $input['res_orderby'] ) ) {
			$options['res_orderby'] = $input['res_orderby'];
		}
		if ( isset( $input['res_main_category'] ) ) {
			if ( array_key_exists(
				     'res_main_category',
				     $options
			     ) && $input['res_main_category'] !== $options['res_main_category'] ) {
				$this->updateCategories( $input['res_main_category'] );
			}
			$options['res_main_category'] = $input['res_main_category'];
		}
		if ( isset( $input['description_field'] ) ) {
			$options['description_field'] = $input['description_field'];
		}
		if ( isset( $input['accordion_limit'] ) ) {
			$options['accordion_limit'] = ( (int) $input['accordion_limit'] > 50 ) ? (int) $input['accordion_limit'] : 50;
		}

		/* OFFERS LAYOUT PARAMS */
		if ( isset( $input['is_share_btn'] ) ) {
			$options['is_share_btn'] = ( (int) $input['is_share_btn'] > 0 ) ? 1 : 0;
		}
		if ( isset( $input['share_btn_pos'] ) && ( 'top' === $input['share_btn_pos'] || 'bottom' === $input['share_btn_pos'] || 'both' === $input['share_btn_pos'] ) ) {
			$options['share_btn_pos'] = $input['share_btn_pos'];
		}
		if ( isset( $input['offer_layout'] ) ) {
			$options['offer_layout'] = $input['offer_layout'];
		}

		/* APPLICATION LAYOUT */
		if ( isset( $input['application_layout'] ) ) {
			$options['application_layout'] = $input['application_layout'];
		}

		/* JOBAPPLY LAYOUT */
		if ( isset( $input['jobapply_layout'] ) ) {
			$options['jobapply_layout'] = $input['jobapply_layout'];
		}

		/* FORM FUNCTIONS PARAMS */
		if ( isset( $input['applyform_columns'] ) ) {
			$val = (int) $input['applyform_columns'];
			if ( $val > 0 && $val < 5 ) {
				$options['applyform_columns'] = $val;
			} else {
				$options['applyform_columns'] = 2;
			}
		}
		if ( isset( $input['maxfilesize'] ) ) {
			$val         = (int) $input['maxfilesize'];
			$max_allowed = (int) ini_get( 'post_max_size' ) * 1000;
			if ( $val > 0 && $val <= $max_allowed ) {
				$options['maxfilesize'] = $val;
			} else {
				$options['maxfilesize'] = $max_allowed;
			}
		}
		if ( isset( $input['is_applygoogle'] ) ) {
			$options['is_applygoogle'] = ( (int) $input['is_applygoogle'] > 0 ) ? 1 : 0;
		}
		if ( isset( $input['is_applydropbox'] ) ) {
			$options['is_applydropbox'] = ( (int) $input['is_applydropbox'] > 0 ) ? 1 : 0;
		}
		if ( isset( $input['is_applyindeed'] ) ) {
			$options['is_applyindeed'] = ( (int) $input['is_applyindeed'] > 0 ) ? 1 : 0;
		}
		if ( isset( $input['is_applyviadeo'] ) ) {
			$options['is_applyviadeo'] = ( (int) $input['is_applyviadeo'] > 0 ) ? 1 : 0;
		}
		if ( isset( $input['is_applylinkedin'] ) ) {
			$options['is_applylinkedin'] = ( (int) $input['is_applylinkedin'] > 0 ) ? 1 : 0;
		}
		if ( isset( $input['is_textkernel'] ) ) {
			$options['is_textkernel'] = ( (int) $input['is_textkernel'] > 0 && class_exists( 'SoapClient' ) ) ? 1 : 0;
		}
		if ( isset( $input['google_api_key'] ) ) {
			$options['google_api_key'] = esc_sql( trim( $input['google_api_key'] ) );
		}
		if ( isset( $input['google_id_client'] ) ) {
			$input['google_id_client']   = str_replace( '.apps.googleusercontent.com', '', $input['google_id_client'] );
			$options['google_id_client'] = esc_sql( trim( $input['google_id_client'] ) );
		}
		if ( isset( $input['dropbox_app_key'] ) ) {
			$options['dropbox_app_key'] = esc_sql( trim( $input['dropbox_app_key'] ) );
		}
		if ( isset( $input['dropbox_secret'] ) ) {
			$options['dropbox_secret'] = esc_sql( trim( $input['dropbox_secret'] ) );
		}
		if ( isset( $input['indeed_token'] ) ) {
			$options['indeed_token'] = esc_sql( trim( $input['indeed_token'] ) );
		}
		if ( isset( $input['indeed_secret'] ) ) {
			$options['indeed_secret'] = esc_sql( trim( $input['indeed_secret'] ) );
		}
		if ( isset( $input['viadeo_id'] ) ) {
			$options['viadeo_id'] = esc_sql( trim( $input['viadeo_id'] ) );
		}
		if ( isset( $input['viadeo_secret'] ) ) {
			$options['viadeo_secret'] = esc_sql( trim( $input['viadeo_secret'] ) );
		}
		if ( isset( $input['linkedin_id'] ) ) {
			$options['linkedin_id'] = esc_sql( trim( $input['linkedin_id'] ) );
		}
		if ( isset( $input['linkedin_secret'] ) ) {
			$options['linkedin_secret'] = esc_sql( trim( $input['linkedin_secret'] ) );
		}
		if ( isset( $input['textkernel_url'] ) ) {
			$options['textkernel_url'] = esc_sql( trim( $input['textkernel_url'] ) );
		}
		if ( isset( $input['textkernel_account'] ) && trim(
			                                              $input['textkernel_account']
		                                              ) !== $options['textkernel_account'] ) {
			$options['textkernel_account'] = base64_encode( esc_sql( trim( $input['textkernel_account'] ) ) );
		}
		if ( isset( $input['textkernel_login'] ) && trim(
			                                            $input['textkernel_login']
		                                            ) !== $options['textkernel_login'] ) {
			$options['textkernel_login'] = base64_encode( esc_sql( trim( $input['textkernel_login'] ) ) );
		}
		if ( isset( $input['textkernel_mdp'] ) && trim( $input['textkernel_mdp'] ) !== $options['textkernel_mdp'] ) {
			$options['textkernel_mdp'] = base64_encode( esc_sql( trim( $input['textkernel_mdp'] ) ) );
		}
		if ( isset( $input['custom_form_param'] ) ) {
			$options['custom_form_param'] = esc_sql( trim( $input['custom_form_param'] ) );
		}

		flush_rewrite_rules();

		return $options;
	}

	private function updateCategories() {
		$terms = get_terms( 'job_category', array( 'fields' => 'ids', 'hide_empty' => false ) );
		foreach ( $terms as $value ) {
			wp_delete_term( $value, 'job_category' );
		}

		$app = \Eolia\EoliaWordpress::get_instance();
		$app->update_categories();
	}

	public function options_update() {
		register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'validate' ) );
	}
}
