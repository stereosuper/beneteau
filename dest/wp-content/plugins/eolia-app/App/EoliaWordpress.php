<?php
/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author     Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date       2017
 * @copyright  Eolia Software (http://www.eolia-consulting.com)
 * @licence    GNU
 *
 * @file       EoliaApp.php
 * @package    eolia-app
 * @version    1.0.0
 */

/**
 * Created by PhpStorm.
 * User: eolia
 * Date: 13/04/2017
 * Time: 23:53
 */

namespace Eolia {

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // don't access directly
	}


	use DateTime;
	use Eolia\Controllers\FieldController;
	use Eolia\Controllers\JobController;
	use Eolia\Controllers\StreamController;
	use Eolia\Interfaces\FieldInterface;
	use stdClass;
	use WP_Query;

	/**
	 * Class App
	 *
	 * @package Eolia
	 */
	class EoliaWordpress {


		/**
		 * @var array
		 */
		public static $file_extensions = array(
			'odt',
			'doc',
			'docx',
			'pages',
			'txt',
			'pdf',
		);
		/**
		 * @var
		 */
		private static $_instance;
		/**
		 * @var mixed|void
		 */
		private $options;
		/**
		 * @var
		 */
		private $jobs;
		/**
		 * @var
		 */
		private $fields = array();
		/**
		 * @var string
		 */
		private $lang;
		/**
		 * @var string
		 */
		private $plugin_url;
		/**
		 * @var string
		 */
		private $plugin_path;
		/**
		 * @var string
		 */
		private $plugin_name;
		/**
		 * @var string
		 */
		private $version;

		/**
		 * App constructor.
		 */
		private function __construct() {
			$this->version     = '2.0.0';
			$this->plugin_name = basename( plugin_basename( __DIR__ ) );
			$this->plugin_path = plugin_dir_path( __DIR__ );
			$this->plugin_url  = plugin_dir_url( __DIR__ );
			$this->options     = get_option( 'eolia-app' );
			$this->lang        = get_locale();

			add_action(
				'init',
				function () {
					if ( ! is_admin() ) {
						$this->lang = function_exists( 'pll_current_language' ) ? pll_current_language(
							'locale'
						) : get_locale();
					}
				}
			);

			add_action( 'init', array( $this, 'job_post_type' ) );
			add_action( 'init', array( $this, 'job_post_taxonomy' ) );

			add_action( 'generate_rewrite_rules', array( $this, 'generate_taxonomy_rewrite_rules' ) );

			add_action( 'pre_get_posts', array( $this, 'get_job_query' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );

			add_filter( 'single_template', array( $this, 'template_include' ) );
			add_filter( 'job_template', array( $this, 'template_include' ) );
			add_filter( 'archive_template', array( $this, 'template_include' ) );

			add_filter( 'request', array( $this, 'fix_job_query_var' ) );

			add_action( 'rss2_ns', array( $this, 'add_eolia_rss_namespace' ) );
			add_action( 'rss2_item', array( $this, 'add_eolia_rss_fields' ) );

			add_filter(
				'the_content',
				function ( $content ) {
					$apply_suffix = _x( 'apply', 'slug', 'eolia-app' );

					if ( is_singular( 'job' ) ) {
						$job = eolia_get_job();
						if ( get_query_var( $apply_suffix ) ) {
							$content = $job->render_form();
						} else {
							$content = $job->render_view();
						}
					}

					return $content;
				}
			);

			add_filter( 'post_type_link', array( $this, 'job_category_permalink' ), 10, 2 );
			add_filter(
				'body_class',
				function ( $classes ) {
					global $post;

					if ( is_apply() ) {
						if ( get_query_var( 'job' ) ) {
							$job       = eolia_get_job();
							$classes[] = 'single-job--apply';
							$classes[] = 'apply';
							$classes[] = 'apply--' . $job->get_id();
						} else {
							$classes[] = 'apply';
							$classes[] = 'apply--unsolicited';
						}
					} elseif ( is_page() ) {
						if ( has_shortcode( $post->post_content, 'eolia_search' ) ) {
							$classes[] = 'job-search';
						}
					}


					return $classes;
				}
			);

			add_filter( 'wp_nav_menu_objects', array( $this, 'set_current_menu_title_as_global' ) );
			add_filter( 'post_type_archive_title', array( $this, 'override_archive_page_title' ) );
			add_filter( 'get_the_archive_title', array( $this, 'override_archive_page_title' ) );
		}

		/**
		 * @return \Eolia\EoliaWordpress
		 */
		public static function get_instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Get the current menu item and set the title as Globals
		 *
		 * @param $sorted_menu_items
		 *
		 * @see \Eolia\EoliaWordpress::override_archive_page_title()
		 *
		 * @return mixed
		 */
		public function set_current_menu_title_as_global( $sorted_menu_items ) {
			foreach ( $sorted_menu_items as $menu_item ) {
				if ( $menu_item->current && $menu_item->type === 'post_type_archive' ) {
					$GLOBALS['eolia_archive_title'] = $menu_item->title;
					break;
				}
			}

			return $sorted_menu_items;
		}

		/**
		 * Override the archive default title by the current menu item title
		 *
		 * @param $title
		 *
		 * @return mixed
		 */
		public function override_archive_page_title( $title ) {
			if ( isset( $GLOBALS['eolia_archive_title'] ) ) {
				return $GLOBALS['eolia_archive_title'];
			}

			return $title;
		}

		/**
		 * @return array|null
		 */
		public function archive_query() {
			if ( isset( $_REQUEST['eolia_search'] ) ) {
				return \Wp_Eolia_App_Public::query_search_results( $_REQUEST );
			}

			$args = array(
				'post_type'      => 'job',
				'paged'          => get_query_var( 'paged' ) ?: 1,
				'posts_per_page' => - 1,
				'orderby'        => 'meta_value',
				'order'          => $this->options['res_order'],
			);

			if ( isset( $_REQUEST['ids'] ) ) {
				$args['meta_query'][] =
					array(
						'key'     => 'job_id',
						'value'   => $_REQUEST['ids'],
						'compare' => 'IN',
					);
			}

			if ( $category = get_query_var( 'job_category' ) ) {
				$args['taxonomy'] = 'job_category';
				$args['term']     = get_query_var( 'job_category' );
			}

			$query = new WP_Query( $args );
			if ( ! $query->posts || is_wp_error( $query ) ) {
				return null;
			}
			foreach ( $query->posts as $post ) {
				$category                          = get_post_meta(
					$post->ID,
					$this->options['res_main_category'],
					true
				);
				$results[ $category ][ $post->ID ] = $post;
			}
			ksort( $results );
			wp_reset_query();

			return $results;
		}

		/**
		 *
		 */
		public function load_assets() {
			wp_enqueue_style(
				$this->plugin_name,
				plugin_dir_url( __DIR__ ) . 'dist/stylesheets/public.css',
				array(),
				filemtime( plugin_dir_path( __DIR__ ) . 'dist/stylesheets/public.css' )
			);

			wp_register_style(
				'font-awesome',
				'//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
				'',
				'4.7.0',
				'all'
			);

			// Googlemap Geoloc(if activated in backoffice).
			wp_register_script(
				'googlemap',
				'//maps.googleapis.com/maps/api/js?key=' . $this->options['gmap_key'] . '&callback=initMap',
				array(),
				'20160609',
				true
			);
			wp_register_script(
				'google-api',
				'//www.google.com/jsapi?key=' . $this->options['google_api_key'],
				array( $this->plugin_name ),
				'20160609',
				true
			);
			wp_register_script(
				'google-client-api',
				'//apis.google.com/js/client.js?onload=initPicker',
				array(),
				'20160609',
				true
			);

			// Rrgister our plugin script.
			wp_enqueue_script(
				$this->plugin_name,
				plugin_dir_url( __DIR__ ) . 'dist/javascripts/public.js',
				array(),
				filemtime( plugin_dir_path( __DIR__ ) . 'dist/javascripts/public.js' ),
				true
			);

			// Load custom variables.
			wp_localize_script(
				$this->plugin_name,
				'script_infos',
				$this->get_javascript_vars()
			);
		}

		/**
		 * Create Javascript variable for using in other JS files.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @return array
		 */
		public function get_javascript_vars() {
			$variables = array(
				'ajax_url'                => admin_url( 'admin-ajax.php' ),
				'locale'                  => $this->lang,
				'lang'                    => $this->lang,
				'maxfilesize'             => $this->options['maxfilesize'],
				'accordion_limit'         => $this->options['accordion_limit'],
				'allowed_file_extensions' => self::$file_extensions,
				'i18n'                    => array(
					'wrong_file_extensions' => sprintf(
						_x(
							'Wrong file extensions (allowed extensions : %s)',
							'alert',
							'eolia-app'
						),
						implode( ', ', self::$file_extensions )
					),
					'recup_fichier'         => _x(
						'Uploading file, please wait...',
						'loadscreen',
						'eolia-app'
					),
					'max_filesize_error'    => sprintf(
						_x( 'File size exceed %s Mo !', 'alert', 'eolia-app' ),
						$this->options['maxfilesize']
					),
					'recup_indeed'          => sprintf(
						_x(
							'Fetching %s profile, please wait...',
							'loadscreen',
							'eolia-app'
						),
						'Indeed'
					),
					'recup_linkedin'        => sprintf(
						_x(
							'Fetching %s profile, please wait...',
							'loadscreen',
							'eolia-app'
						),
						'LinkedIn'
					),
					'recup_viadeo'          => sprintf(
						_x(
							'Fetching %s profile, please wait...',
							'loadscreen',
							'eolia-app'
						),
						'Viadeo'
					),
					'fichier_viadeo'        => sprintf(
						_x(
							'My %s resume',
							'file-label',
							'eolia-app'
						),
						'Viadeo'
					),
					'fichier_indeed'        => sprintf( _x( 'My %s resume', 'file-label', 'eolia-app' ), 'Indeed' ),
				),
			);
			if ( 1 === $this->options['geoloc'] ) {
				$marker_styles = array();
				for ( $i = 1; $i < 6; $i ++ ) {
					$img = wp_get_attachment_metadata( $this->options[ 'marker_level' . $i . '_img' ] );
					if ( $img ) {
						$style           = new stdClass();
						$style->url      = wp_get_attachment_url( $this->options[ 'marker_level' . $i . '_img' ] );
						$style->height   = $img['height'];
						$style->width    = $img['width'];
						$style->anchor   = array( 0, 0 );
						$marker_styles[] = $style;
					}
				}
				$variables['gmap'] = array(
					'key'           => $this->options['gmap_key'],
					'lat'           => $this->options['gmap_lat'],
					'lng'           => $this->options['gmap_lng'],
					'zoom'          => $this->options['gmap_zoom'],
					'autozoom'      => $this->options['gmap_autozoom'],
					'marker_styles' => $marker_styles,
					'marker_img'    => wp_get_attachment_url( $this->options['marker_img'] ),
				);
			}
			if ( 1 === $this->options['is_applygoogle'] ) {
				$variables['google_api_key']   = $this->options['google_api_key'];
				$variables['google_id_client'] = $this->options['google_id_client'];
			}

			return $variables;
		}

		/**
		 * @param WP_Query $query The Wordpress Query.
		 *
		 * @return \WP_Query
		 */
		public function get_job_query( $query ) {
			if ( ! $query->is_main_query() || ! get_query_var( 'job' ) ) {
				return $query;
			}

			// For feed override
			if ( get_query_var( 'job' ) === 'feed' ) {
				$query->is_single   = false;
				$query->is_singular = false;
				$query->is_tax      = true;
				$query->is_feed     = true;
				$query->is_category( get_query_var( 'job_category' ) );

				$query->set( 'name', '' );
				$query->set( 'job', '' );
				$query->set( 'post_type', '' );
				$query->set( 'feed', 'rss2' );

				$args = array(
					array(
						'taxonomy' => 'job_category',
						'terms'    => array(
							get_query_var( 'job_category' ),
						),
						'field'    => is_numeric( get_query_var( 'job_category' ) ) ? 'term_id' : 'slug',
					),
				);

				$query->tax_query = new \WP_Tax_Query( $args );

				return $query;
			}

			if ( ! is_numeric( get_query_var( 'job' ) ) ) {
				return $query;
			}

			$query->set( 'meta_key', 'job_id' );
			$query->set( 'meta_value', (int) get_query_var( 'job' ) );
			$query->set( 'name', '' );
			$query->set( 'job', '' );

			return $query;
		}

		/**
		 * @param string   $permalink The permalink.
		 * @param \WP_Post $post      The post ID.
		 *
		 * @return mixed
		 */
		public function job_category_permalink( $permalink, $post ) {
			if ( 'job' !== $post->post_type || ! is_object( $post ) ) {
				return $permalink;
			}
			if ( $category = get_the_terms( $post->ID, 'job_category' ) ) {
				$permalink = str_replace( '%job_category%', current( $category )->slug, $permalink );
			} else {
				$permalink = str_replace( '%job_category%/', '', $permalink );
			}

			return $permalink;
		}

		public function generate_taxonomy_rewrite_rules( $wp_rewrite ) {
			$rules      = array();
			$post_types = get_post_types( array( 'name' => 'job', 'public' => true, '_builtin' => false ), 'objects' );
			$taxonomies = get_taxonomies(
				array( 'name' => 'job_category', 'public' => true, '_builtin' => false ),
				'objects'
			);

			foreach ( $post_types as $post_type ) {
				$post_type_name = $post_type->name; // 'job'
				$post_type_slug = $post_type->rewrite['slug']; // 'job'

				foreach ( $taxonomies as $taxonomy ) {
					if ( $taxonomy->object_type[0] == $post_type_name ) {
						$terms = get_categories(
							array(
								'type'       => $post_type_name,
								'taxonomy'   => $taxonomy->name,
								'hide_empty' => 0,
							)
						);
						foreach ( $terms as $term ) {
							$rules[ $post_type_slug . '/' . $term->slug . '/?$' ] = 'index.php?' . $term->taxonomy . '=' . $term->slug;
						}
					}
				}
			}
			$wp_rewrite->rules = array_merge( $rules, $wp_rewrite->rules );
		}

		/**
		 * @param array $vars The allowed post vars.
		 *
		 * @return mixed
		 */
		public function fix_job_query_var( $vars ) {
			$apply_suffix = _x( 'apply', 'slug', 'eolia-app' );

			if ( isset( $vars[ $apply_suffix ] ) ) {
				$vars[ $apply_suffix ] = true;
			}

			return $vars;
		}

		/**
		 * @param string $template The template URL.
		 *
		 * @return string
		 * @todo Add search engine/results template
		 */
		public function template_include( $template ) {
			$job_templates     = array(
				'single-job.php',
			);
			$apply_templates   = array(
				'single-apply.php',
			);
			$archive_templates = array(
				'archive-job.php',
			);

			$apply_suffix = _x( 'apply', 'slug', 'eolia-app' );

			if ( is_singular() ) {
				if ( get_query_var( $apply_suffix ) ) {
					$template = locate_template( $apply_templates ) ?: plugin_dir_path(
						                                                   __DIR__
					                                                   ) . '/public/partials/single-apply.php';
				} elseif ( 'job' === get_query_var( 'post_type' ) ) {
					$template = locate_template( $job_templates ) ?: plugin_dir_path(
						                                                 __DIR__
					                                                 ) . '/public/partials/single-job.php';
				}
			} elseif ( is_archive() && ( 'job' === get_query_var( 'post_type' ) || get_query_var( 'job_category' ) ) ) {
				$template = locate_template( $archive_templates ) ?: plugin_dir_path(
					                                                     __DIR__
				                                                     ) . '/public/partials/archive-job.php';
			}

			return $template;
		}

		/**
		 *
		 */
		public function job_post_type() {

			$labels = array(
				'name'                  => _x( 'Jobs', 'Post Type General Name', 'eolia-app' ),
				'singular_name'         => _x( 'Job', 'Post Type Singular Name', 'eolia-app' ),
				'menu_name'             => __( 'Jobs', 'eolia-app' ),
				'name_admin_bar'        => __( 'Job', 'eolia-app' ),
				'archives'              => __( 'Job Archives', 'eolia-app' ),
				'attributes'            => __( 'Job Attributes', 'eolia-app' ),
				'parent_item_colon'     => __( 'Parent Job:', 'eolia-app' ),
				'all_items'             => __( 'All Jobs', 'eolia-app' ),
				'add_new_item'          => __( 'Add New Job', 'eolia-app' ),
				'add_new'               => __( 'Add New', 'eolia-app' ),
				'new_item'              => __( 'New Job', 'eolia-app' ),
				'edit_item'             => __( 'Edit Job', 'eolia-app' ),
				'update_item'           => __( 'Update Job', 'eolia-app' ),
				'view_item'             => __( 'View Job', 'eolia-app' ),
				'view_items'            => __( 'View Jobs', 'eolia-app' ),
				'search_items'          => __( 'Search Job', 'eolia-app' ),
				'not_found'             => __( 'Not found', 'eolia-app' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'eolia-app' ),
				'featured_image'        => __( 'Featured Image', 'eolia-app' ),
				'set_featured_image'    => __( 'Set featured image', 'eolia-app' ),
				'remove_featured_image' => __( 'Remove featured image', 'eolia-app' ),
				'use_featured_image'    => __( 'Use as featured image', 'eolia-app' ),
				'insert_into_item'      => __( 'INSERT INTO Job', 'eolia-app' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Job', 'eolia-app' ),
				'items_list'            => __( 'Jobs list', 'eolia-app' ),
				'items_list_navigation' => __( 'Jobs list navigation', 'eolia-app' ),
				'filter_items_list'     => __( 'Filter Jobs list', 'eolia-app' ),
			);
			$args   = array(
				'label'                 => __( 'Job', 'eolia-app' ),
				'labels'                => $labels,
				'supports'              => array( 'title', 'thumbnail', 'custom-fields', 'content' ),
				'hierarchical'          => false,
				'public'                => true,
				'query_var'             => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 5,
				'menu_icon'             => 'dashicons-universal-access',
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => 'job',
				'exclude_from_search'   => false,
				'publicly_queryable'    => true,
				'capability_type'       => 'post',
				'taxonomies'            => array( 'job' ),
				'show_in_rest'          => true,
				'rest_base'             => 'job',
				'rest_controller_class' => 'Eolia\ApiController',
				'rewrite'               => array(
					'slug'       => 'job/%job_category%',
					'with_front' => false,
				),
			);

			register_post_type( 'job', $args );

			$apply_suffix = _x( 'apply', 'slug', 'eolia-app' );

			add_rewrite_endpoint( $apply_suffix, EP_PERMALINK );
		}

		/**
		 *
		 */
		public function job_post_taxonomy() {

			$labels = array(
				'name'                       => _x( 'Job Categories', 'Taxonomy General Name', 'eolia-app' ),
				'singular_name'              => _x( 'Job Category', 'Taxonomy Singular Name', 'eolia-app' ),
				'menu_name'                  => __( 'Job Category', 'eolia-app' ),
				'all_items'                  => __( 'All Job Categories', 'eolia-app' ),
				'parent_item'                => __( 'Parent Category', 'eolia-app' ),
				'parent_item_colon'          => __( 'Parent Category:', 'eolia-app' ),
				'new_item_name'              => __( 'New Category Name', 'eolia-app' ),
				'add_new_item'               => __( 'Add New Category', 'eolia-app' ),
				'edit_item'                  => __( 'Edit Category', 'eolia-app' ),
				'update_item'                => __( 'Update Category', 'eolia-app' ),
				'view_item'                  => __( 'View Category', 'eolia-app' ),
				'separate_items_with_commas' => __( 'Separate Categories with commas', 'eolia-app' ),
				'add_or_remove_items'        => __( 'Add or remove Categories', 'eolia-app' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'eolia-app' ),
				'popular_items'              => __( 'Popular Category', 'eolia-app' ),
				'search_items'               => __( 'Search Categories', 'eolia-app' ),
				'not_found'                  => __( 'Not Found', 'eolia-app' ),
				'no_terms'                   => __( 'No Job Categories', 'eolia-app' ),
				'items_list'                 => __( 'Job Categories list', 'eolia-app' ),
				'items_list_navigation'      => __( 'Job Categories list navigation', 'eolia-app' ),
			);

			$args = array(
				'labels'            => $labels,
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud'     => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'job' ),
			);
			register_taxonomy( 'job_category', array( 'job' ), $args );

		}

		/**
		 *
		 * @throws \ErrorException
		 */
		public function update_jobs() {
			// Force streams update
			$stream = StreamController::get_instance();
			$stream->get_stream( 'application_fields', true );
			$stream->get_stream( 'offer_fields', true );

			if ( ! $jobs = $this->get_jobs( true ) ) {
				if ( ! is_admin() ) {
					wp_send_json_error( 'no jobs found' );
				}

				return add_action(
					'admin_notices',
					function () {
						echo '<div class="notice notice-error is-dismissible">
							<button type="button" class="notice-dismiss">
								<span class="screen-reader-text">' . __( 'Dismiss this notice.', 'eolia-app' ) . '</span>
							</button>
							<p>' . sprintf(
								_x(
									'No offers found in your language (%s)',
									'admin notice',
									$this->plugin_name
								),
								$this->lang
							) . '</p></div>';
					}
				);
			}

			$posts = $posts_localized = array();

			$args = array(
				'post_type' => 'job',
				'showposts' => - 1,
			);

			$query = new WP_Query( $args );
			foreach ( $query->posts as $post ) {
				$lang                                                         = function_exists(
					'pll_get_post_language'
				) ? pll_get_post_language(
					$post->ID,
					'locale'
				) : get_post_meta( $post->ID, 'lang', true );
				$posts[ $lang ][ get_post_meta( $post->ID, 'job_id', true ) ] = $post;
			}
			wp_reset_query();

			$terms = array();

			foreach ( $jobs as $lang => $values ) {
				if ( ! isset( $posts[ $lang ] ) ) {
					$posts[ $lang ] = array();
				}

				if ( $deleted = array_diff_key( (array) $posts[ $lang ], $jobs[ $lang ] ) ) {
					foreach ( $deleted as $job_id => $post ) {
						wp_delete_post( $post->ID );
					}
					if ( ! empty( $deleted ) ) {
						add_action(
							'admin_notices',
							function () use ( $deleted, $lang ) {
								echo '<div class="notice notice-warning is-dismissible">
							<button type="button" class="notice-dismiss">
								<span class="screen-reader-text">' . __( 'Dismiss this notice.', 'eolia-app' ) . '</span>
							</button>
							<p>' . sprintf(
										_x(
											'Successful Deleted %d %s offers',
											'admin notice',
											$this->plugin_name
										),
										count( $deleted ),
										$lang
									) . '</p></div>';
							}
						);
					}
				}

				if ( $update = array_intersect_key( $jobs[ $lang ], (array) $posts[ $lang ] ) ) {
					foreach ( $update as $job_id => $value ) {
						/** @var JobController $job */
						$job  = $jobs[ $lang ][ $job_id ];
						$post = get_post( $job->get_postId() );
						if ( $job->get_modified() === $job->get_created() || $job->get_modified()->format(
								'Y-m-d H:i:s'
							) <= $post->post_modified ) {
							unset( $update[ $job_id ] );
							continue;
						}
						$post_id                                                         = $job->save();
						$posts_localized[ $job->get_id() ][ $job->get_language( true ) ] = $post_id;

					}
					if ( ! empty( $update ) ) {
						add_action(
							'admin_notices',
							function () use ( $update, $lang ) {
								echo '<div class="notice notice-info is-dismissible">
							<button type="button" class="notice-dismiss">
								<span class="screen-reader-text">' . __( 'Dismiss this notice.', 'eolia-app' ) . '</span>
							</button>
							<p>' . sprintf(
										_x(
											'Successful Updated %d %s offers',
											'admin notice',
											$this->plugin_name
										),
										count( $update ),
										$lang
									) . '</p></div>';
							}
						);
					}
				}

				if ( $new = array_diff_key( $jobs[ $lang ], (array) $posts[ $lang ] ) ) {
					foreach ( $new as $job_id => $value ) {
						/** @var JobController $job */
						$job                                                             = $jobs[ $lang ][ $job_id ];
						$post_id                                                         = $job->save();
						$posts_localized[ $job->get_id() ][ $job->get_language( true ) ] = $post_id;

					}
					add_action(
						'admin_notices',
						function () use ( $new, $lang ) {
							echo '<div class="notice notice-success is-dismissible">
							<button type="button" class="notice-dismiss">
								<span class="screen-reader-text">' . __( 'Dismiss this notice.', 'eolia-app' ) . '</span>
							</button>
							<p>' . sprintf(
									_x(
										'Successful Added %d %s offers',
										'admin notice',
										$this->plugin_name
									),
									count( $new ),
									$lang
								) . '</p></div>';
						}
					);
				}
			}


			if ( ! empty( $posts_localized ) && function_exists( 'pll_save_post_translations' ) ) {
				foreach ( $posts_localized as $post => $values ) {
					pll_save_post_translations( $values );
				}
			}

			$this->update_categories();


			if ( ! is_admin() ) {
				wp_send_json_success(
					array(
						'new'     => count( $new ),
						'updated' => count( $update ),
						'deleted' => count( $deleted ),
					)
				);
			}

			flush_rewrite_rules();
		}

		/**
		 * @param bool|null   $forced To force job update.
		 *
		 * @param null|string $lang   The current language.
		 *
		 * @return array|bool
		 * @throws \ErrorException
		 */
		public function get_jobs( $forced = null ) {
			$jobs = array();

			if ( null !== $this->jobs && null === $forced ) {
				return $this->jobs;
			}

			$stream = StreamController::get_instance();
			$stream = $stream->get_stream( 'offers', $forced );
			$xml    = simplexml_load_string( $stream, 'SimpleXMLElement', LIBXML_NOCDATA );
			if ( ! $xml || ! is_a( $xml, 'SimpleXMLElement' ) || ! count( $xml->job ) ) {
				add_action(
					'admin_notices',
					function () {
						echo '<div class="notice notice-warning is-dismissible">
							<button type="button" class="notice-dismiss">
								<span class="screen-reader-text">' . _x(
								'Dismiss this notice.',
								'admin notice',
								$this->plugin_name
							) . '</span>
							</button>
							<p>' . _x(
							     'It seem like there is no offers in stream the stream.',
							     'admin notice',
							     $this->plugin_name
						     ) . '</p></div>';
					}
				);

				return false;
			}

			foreach ( $xml->job as $item ) {
				$job = new JobController();
				$job->set_additionnalFields( $item );

				$job->set_language( str_replace( '-', '_', $item->version->__toString() ) );
				$job->set_id( $item->id->__toString() );

				$title = isset( $item->saisie1 ) && ! empty(
				trim(
					$item->saisie1->__toString()
				)
				) ? $item->saisie1->__toString() : $item->nomposte->__toString();

				$job->set_title( $title );

				if ( isset( $item->ref ) ) {
					$job->set_ref( $item->ref->__toString() );
				}

				if ( isset( $item->nomclient ) ) {
					$job->set_client( $item->nomclient->__toString() );
				}

				if ( isset( $item->datecreation ) ) {
					$created = DateTime::createFromFormat( 'd/m/Y H:i:s', $item->datecreation->__toString() );
					$job->set_created( $created );
				}

				if ( isset( $item->datemodification ) && ! empty( $item->datemodification->__toString() ) ) {
					$modified = DateTime::createFromFormat( 'd/m/Y H:i:s', $item->datemodification->__toString() );
					$job->set_modified( $modified );
				}

				if ( array_key_exists(
					     'description_field',
					     $this->options
				     ) && isset( $item->{$this->options['description_field']} ) && ! empty( $this->options['description_field'] ) ) {
					$job->set_description(
						html_entity_decode( $item->{$this->options['description_field']}->__toString() )
					);
				}

				if ( array_key_exists(
					     'res_main_category',
					     $this->options
				     ) && isset( $item->{$this->options['res_main_category']} ) && ! empty( $item->{$this->options['res_main_category']} ) ) {
					$job->set_category( $item->{$this->options['res_main_category']}->__toString() );
				} else {
					$job->set_category( 'uncategorized' );
				}

				if ( isset( $item->bu ) ) {
					$job->set_business_unit( $item->bu->__toString() );
				}

				if ( isset( $item->location->lat ) && ! empty( $item->location->lng ) ) {
					$job->set_location( $item->location->lat->__toString(), $item->location->lng->__toString() );
				}

				if ( isset( $item->questions ) && ! empty( $item->questions ) ) {
					$array = array();
					/** @var \SimpleXMLElement $question */
					foreach ( $item->questions->children() as $question ) {
						if ( ! empty( $question->__toString() ) ) {
							$array[] = $question->__toString();
						}
					}
					if ( ! empty( $array ) ) {
						$job->set_questions( $array );
					}
				}

				$args = array(
					'post_type'  => 'job',
					'showposts'  => 1,
					'meta_query' => array(
						array(
							'key'     => 'job_id',
							'value'   => $job->get_id(),
							'compare' => '=',
						),
						array(
							'key'     => 'lang',
							'value'   => $job->get_language(),
							'compare' => '=',
						),
					),
				);

				$query = new WP_Query( $args );

				// todo[rpozzi]: check if category have changed, then update it.
				if ( ! is_wp_error( $query ) && $query->have_posts() ) {
					$job->set_postId( $query->post->ID );
				} elseif ( null !== $forced ) {
					$job->save();
				}

				wp_reset_query();

				$jobs[ $job->get_language() ][ $job->get_id() ] = $job;
			}

			if ( empty( $jobs ) ) {
				return false;
			}

			return $this->jobs = $jobs;
		}

		/**
		 * @throws \ErrorException
		 */
		public function update_categories() {
			$terms_localized = $posts_localized = array();

			// Default categories
			if ( ! $all_jobs = $this->get_jobs() ) {
				return false;
			}

			foreach ( $all_jobs as $lang => $jobs ) {
				$lang = substr( $lang, 0, 2 );
				if ( ! $term = get_term_by( 'slug', 'uncategorized-' . $lang, 'job_category' ) ) {
					$term = wp_insert_term(
						__( 'Uncategorized' ),
						'job_category',
						array(
							'slug' => 'uncategorized-' . $lang,
						)
					);
					if ( function_exists( 'pll_set_term_language' ) && ! is_wp_error( $term ) ) {
						pll_set_term_language( $term['term_id'], $lang );
					}
				}
			}

			// Parse jobs to get all terms
			$terms_localized = $posts_localized = array();
			foreach ( $all_jobs as $lang => $jobs ) {
				/** @var JobController $job */
				foreach ( $jobs as $job_id => $job ) {
					if ( ! $value = $job->get_category() ) {
						continue;
					}

					if ( function_exists( 'pll_get_post_language' ) ) {
						$post_language = pll_get_post_language( $job->get_postId() );
					} else {
						$post_language = $job->get_language( true );
					}

					$term_slug = sanitize_title( $value . '-' . $post_language );

					$term = get_term_by( 'slug', $term_slug, 'job_category' );

					if ( false !== $term && ! is_wp_error( $term ) ) {
						if ( function_exists( 'pll_get_term' ) ) {
							if ( ! $term_id = pll_get_term( $term->term_id, $post_language ) ) {
								$term = wp_insert_term( $value, 'job_category', array( 'slug' => $term_slug ) );
								if ( false !== $term && ! is_wp_error( $term ) ) {
									$term_id = $term['term_id'];
									pll_set_term_language( $term_id, $post_language );
								}
							};
						} else {
							$term_id = $term->term_id;
						}
					} else {
						if ( ! $term = get_term_by( 'slug', $term_slug, 'job_category' ) ) {
							$term = wp_insert_term( $value, 'job_category', array( 'slug' => $term_slug ) );
							if ( ! is_wp_error( $term ) ) {
								$term_id = $term['term_id'];
								if ( function_exists( 'pll_set_term_language' ) ) {
									pll_set_term_language( $term_id, $post_language );
								}
							}
						} else {
							$term_id = $term->term_id;
						}

						if ( function_exists( 'pll_get_term_language' ) ) {
							pll_set_term_language( $term_id, $post_language );
						}
					}

					wp_set_object_terms( $job->get_postId(), $term_id, 'job_category' );
					if ( ! isset( $terms_localized[ $value ][ $post_language ] ) ) {
						$terms_localized[ $value ][ $post_language ] = $term_id;
						if ( function_exists( 'pll_set_term_language' ) ) {
							pll_set_term_language( $term_id, $post_language );
						}
					}
				}
			}

			wp_reset_query();

			if ( null !== $terms_localized && function_exists( 'pll_save_term_translations' ) ) {
				foreach ( $terms_localized as $term => $values ) {
					pll_save_term_translations( $values );
				}
			}

			if ( $terms_localized && count( $terms_localized ) > 0 ) {
				add_action(
					'admin_notices',
					function () use ( $terms_localized ) {
						echo '<div class="notice notice-info is-dismissible">
							<button type="button" class="notice-dismiss">
								<span class="screen-reader-text">' . __( 'Dismiss this notice.', 'eolia-app' ) . '</span>
							</button>
							<p>' . sprintf(
								_x(
									'Successful Updated %d job categories',
									'admin notice',
									$this->plugin_name
								),
								count( $terms_localized )
							) . '</p></div>';
					}
				);
			}

			return true;
		}

		/**
		 * @return array|bool
		 * @throws \ErrorException
		 */
		public function getAvailableJobsLanguages() {
			static $jobs;
			if ( null === $jobs ) {
				$stream = StreamController::get_instance();
				$stream = $stream->get_stream( 'offers' );
				$xml    = simplexml_load_string( $stream, 'SimpleXMLElement', LIBXML_NOCDATA );
				$json   = json_decode( json_encode( $xml ), true );
				$jobs   = $json['job'];
			}

			$languages = array();

			if ( null === $jobs ) {
				return false;
			}

			foreach ( $jobs as $job ) {
				$lang = str_replace( '-', '_', $job['version'] );
				if ( false === in_array( $lang, $languages, true ) ) {
					$languages[] = $lang;
				}
			}

			if ( empty( $languages ) ) {
				return false;
			}

			return $languages;
		}

		/**
		 * Add eolia xmlns for RSS2 validation.
		 */
		public function add_eolia_rss_namespace() {
			echo 'xmlns:eolia="' . get_bloginfo( 'wpurl' ) . '"';
		}

		/**
		 * Add eolia fields to wordpress RSS2.
		 */
		public function add_eolia_rss_fields() {
			if ( get_post_type() === 'job' ) {
				$fields = $this->get_fields( 'offer_fields' );

				$xml = new \XMLWriter();
				$xml->openMemory();

				foreach ( $fields as $field ) {
					if ( $value = get_post_meta( get_the_ID(), $field->get_id(), true ) ) {
						if ( is_string( $value ) && empty( trim( $value ) ) ) {
							continue;
						}

						if ( $field->get_id() === 'questions' ) {
							$xml->startElement( "eolia:{$field->get_id()}" );
							foreach ( $value as $k => $v ) {
								$xml->startElement( 'eolia:question' );
								$xml->writeCData( $v );
								$xml->endElement();
							}
							$xml->endElement();
						} elseif ( $field->get_type() === 'longtext' ) {
							$xml->startElement( "eolia:{$field->get_id()}" );
							$xml->writeCData( $value );
							$xml->endElement();
						} elseif ( ! is_string( $value ) ) {
							$xml->startElement( "eolia:{$field->get_id()}" );
							foreach ( (array) $value as $k => $v ) {
								$xml->startElement( (string) $k );
								$xml->text( $v );
								$xml->endElement();
							}
							$xml->endElement();
						} else {
							$xml->startElement( "eolia:{$field->get_id()}" );
							$xml->text( $value );
							$xml->endElement();
						}
					}
				}

				echo $xml->outputMemory();

			}
		}

		/**
		 * @param string $type application_fields|offer_fields.
		 *
		 * @return array|bool
		 * @throws \ErrorException
		 */
		public function get_fields( $type ) {
			if ( array_key_exists( $type, $this->fields ) ) {
				return $this->fields[ $type ];
			}
			$stream_controller = StreamController::get_instance();
			$stream            = $stream_controller->get_stream( $type );
			$xml               = new \SimpleXMLElement( $stream );
			$fields            = array();

			if ( ! $xml ) {
				add_action(
					'admin_notices',
					function () {
						echo '<div class="notice notice-error is-dismissible">
							<button type="button" class="notice-dismiss">
								<span class="screen-reader-text">' . _x(
								'Dismiss this notice.',
								'admin notice',
								'eolia-app'
							) . '</span>
							</button>
							<p>' . _x(
							     'It seem that there is no fields in application/offer stream.',
							     'admin notice',
							     'eolia-app'
						     ) . '</p></div>';
					}
				);
			}
			/** @var \SimpleXMLElement $item */
			foreach ( $xml as $item ) {
				$field = new FieldController();
				$field->set_language( $this->lang );

				if ( ! $item->xpath( './display_names/label[@lang="' . $field->get_language( true ) . '"]' ) ) {
					continue;
				}

				$field->set_label(
					trim(
						$item->xpath(
							'./display_names/label[@lang="' . $field->get_language( true ) . '"]'
						)[0]->__toString()
					)
				);

				$field->set_id( $item->xpath( './internal' )[0]->__toString() );

				if ( $field->get_id() === 'questions' ) {
					$field->set_type( 'custom' )->set_component( 'questions' );
					$fields[ $field->get_id() ] = $field;
					continue;
				}

				switch ( $item->xpath( './type' )[0]->__toString() ) {
					case ( false !== strpos( (string) $item->xpath( './type' )[0], 'nvarchar' ) ):
						$field->set_type( 'string' );
						break;
					case 'float':
						$field->set_type( 'float' );
						break;
					case 'datetime':
						$field->set_type( 'datetime' );
						break;
					default:
						$field->set_type( $item->xpath( './type' )[0]->__toString() );
				}
				switch ( strtolower( $item->xpath( './component' )[0]->__toString() ) ) {
					case 'pj':
						$field->set_component( 'file' );
						break;
					case 'saisie':
						$field->set_component( 'text' );
						break;
					case 'liste':
						$field->set_component( 'select' );
						break;
					case 'date':
						$field->set_component( 'datepicker' );
						break;
					default:
						$field->set_component( $item->xpath( './component' )[0]->__toString() );
				}

				if ( isset( $item->conditional_parent ) ) {
					$field->set_parentId( $item->conditional_parent->__toString() );
				}

				if ( isset( $item->textkernel ) && ! empty( $item->textkernel ) ) {
					$field->set_textkernalPath( $item->textkernel );
				}

				if ( count( $item->values ) > 0 ) {
					$values = array();
					foreach (
						$item->xpath(
							'./values/value/label[@lang="' . $field->get_language( true ) . '"]'
						) as $value
					) {
						$id                     = $value->xpath( 'parent::*' )[0]->attributes()['id']->__toString();
						$values[ $id ]['label'] = $value->__toString();
						if ( $parent_id = $value->xpath( 'parent::*' )[0]->attributes()['parent_id'] ) {
							$values[ $id ]['parent_id'] = $parent_id->__toString();
						}
					}
					$field->set_values( $values );
				}
				$fields[ $field->get_id() ] = $field;
			}

			uasort(
				$fields,
				function ( FieldInterface $a, FieldInterface $b ) {
					return strcmp( $a->get_label(), $b->get_label() );
				}
			);

			if ( null === $fields ) {
				return false;
			}

			return $this->fields[ $type ] = $fields;
		}
	}
}

