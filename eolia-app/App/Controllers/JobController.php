<?php
/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author     Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date       2017
 * @copyright  Eolia Software (http://www.eolia-consulting.com)
 * @licence    GNU
 *
 * @file       JobController.php
 * @package    eolia-app
 * @version    1.0.0
 */

/**
 * Created by PhpStorm.
 * User: eolia
 * Date: 13/04/2017
 * Time: 19:42
 */

namespace Eolia\Controllers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

use Eolia\Interfaces\JobInterface;
use Eolia\Models\JobModel;


/**
 * Class Job
 *
 * @package Eolia\Controllers
 */
class JobController extends JobModel {


	/**
	 * @var mixed|void
	 */
	private $options;


	/**
	 * Job constructor.
	 *
	 * @param null|int|JobInterface $job The job.
	 *
	 */
	public function __construct( $job = null ) {
		if ( ! is_a( $job, JobInterface::class ) ) {
			parent::__construct( $job );
		}
		$this->options = get_option( 'eolia-app' );
	}

	/**
	 * @param int $post_id The post ID.
	 *
	 * @return string
	 */
	public static function get_apply_url( $job_id ) {
		if ( ! $job = eolia_get_job( $job_id ) ) {
			return false;
		}
		$apply_suffix = _x( 'apply', 'slug', 'eolia-app' );

		return esc_url( add_query_arg( $apply_suffix, '', get_the_permalink( $job->get_postId() ) ) );
	}

	/**
	 * @return bool|string
	 */
	public function render_view() {
		if ( ! $selected_fields = json_decode( $this->options['offer_layout'] ) ) {
			return '<div class="alert alert-warning">' . __(
					'No offer details fields found, check your plugin configuration',
					'eolia-app'
				) . '</div>';
		}
		$content = '<div class="eolia_job" data-job-id="' . $this->get_id() . '" data-job-category="' . sanitize_title(
				$this->get_category()
			) . '" itemscope="" itemtype="http://schema.org/JobPosting">';
		$content = apply_filters( 'eolia_filter_before_view', $content, $this );
		$row     = 0;
		foreach ( $selected_fields as $selected_field ) {
			try {
				$field           = new FieldController( $selected_field->name, 'offer_fields' );
				$options         = new \stdClass();
				$options->row_id = $row;
				if ( isset( $selected_field->component ) ) {
					$field->set_component( $selected_field->component );
				}
				isset( $selected_field->txt ) ? $field->set_values( $selected_field->txt ) : null;
				foreach ( $selected_field as $key => $value ) {
					$options->{$key} = $value;
				}
				$field->set_options( $options );
				$field = apply_filters( "eolia_filter_view_field_{$field->get_id()}", $field, $selected_field );
				ob_start();
				echo $field->render_view();
				$content .= ob_get_contents();
				ob_end_clean();
			} catch ( \Exception $e ) {
				$options         = new \stdClass();
				$options->row_id = $row;
				$field           = new FieldController();
				$field->set_id( $selected_field->name )
				      ->set_type( 'custom' );

				isset( $selected_field->txt ) ? $field->set_values( $selected_field->txt ) : null;
				foreach ( $selected_field as $key => $value ) {
					$options->{$key} = $value;
				}
				$field->set_options( $options );

				if ( isset( $selected_field->component ) ) {
					$field->set_component( $selected_field->component );
				}
				$field = apply_filters( "eolia_filter_view_field_{$field->get_id()}", $field, $selected_field );
				ob_start();
				echo $field->render_view();
				$content .= ob_get_contents();
				ob_end_clean();
			}
			$row ++;
		}
		$content .= '</div>';

		return apply_filters( 'eolia_filter_after_view', $content, $this );
	}

	/**
	 * @return mixed|string
	 */
	public function render_form() {
		$content = '';
		$content = apply_filters( 'eolia_filter_before_form', $content, $this );
		// Ne pas afficher le formulaire si des données sont envoyée car redirection peu après...
		$criteria_field = $this->options['custom_form_param'];

		if ( $this->get_additionnal_field( $criteria_field ) && ! empty(
			$this->get_additionnal_field( $criteria_field )
			) ) {

			$custom_function_name = preg_replace(
				'/[^a-zA-Z0-9]/',
				'',
				ucfirst( $this->get_additionnal_field( $criteria_field ) )
			);

			// open user custom function in functions.php.
			if ( function_exists( 'doEoliaCustomForm' . $custom_function_name ) ) {

				$content .= '<div class="job_application job_application--custom-' . $custom_function_name . '"><form enctype="multipart/form-data" method="post" action="" id="applyjob" name="applyjob" class="applyform custom_apply_form custom_apply_form' . $custom_function_name . '">';
				if ( isset( $_SESSION['eolia-origine'], $_SESSION['eolia-origine-url'] ) ) {
					$content .= '<input type="hidden" name="origine" value="' . $_SESSION['eolia-origine'] . '" />';
					$content .= '<input type="hidden" name="origine_url" value="' . $_SESSION['eolia-origine-url'] . '" />';
				} else {
					if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
						$content .= '<input type="hidden" name="origine" value="' . $_SERVER['HTTP_REFERER'] . '" />';
						$content .= '<input type="hidden" name="origine_url" value="' . parse_url(
								$_SERVER['HTTP_REFERER'],
								PHP_URL_HOST
							) . '" />';
					}
				}
				$content .= '<input type="hidden" name="job-id" value="' . $this->get_id() . '" />';
				$content .= '<input type="hidden" name="eolia-app-action" value="job_apply" />';
				$content .= '<input type="hidden" name="ref" value="' . $this->get_ref() . '" />';
				$content .= '<input type="hidden" name="type" value="' . $this->get_additionnal_field(
						$criteria_field
					) . '" />';

				$content .= call_user_func(
					'doEoliaCustomForm' . $custom_function_name,
					(array) $this
				);
				$content .= '</form></div>';

			} else {
				$content .= '<p>Veuillez déclarer la fonction <strong>doEoliaCustomForm<span style="text-decoration: underline">' . $custom_function_name . '</span>($atts)</strong> dans votre fichier de thème functions.php</p>';
				$content .= "<p>La valeur du champs <strong>\"$criteria_field\"</strong> permet de définir la fonction à appeler en fonction de sa valeur.</p>";
			}
		} else {
			$content .= $this->render_form_inner();
		}

		$content = apply_filters( 'eolia_filter_after_form', $content, $this );

		return $content;
	}

	/**
	 * @return string
	 */
	public function render_form_inner() {
		wp_enqueue_style( 'eolia-app' );
		wp_enqueue_script( 'eolia-app' );
		$content = array();

		if ( is_single() ) {
			$selected_fields = json_decode( $this->options['jobapply_layout'] );
		} else {
			$selected_fields = json_decode( $this->options['application_layout'] );
		}
		$class = '';

		if ( 1 === $this->options['is_textkernel'] ) {
			$class .= 'textkernel_active';
		}

		if ( count( $selected_fields ) <= 0 ) {
			return false;
		}
		$content[] = '<div class="eolia_form eolia_form--' . ( $this->get_id() ? 'offer' : 'unsolicited' ) . '">';
		$content[] = '<form enctype="multipart/form-data" method="post" action="" id="eolia_form" name="applyjob" class="eolia_form ' . $class . '">';
		if ( isset( $_SESSION['eolia-origine'], $_SESSION['eolia-origine-url'] ) ) {
			$content[] = '<input type="hidden" name="origine" value="' . $_SESSION['eolia-origine'] . '" />';
			$content[] = '<input type="hidden" name="origine_url" value="' . $_SESSION['eolia-origine-url'] . '" />';
		} else {
			if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
				$content[] = '<input type="hidden" name="origine" value="' . $_SERVER['HTTP_REFERER'] . '" />';
				$content[] = '<input type="hidden" name="origine_url" value="' . parse_url(
						$_SERVER['HTTP_REFERER'],
						PHP_URL_HOST
					) . '" />';
			}
		}

		if ( $this->get_id() ) {
			$content[] = '<input type="hidden" name="job-id" value="' . $this->get_id() . '" />';
			$content[] = '<input type="hidden" name="eolia-app-action" value="job_apply" />';
			$content[] = '<input type="hidden" name="ref" value="' . $this->get_ref() . '" />';
			$content[] = '<input type="hidden" name="type" value="application" />';
		} else {
			$content[] = '<input type="hidden" name="eolia-app-action" value="job_apply" />';
			$content[] = '<input type="hidden" name="type" value="unsolicited" />';
		}
		$row = 0;
		foreach ( $selected_fields as $selected_field ) {
			try {
				if ( 'questions' === $selected_field->name ) {
					$field = new FieldController( $selected_field->name, 'offer_fields' );
				} else {
					$field = new FieldController( $selected_field->name, 'application_fields' );
				}

				$field->set_id( $selected_field->name )
				      ->set_type( 'custom' );

				$options         = new \stdClass();
				$options->row_id = $row;
				foreach ( $selected_field as $key => $value ) {
					$options->{$key} = $value;
				}

				$field->set_options( $options );

				isset( $selected_field->label ) && ! empty( $selected_field->label ) ? $field->set_label(
					$selected_field->label
				) : null;
				isset( $selected_field->txt ) ? $field->set_values( $selected_field->txt ) : null;
				isset( $selected_field->required ) && true === $selected_field->required ? $field->set_required(
					true
				) : null;

				if ( isset( $selected_field->component ) ) {
					$field->set_component( $selected_field->component );
				}

				$field = apply_filters( "eolia_filter_view_field_{$field->get_id()}", $field, $selected_field );
				ob_start();
				echo $field->render_form();
				$content[] = ob_get_contents();
				ob_end_clean();
			} catch ( \Exception $e ) {
				$field = new FieldController();
				$field->set_id( $selected_field->name )
				      ->set_type( 'custom' );

				$options         = new \stdClass();
				$options->row_id = $row;
				foreach ( $selected_field as $key => $value ) {
					$options->{$key} = $value;
				}
				$field->set_options( $options );

				isset( $selected_field->txt ) ? $field->set_values( $selected_field->txt ) : null;
				isset( $selected_field->required ) && true === $selected_field->required ? $field->set_required(
					true
				) : null;

				if ( isset( $selected_field->component ) ) {
					$field->set_component( $selected_field->component );
				}
				$field = apply_filters( "eolia_filter_view_field_{$field->get_id()}", $field, $selected_field );
				ob_start();
				echo $field->render_view();
				$content[] = ob_get_contents();
				ob_end_clean();
			}
			$row ++;
		}
		$content[] = do_shortcode( '[eolia_captcha/]' );

		if ( ! $this->get_id() ) {
			$data_ga_type = 'data-ga="Offer::Apply::Unsolicited"';
		} else {
			$data_ga_type = 'data-ga="Offer::Apply::' . sanitize_title( $this->get_title() ) . ' ' . $this->get_id(
				) . '"';
		};

		$content[] = '<div class="eolia_form-row eolia_form-row--input eolia_form-row--submit" id="eolia-submit"><div class="eolia_form-group" data-field="submit"><input type="submit" value="' . apply_filters(
				'eolia_filter_form_field_submit_btn',
				_x(
					'Apply',
					'form submit',
					'eolia-app'
				)
			) . '" ' . $data_ga_type . ' class="eolia_input eolia_input--button" /></div></div>';
		$content[] .= '</form></div>';

		return implode( '', $content );
	}

	/**
	 * @param $key
	 *
	 * @return bool|\Eolia\Interfaces\FieldInterface
	 */
	public function find_apply_field_by_internal( $key ) {
		static $fields;
		if ( ! isset( $fields ) ) {
			$fields = eolia_get_fields( 'application_fields' );
		}
		if ( ! isset( $fields[ $key ] ) ) {
			return false;
		}

		return $fields[ $key ];
	}
}