namespace {

	use Eolia\Controllers\JobController;
	use Eolia\EoliaWordpress;

	/**
	 * @return array|bool
	 */
	function eolia_get_jobs() {
		static $jobs;
		if ( ! isset( $jobs ) ) {
			$eolia = EoliaWordpress::get_instance();
			$jobs  = $eolia->get_jobs();
		}

		return $jobs;
	}

	/**
	 * @param string $type application_fields|offer_fields.
	 *
	 * @return array|bool
	 */
	function eolia_get_fields( $type = 'offer_fields' ) {
		$eolia = EoliaWordpress::get_instance();

		return $eolia->get_fields( $type );
	}

	/**
	 * @param string $field_id The field ID.
	 * @param string $type     application_fields|offer_fields.
	 *
	 * @return bool|\Eolia\Controllers\FieldController
	 */
	function eolia_get_field( $field_id, $type = 'offer_fields' ) {
		$eolia  = EoliaWordpress::get_instance();
		$fields = $eolia->get_fields( $type );
		if ( ! array_key_exists( $field_id, $fields ) ) {
			return false;
		}

		return $fields[ $field_id ];
	}

	/**
	 * @param int $id The job internal ID.
	 *
	 * @return \Eolia\Controllers\JobController
	 */
	function eolia_get_job( $id = null ) {
		if ( null === $id ) {
			$id = get_post_meta( get_the_ID(), 'job_id', true );
		}

		return new JobController( $id );
	}

	/**
	 * Check if current page is an apply
	 *
	 * @return bool
	 */
	function is_apply() {
		static $options;
		static $is_apply = false;

		if ( null === $options ) {
			$options = get_option( 'eolia-app' );
		}

		$apply_suffix = _x( 'apply', 'slug', 'eolia-app' );

		if ( is_singular( 'job' ) && get_query_var( $apply_suffix ) ) {
			$is_apply = true;
		} elseif ( ! is_singular( 'job' ) && ( is_single() || is_page() ) ) {
			$post     = get_post();
			$is_apply = has_shortcode( $post->post_content, 'eolia_form' ) ? true : false;
		}

		return $is_apply;
	}
}