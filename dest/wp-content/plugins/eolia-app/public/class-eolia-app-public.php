<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

use Eolia\Controllers\JobController;

/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author        Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date          2017
 * @copyright     Eolia Software (http://www.eolia-consulting.com)
 * @licence       GNU
 *
 * @file          class-eolia-app-public.php
 * @package       Wp_Eolia_App
 * @subpackage    Wp_Eolia_App/public
 * @version       1.0.0
 */
class Wp_Eolia_App_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version
	 */
	private $version;

	/**
	 * Plugin Current informations
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var array
	 */
	private $options;
	/**
	 * Plugin language
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var mixed
	 */
	private $lang;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version     The version of this plugin.
	 * @param      array  $options     The saved options of this plugin.
	 */
	public function __construct( $plugin_name, $version, $options ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->options     = $options;

		add_filter( 'eolia_filter_mail_to', array( $this, 'prefix_local_email' ), 9 );
	}

	/**
	 * Format query for searching offers
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array|null $params The requested params.
	 *
	 * @return array
	 */
	public static function query_search_results( $params = null ) {
		static $options;
		if ( ! $options ) {
			$options = get_option( 'eolia-app' );
		}
		global $wpdb;
		$fields = eolia_get_fields();


		$params_meta_query = $keywords_meta_query = $results = null;

		$order = $options['res_order'];

		$query = $base_query = array(
			'post_type'  => 'job',
			'showposts'  => - 1,
			'order'      => $order,
			'orderby'    => 'meta_value',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'relation' => 'OR',
					array(
						'key'     => $options['res_orderby'],
						'compare' => 'EXISTS',
					),
					array(
						'key'     => $options['res_orderby'],
						'compare' => 'NOT EXISTS',
					),
				),
			),
		);

		foreach ( $params as $key => $value ) {
			if ( empty( $value ) || ! array_key_exists( $key, $fields ) ) {
				continue;
			}
			/** @var \Eolia\Controllers\FieldController $field */
			$field      = $fields[ $key ];
			$tmp_values = null;
			if ( is_array( $value ) ) {
				foreach ( (array) $value as $v ) {
					$tmp_values[ $v ] = $field->get_value( $v )['label'];
				}
			} else {
				$tmp_values = $field->get_value( $value )['label'];
			}
			$search_compare                          = is_array( $value ) ? 'IN' : '=';
			$search_value                            = $tmp_values;
			$params_meta_query['meta_query'][ $key ] = array(
				'key'     => $key,
				'value'   => $search_value,
				'compare' => $search_compare,
			);
		}

		if ( null !== $params_meta_query ) {
			$query = array_merge( $base_query, $params_meta_query );
		}

		if ( isset( $params['keywords'] ) && ! empty( $params['keywords'] ) ) {
			/** @var \Eolia\Controllers\FieldController $field */
			foreach ( $fields as $field ) {
				if ( array_key_exists( $field->get_id(), $params ) ) {
					continue;
				}
				$field_id = $field->get_id();
				$keywords = explode( ' ', $params['keywords'] );
				if ( count( $keywords ) > 1 ) {
					$search_value   = $keywords;
					$search_compare = 'IN';
				} else {
					$search_value   = $wpdb->esc_like( $params['keywords'] );
					$search_compare = 'LIKE';
				}
				$keywords_meta_query['meta_query'][ $field_id ] = array(
					'key'     => $field_id,
					'value'   => $search_value,
					'compare' => $search_compare,
				);
			}
			$keywords_meta_query['meta_query']['relation'] = 'OR';
		}


		if ( null !== $keywords_meta_query ) {
			$keywords_query    = new WP_Query( array_merge( $base_query, $keywords_meta_query ) );
			$query['post__in'] = wp_list_pluck( $keywords_query->posts, 'ID' );
			wp_reset_query();
		}

		$query['meta_query'][] = array(
			'key'     => 'lang',
			'value'   => get_locale(),
			'compare' => '=',
		);

		$query = new WP_Query( $query );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$group                             = get_post_meta(
					get_the_ID(),
					$options['res_main_category'],
					true
				);
				$results[ $group ][ get_the_ID() ] = get_post();
			}
		}
		wp_reset_query();

		if ( null === $results || empty( $results ) ) {
			return false;
		}

		return $results;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function enqueue_styles() {
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function enqueue_scripts() {
	}

	/**
	 * Send and redirect user after form submission.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function redirect_form_submission() {
		if ( ! isset( $_REQUEST['action'] ) && isset( $_REQUEST['eolia-app-action'] ) && $_REQUEST['eolia-app-action'] = 'job_apply' ) {
			$job = null;

			if ( isset( $_REQUEST['job-id'] ) ) {
				$job = new \Eolia\Controllers\JobController( $_REQUEST['job-id'] );
			}
			if ( $this->send_apply_mail( $job ) ) {
				if ( isset( $_REQUEST['job-id'] ) && '' !== $_REQUEST['job-id'] ) {
					$url = esc_url( $this->options['thanks_offer'] );
				} else {
					$url = esc_url( $this->options['thanks_application'] );
				}
				if ( empty( $url ) ) {
					$url = get_home_url();
				}
				do_action( 'eolia_action_redirect_thanks', $job, $url );
				wp_redirect( $url );
				exit();
			}
			wp_die(
				__(
					'<h1>Error sending your information</h1><p>Your application could not be sent, please try again in a moment.</p>',
					'eolia-app'
				),
				sprintf( __( 'Error sending your information - %s', 'eolia-app' ), get_bloginfo( 'name' ) ),
				array( 'response' => 200 )
			);
		}
	}

	/**
	 * Send email to Eolia robots
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param \Eolia\Controllers\JobController $job_object The current job object.
	 *
	 * @return \PHPMailer
	 * @throws \phpmailerException
	 */
	public function send_apply_mail( $job_object ) {
		$attachments  = array();
		$out_code     = '';
		$out_readable = '<style>
    body {
        background: #efefef;
    }

    table {
        background: #fff;
        width: 750px;
        margin: 10px auto;
    }

    td {
        border-bottom: 1px solid #ccc;
        padding: 5px;
    }

    th {
        font-weight: bold;
        font-size: 18px;
        padding: 10px;
    }

    .pre {
        display: block;
        padding: 7px;
        margin: 0 0 8px;
        font-size: 13px;
        line-height: 1.2;
        word-break: break-all;
        word-wrap: break-word;
        color: #333333;
        background-color: #f5f5f5;
        border: 1px solid #cccccc;
        border-radius: 4px;
        font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
    }
</style>
<table class="table">
    <tr>
        <th colspan="2">' . __( 'Application', 'eolia-app' ) . '</th>
    </tr>';
		$locale       = get_locale();

		$out_code     .= 'langue : ' . $locale . "\r\n";
		$out_readable .= '<tr><td>' . __( 'Language', 'eolia-app' ) . '</td><td>' . $locale . '</td></tr>';
		if ( isset( $this->options['recaptcha_key'], $this->options['recaptcha_secret'] ) && ( ! empty(
				trim(
					$this->options['recaptcha_key']
				)
				) && ! empty( trim( $this->options['recaptcha_secret'] ) ) ) ) {
			if ( ! array_key_exists(
					'g-recaptcha-response',
					$_REQUEST
				) || empty( $_REQUEST['g-recaptcha-response'] ) ) {
				wp_die( _x( 'You must complete reCaptcha challenge', 'form recaptcha error', $this->plugin_name ) );
			}
			$args = array(
				'body' => array(
					'secret'   => $this->options['recaptcha_secret'],
					'response' => $_REQUEST['g-recaptcha-response'],
					'remoteip' => array_key_exists(
						'HTTP_X_FORWARDED_FOR',
						$_SERVER
					) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
				),
			);

			$response  = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', $args );
			$recaptcha = json_decode( $response['body'] );
			if ( ! isset( $recaptcha->success ) || empty( $recaptcha->success ) || ! $recaptcha->success ) {
				wp_die(
					_x(
						'Error with captcha, please re-submit your application, or contact the administrator.',
						'form recaptcha error',
						$this->plugin_name
					)
				);
			}
			unset( $_REQUEST['g-recaptcha-response'] );
		}

		if ( ! isset( $_REQUEST['email'] ) || empty( $_REQUEST['email'] ) || ! filter_var(
				$_REQUEST['email'],
				FILTER_VALIDATE_EMAIL
			)
		) {
			wp_die(
				_x(
					'You must privide a valid email address.',
					'form mail error',
					$this->plugin_name
				)
			);
		}
		/** The current user REQUEST. @var array $_REQUEST */
		foreach ( $_REQUEST as $k => $v ) {
			if ( 'apply' === $k ) {
				continue;
			}

			if ( strpos( $k, 'external_' ) === false && strpos( $k, 'label_file_' ) === false ) {
				$out_code .= $k . ' : ';
				$out_code .= is_array( $v ) ? implode( ', ', $v ) : stripslashes( $v );
				$out_code .= "\r\n";
			}

			/** @var \Eolia\Controllers\FieldController $field */
			try {
				if ( $k === 'questions' ) {
					$field = new \Eolia\Controllers\FieldController( $k, 'offer_fields' );

				} else {
					$field = new \Eolia\Controllers\FieldController( $k, 'application_fields' );
				}
			} catch ( Exception $e ) {
				$field = new \Eolia\Controllers\FieldController();
				$field->set_id( $k )
				      ->set_language( get_locale() )
				      ->set_label( $k );
				if ( is_array( $v ) ) {
					$tmp_values = array();
					foreach ( $v as $value ) {
						$tmp_values[ $value ]['label'] = $field->get_value( $value );
					}
					$field->set_values( $tmp_values );
				} elseif ( ! empty( $v ) ) {
					$field->set_values( $v );
				}
			}
			$out_readable .= '<tr><td>';
			$out_readable .= $field->get_label();
			$out_readable .= '</td><td>';
			switch ( $field->get_component() ) {
				case 'select':
					$tmp_values = array();
					if ( is_array( $v ) && ! empty( $v ) ) {
						foreach ( (array) $v as $field_value ) {
							$tmp_values[] = $field->get_value( $field_value )['label'];
						}
						if ( ! empty( $tmp_values ) ) {
							if ( is_array( $tmp_values ) ) {
								$out_readable .= implode( ', ', $tmp_values );
							} else {
								$out_readable .= $tmp_values;
							}
						}
					} else {
						$out_readable .= $field->get_value( $v )['label'];
					}
					break;
				case 'questions':
					if ( is_array( $v ) && ! empty( $v ) ) {
						foreach ( (array) $v as $key => $field_value ) {
							$out_readable .= '<p><b>' . $job_object->get_questions()[ $key ] . '</b></p>';
							$out_readable .= '<p>' . $field_value . '</p>';
						}
					}
					break;

				default:
					$out_readable .= $v;
			}
			$out_readable .= '</td></tr>';

			if ( 'eolia-app-action' !== $k && strpos( $k, 'external_' ) !== false ) {

				$json = json_decode( stripslashes( $v ) );
				if ( null !== $json ) {

					/* GOOGLE */
					if ( ! empty( $json->service ) && 'google' === $json->service && ! empty( $json->token ) && ! empty( $json->file ) && ! empty( $json->title ) && in_array(
							str_replace( '.', '', $json->ext ),
							\Eolia\EoliaWordpress::$file_extensions,
							true
						)
					) {
						$auth_header = array( 'Authorization: Bearer ' . $json->token );
						$url         = $json->file;
						$attachment  = WP_CONTENT_DIR . '/uploads/' . sanitize_file_name(
								uniqid( 'gdrive_', true ) . $json->ext
							);
						$ch          = curl_init();
						$fp          = fopen( $attachment, 'w' );
						curl_setopt( $ch, CURLOPT_URL, $url );
						curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
						curl_setopt( $ch, CURLOPT_HTTPHEADER, $auth_header );
						curl_setopt( $ch, CURLOPT_FILE, $fp );
						curl_exec( $ch );
						curl_close( $ch );
						$attachments[] = $attachment;
					}
					/* DROPBOX */
					if ( ! empty( $json->service ) && 'dropbox' === $json->service && ! empty( $json->file ) && ! empty( $json->title ) ) {
						$url        = $json->file;
						$attachment = WP_CONTENT_DIR . '/uploads/' . sanitize_file_name(
								uniqid( 'dropbox_', false ) . '.' . substr( strrchr( $json->title, '.' ), 1 )
							);
						$ch         = curl_init();
						$fp         = fopen( $attachment, 'w' );
						curl_setopt( $ch, CURLOPT_URL, $url );
						curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
						curl_setopt( $ch, CURLOPT_FILE, $fp );
						curl_exec( $ch );
						curl_close( $ch );
						$attachments[] = $attachment;
					}

					/* VIADEO */
					if ( ! empty( $json->service ) && 'viadeo' === $json->service && ! empty( $json->cv ) ) {
						$datas    = json_encode( $json->cv );
						$args     = array(
							'method' => 'POST',
							'body'   => array(
								'client_id'     => 'eolia-wp-plugin',
								'client_secret' => 'QSvp1c2Cfu0Y664g71D4wZvghwo1oY9w',
								'grant_type'    => 'client_credentials',
							),
						);
						$response = wp_remote_post( 'https://api.eolia-interne.com/token', $args );
						if ( is_wp_error( $response ) ) {
							wp_die( __( 'Error expected response from Eolia API', 'eolia-app' ) );
						}
						$json = json_decode( $response['body'] );
						if ( ! isset( $json->token_type, $json->access_token ) ) {
							wp_die( __( 'Error while getting Eolia API token', 'eolia-app' ) );
						}

						$token      = $json->access_token;
						$url        = 'https://api.eolia-interne.com/generator/pdf/cv';
						$attachment = WP_CONTENT_DIR . '/uploads/' . sanitize_file_name(
								uniqid( 'viadeo_', false ) . '.pdf'
							);
						$ch         = curl_init();
						$fp         = fopen( $attachment, 'w' );
						curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
						curl_setopt( $ch, CURLOPT_POSTFIELDS, $datas );
						curl_setopt(
							$ch,
							CURLOPT_HTTPHEADER,
							array(
								'Content-Type: application/json',
								'Content-Length: ' . strlen( $datas ),
								'Authorization: Bearer ' . $token,
							)
						);
						curl_setopt( $ch, CURLOPT_URL, $url );
						curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
						curl_setopt( $ch, CURLOPT_FILE, $fp );
						curl_exec( $ch );
						curl_close( $ch );
						$attachments[] = $attachment;
					}

					/* INDEED */
					if ( ! empty( $json->service ) && 'indeed' === $json->service ) {
						session_start();
						$data        = json_decode( $_SESSION['indeed_datas'] );
						$filename    = $data->applicant->resume->file->fileName;
						$fileData    = $data->applicant->resume->file->data;
						$endfilename = sanitize_file_name( uniqid( 'indeed_', false ) . $filename );
						if ( file_put_contents(
							WP_CONTENT_DIR . '/uploads/' . $endfilename,
							base64_decode( $fileData )
						) ) {
							$attachment    = WP_CONTENT_DIR . '/uploads/' . $endfilename;
							$attachments[] = $attachment;
						}
					}
				}
			}
		}
		$out_readable .= '</table>';

		/* ATTACH LOCAL FILES SENDED */
		if ( isset( $_FILES ) ) {
			foreach ( $_FILES as $k => $file ) {
				if ( ! empty( $file['name'] ) ) {
					$extension = str_replace( '.', '', strtolower( strrchr( $file['name'], '.' ) ) );
					if ( ! in_array( $extension, \Eolia\EoliaWordpress::$file_extensions, true ) ) {
						wp_die(
							sprintf(
								_x(
									'Wrong file extensions (allowed extensions : %s)<br/>Current extension : %s',
									'alert',
									'eolia-app'
								),
								implode( ', ', \Eolia\EoliaWordpress::$file_extensions ),
								$extension
							)
						);
					}

					if ( $file['size'] > 0 && @filesize(
							$file['tmp_name']
						) > ( $this->options['maxfilesize'] * 1000 * 1000 )
					) {
						wp_die(
							sprintf(
								_x( 'File size exceed %s Mo !', 'alert', 'eolia-app' ),
								$this->options['maxfilesize']
							)
						);
					} else {
						$out_code   .= $k . ' : ';
						$out_code   .= $file['name'];
						$out_code   .= "\r\n";
						$attachment = WP_CONTENT_DIR . '/uploads/' . basename( $file['name'] );
						move_uploaded_file( $file['tmp_name'], $attachment );
						$attachments[] = $attachment;
					}
				}
			}
		}
		$message = "$out_readable<pre class=\"pre\">$out_code</pre>";

		$mail = new PHPMailer( true );

		try {
			$mail->isSMTP();
			$mail->isHTML();
			$mail->CharSet    = 'UTF-8';
			$mail->Host       = 'smtp.mandrillapp.com';
			$mail->SMTPAuth   = true;
			$mail->Username   = base64_decode( 'c3VwcG9ydEBlb2xpYS1tYWlsLmNvbQ==' );
			$mail->Password   = base64_decode( 'Ylc2T1BLcURKT3c1Vmt2dm1OTHRuQQ==' );
			$mail->SMTPSecure = 'ssl';
			$mail->Port       = 465;

			$mail->addCustomHeader(
				'X-MC-Tags',
				implode(
					',',
					array(
						get_bloginfo( 'name' ),
					)
				)
			);

			$mail->addCustomHeader(
				'X-MC-Metadata',
				json_encode(
					array(
						'job_id'      => is_a(
							$job_object, \Eolia\Interfaces\JobInterface::class
						) ? $job_object->get_id() : false,
						'job_ref'     => is_a(
							$job_object, \Eolia\Interfaces\JobInterface::class
						) ? $job_object->get_ref() : false,
						'apply_email' => $_REQUEST['email'],
						'apply_type'  => is_a(
							$job_object, \Eolia\Interfaces\JobInterface::class
						) ? 'normal' : 'unsolicited',
					)
				)
			);

			$mail->setFrom( 'wordpress@eolia-mail.com', get_bloginfo( 'name' ) );
			$mail->addAddress(
				apply_filters(
					'eolia_filter_mail_to',
					$this->options['application_email'],
					$job_object
				)
			);     // Add a recipient

			$mail->Subject = apply_filters(
				'eolia_filter_mail_subject',
				( ! method_exists( $job_object, 'get_ref' ) || ! $job_object->get_ref() ? __(
					'Unsolicited application',
					'eolia-app'
				) : $job_object->get_ref() ),
				$job_object
			);

			$mail->Body = $message;

			if ( isset( $attachments ) && is_array( $attachments ) ) {
				foreach ( $attachments as $attachment ) {
					$mail->addAttachment( $attachment );
				}
			}

			do_action( 'eolia_action_mail', $_REQUEST, $job_object );

			$mail->send();

			foreach ( $attachments as $a ) {
				if ( file_exists( $a ) ) {
					unlink( $a );
				}
			}
		} catch ( phpmailerException $e ) {
			wp_die(
				sprintf(
					__( '<p>Error while sending your apply, please contact the administrator</p>%s', 'eolia-app' ),
					$e->errorMessage()
				)
			);
		} catch ( Exception $e ) {
			wp_die(
				sprintf(
					__( '<p>Error while sending your apply, please contact the administrator</p>%s', 'eolia-app' ),
					$e->getMessage()
				)
			);
		}

		return $mail;
	}

	public function prefix_local_email( $email ) {
		if ( ! preg_match( '/^[a-z]{2}-/', $email ) && false !== strpos( $email, 'redirection-eolia.com' ) ) {
			$prefix = substr( get_locale(), 0, 2 );
			$email  = $prefix . '-' . $email;;
		}

		return $email;
	}

	/**
	 * Set visitor origin URL
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function set_origin() {
		if ( PHP_SESSION_NONE === session_status() ) {
			@session_start();
		}
		$isUrl = false;

		if ( isset( $_GET['origine'] ) ) {
			$referer = urldecode( $_GET['origine'] );

			if ( false !== filter_var( $referer, FILTER_VALIDATE_URL ) ) {
				$referer = esc_url( $referer );
				$isUrl   = true;
			}
		} elseif ( isset( $_SERVER['HTTP_REFERER'] ) ) {
			$isUrl   = true;
			$referer = $_SERVER['HTTP_REFERER'];
		}

		if ( isset( $referer ) ) {
			$referer_host = $isUrl ? parse_url( $referer, PHP_URL_HOST ) : $referer;
			$siteurl      = parse_url( get_bloginfo( 'url' ), PHP_URL_HOST );
			if ( '' !== $referer_host && $siteurl !== $referer_host ) {
				$_SESSION['eolia-origine']     = $referer_host;
				$_SESSION['eolia-origine-url'] = $referer === $referer_host && isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : $referer;
			}
		}
	}

	/**
	 * Get viadeo profile called by Ajax
	 *
	 * @see      \Wp_Eolia_App::define_public_hooks
	 * @since    1.0.0
	 * @access   public
	 */
	public function get_viadeo_callback() {
		if ( PHP_SESSION_NONE === session_status() ) {
			session_start();
		}
		$curl = curl_init( 'https://partners.viadeo.com/oauth/token' );
		curl_setopt( $curl, CURLOPT_POST, true );
		curl_setopt(
			$curl,
			CURLOPT_POSTFIELDS,
			http_build_query(
				array(
					'client_id'     => $this->options['viadeo_id'],
					'redirect_uri'  => plugins_url( 'viadeo_response.php', __FILE__ ),
					'client_secret' => $this->options['viadeo_secret'],
					'code'          => $_SESSION['viadeo_code'],
					'grant_type'    => 'authorization_code',
					'scope'         => 'api',
				)
			)
		);
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		$auth = curl_exec( $curl );
		curl_close( $curl );
		$secret = json_decode( $auth );
		if ( ! $secret || ! isset( $secret->access_token ) ) {
			wp_send_json_error( $auth );
			die();
		}


		$header = array(
			'Accept: application/json',
			'Content-type: application/json',
			'Authorization: Bearer ' . $secret->access_token,
		);
		$ch     = curl_init();
		curl_setopt( $ch, CURLOPT_URL, 'https://partners.viadeo.com/api/member/account/applyWith' );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
		$contents = curl_exec( $ch );
		curl_close( $ch );
		$content                = json_decode( $contents )->data;
		$out                    = new stdClass();
		$out->first_name        = $content->firstName;
		$out->last_name         = $content->lastName;
		$out->gender            = ( 1 !== (int) $content->gender ) ? 'male' : 'female';
		$out->gender_val        = (int) $content->gender;
		$out->birth_date        = $content->birthDate;
		$out->location          = new stdClass();
		$out->location->country = $content->location->countryCode;
		$out->email             = $content->email;
		$out->profile_url       = $content->profileUrl;
		$out->avatar_url        = $content->avatarUrl;
		$out->headline          = $content->headline;
		$out->experiences       = array();
		foreach ( $content->positions as $pos ) {
			$myexp              = new stdClass();
			$myexp->title       = $pos->positionTitle;
			$myexp->company     = $pos->companyName;
			$myexp->description = $pos->description;
			$sd                 = DateTime::createFromFormat( 'm/Y', $pos->startDate )->format( 'Y-m-d' );
			$myexp->start_date  = $sd;
			if ( ! empty( $pos->endDate ) ) {
				$ed              = DateTime::createFromFormat( 'm/Y', $pos->endDate )->format( 'Y-m-d' );
				$myexp->end_date = $ed;
			}
			$out->experiences[] = $myexp;
		}
		$out->educations = array();
		foreach ( $content->educations as $educ ) {
			$myeduc             = new stdClass();
			$myeduc->title      = $educ->diplomaTitle;
			$myeduc->school     = $educ->schoolName;
			$myeduc->city       = $educ->town;
			$sd                 = DateTime::createFromFormat( 'Y', $educ->startYear )->format( 'Y-m-d' );
			$myeduc->start_date = $sd;
			if ( ! empty( $myeduc->endDate ) ) {
				$ed               = DateTime::createFromFormat( 'Y', $educ->endYear )->format( 'Y-m-d' );
				$myeduc->end_date = $ed;
			}
			$out->educations[] = $myeduc;
		}
		$out->languages = array();
		foreach ( $content->spokenLanguages as $lang ) {
			$mylang           = new stdClass();
			$mylang->title    = $lang->language;
			$mylang->level    = $lang->level;
			$out->languages[] = $mylang;
		}
		$out->skills = array();
		foreach ( $content->skills as $skill ) {
			$myskill        = new stdClass();
			$myskill->title = $skill->skill;
			$myskill->level = $skill->level;
			$out->skills[]  = $myskill;
		}
		$out->hobbies = array();
		foreach ( $content->hobbies as $hobby ) {
			$myhobby        = new stdClass();
			$myhobby->title = $hobby->hobby;
			$myhobby->level = $hobby->level;
			$out->hobbies[] = $myhobby;
		}
		if ( isset( $out ) ) {
			wp_send_json_success( $out );
		} else {
			wp_send_json_error( $content );
		}
		die();
	}

	/**
	 * Get LinkedIn profile called by Ajax
	 *
	 * @see      \Wp_Eolia_App::define_public_hooks
	 * @since    1.0.0
	 * @access   public
	 */
	public function get_linkedin_callback() {
		$curl = curl_init( 'https://www.linkedin.com/oauth/v2/accessToken' );
		curl_setopt( $curl, CURLOPT_POST, true );
		curl_setopt(
			$curl,
			CURLOPT_HTTPHEADER,
			array(
				'HOST: www.linkedin.com',
				'Content-Type: application/x-www-form-urlencoded',
			)
		);
		curl_setopt(
			$curl,
			CURLOPT_POSTFIELDS,
			http_build_query(
				array(
					'client_id'     => $this->options['linkedin_id'],
					'redirect_uri'  => plugins_url( 'linkedin_response.php', __FILE__ ),
					'client_secret' => $this->options['linkedin_secret'],
					'code'          => $_SESSION['linkedin_code'],
					'grant_type'    => 'authorization_code',
				)
			)
		);
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		$auth = curl_exec( $curl );
		curl_close( $curl );

		$secret = json_decode( $auth );
		if ( isset( $secret->access_token ) ) {
			$url    = 'https://api.linkedin.com/v1/people/~:(id,num-connections,picture-url,email-address,first-name,last-name,picture-urls::(original))?format=json';
			$header = array( "Authorization: Bearer $secret->access_token" );
			$ch     = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
			$contents = curl_exec( $ch );
			curl_close( $ch );
			wp_send_json_success( $contents );
		} else {
			wp_send_json_error( 'no auth' );
		}
		die();
	}

	/**
	 * Get indeed datas from indeed callback(not ajax like others)
	 *
	 * @see      \Wp_Eolia_App::define_public_hooks
	 * @since    1.0.0
	 * @access   public
	 */
	public function get_indeed_datas() {
		if ( ! isset( $_GET['id'] ) || empty( $_GET['id'] ) ) {
			$_SESSION['indeed_datas'] = 'Id is missing.';
			die();
		}

		$data       = file_get_contents( 'php://input' );
		$payloadSig = base64_encode( hash_hmac( 'sha1', $data, $this->options['indeed_secret'], true ) );
		$sig        = getallheaders()['X-Indeed-Signature'];

		if ( $sig !== $payloadSig ) {
			$_SESSION['indeed_datas'] = 'Signature mismatch.';
		} else {
			session_start();
			session_id( $_GET['id'] );
			$_SESSION['indeed_datas'] = $data;
		}

		die();
	}

	/**
	 * Send datas to textkernel and return as json
	 *
	 * @see      \Wp_Eolia_App::define_public_hooks
	 * @since    1.0.0
	 * @access   public
	 */
	public function parse_cv_callback() {
		if ( 1 === $this->options['is_textkernel'] ) {
			$parameters = array(
				'account'  => base64_decode( $this->options['textkernel_account'] ),
				'username' => base64_decode( $this->options['textkernel_login'] ),
				'password' => base64_decode( $this->options['textkernel_mdp'] ),
			);
			if ( isset( $_POST['cv_service'] ) ) {
				switch ( $_POST['cv_service'] ) {
					case 'google':
						$header = array( 'Authorization: Bearer ' . $_POST['cv_token'] );
						$url    = $_POST['cv_file'];
						$ch     = curl_init();
						curl_setopt( $ch, CURLOPT_URL, $url );
						curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
						curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
						$contents = curl_exec( $ch );
						curl_close( $ch );
						$parameters['fileName']    = $_POST['cv_title'];
						$parameters['fileContent'] = $contents;
						break;
					case 'dropbox':
						$url = $_POST['cv_file'];
						$ch  = curl_init();
						curl_setopt( $ch, CURLOPT_URL, $url );
						curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
						$contents = curl_exec( $ch );
						curl_close( $ch );
						$parameters['fileName']    = $_POST['cv_title'];
						$parameters['fileContent'] = $contents;
						break;
					case 'indeed':
						$data                      = json_decode( $_SESSION['indeed_datas'], true );
						$filename                  = $data['applicant']['resume']['file']['fileName'];
						$contents                  = base64_decode( $data['applicant']['resume']['file']['data'] );
						$parameters['fileName']    = $filename;
						$parameters['fileContent'] = $contents;
						break;
				}
			} else {
				$fh       = fopen( $_FILES['cv_file']['tmp_name'], 'r+' );
				$contents = fread( $fh, filesize( $_FILES['cv_file']['tmp_name'] ) );
				fclose( $fh );
				$parameters['fileName']    = $_FILES['cv_file']['tmp_name'];
				$parameters['fileContent'] = $contents;
			}

			if ( isset( $parameters['fileName'] ) ) {
				try {
					if ( ! class_exists( 'SoapClient' ) ) {
						wp_send_json_error(
							array( 'messages' => 'Server need Soap extension to allow textkernel parsing' ),
							500
						);
					}
					$client   = new SoapClient( $this->options['textkernel_url'] );
					$response = $client->processDocument( $parameters );
					$xml      = new SimpleXMLElement( $response->return );
					wp_send_json_success( json_encode( $xml ) );
				} catch ( Exception $e ) {
					wp_send_json_error(
						array( 'messages' => $e->getMessage(), 'parameters' => $parameters ),
						406
					);
				}
			} else {
				wp_send_json_error( __( 'No file found', 'eolia-app' ) );
			}
		} else {
			wp_send_json_error( __( 'Textkernel is not activated in your configuration', 'eolia-app' ), 406 );
		}
		die();
	}

	/**
	 * Declare Eolia Shortcodes to Wordpress
	 *
	 * @since    1.0.0
	 * @access   public
	 * @see      \Wp_Eolia_App::define_public_hooks
	 */
	public function declare_shortcodes() {
		add_shortcode( 'eolia_search', array( $this, 'display_search_engine' ) );
		add_shortcode( 'eolia_form', array( $this, 'display_application_form' ) );
		add_shortcode( 'eolia_sharelink', array( $this, 'add_sharelink' ) );
		add_shortcode( 'eolia_captcha', array( $this, 'add_recaptcha' ) );
	}

	/**
	 * Echo google reCaptcha
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array  $atts    Shortcode attributes.
	 * @param string $content SHortcode content.
	 *
	 * @return bool|string
	 */
	public function add_recaptcha( $atts, $content ) {
		wp_enqueue_script( $this->plugin_name );
		$key = isset( $atts['key'] ) ? $atts['key'] : $this->options['recaptcha_key'];
		if ( null === $key || empty( $key ) ) {
			return false;
		}
		$unique_captcha_id = uniqid( 'g-recaptcha', false );
		wp_enqueue_script(
			'g-recaptcha',
			'//www.google.com/recaptcha/api.js?onload=recaptchaCallback&render=explicit',
			array(),
			false,
			true
		);

		return <<<HTML
<div id="g-recaptcha-$unique_captcha_id" class="g-recaptcha g-recaptcha-$unique_captcha_id"
     data-sitekey="$key" data-callback="validCaptcha" data-expired-callback="invalidCaptcha"></div>
<script type="text/javascript">
  var submitBtn, recaptchaWidgets = [];
  jQuery(document).ready(function () {
    submitBtn = jQuery('#applyjob').find('input[type="submit"]');
    // Disable on load
    submitBtn.attr('disabled', true);
  });
  // use data-callback argument to enable button
  function validCaptcha() {
    submitBtn.attr('disabled', false);
  }

  function invalidCaptcha() {
    submitBtn.attr('disabled', true);
  }
  // Render recaptcha by matching g-recaptcha pattern
  var recaptchaCallback = function () {
    var forms = document.getElementsByTagName('form');
    var pattern = /(^|\s)g-recaptcha(\s|$)/;

    for (var i = 0; i < forms.length; i++) {
      var divs = forms[i].getElementsByTagName('div');

      for (var j = 0; j < divs.length; j++) {
        var sitekey = divs[j].getAttribute('data-sitekey');

        if (divs[j].className && divs[j].className.match(pattern) && sitekey) {
          var params = {
            'sitekey': sitekey,
            'theme': divs[j].getAttribute('data-theme'),
            'type': divs[j].getAttribute('data-type'),
            'size': divs[j].getAttribute('data-size'),
            'tabindex': divs[j].getAttribute('data-tabindex')
          };

          var callback = divs[j].getAttribute('data-callback');

          if (callback && 'function' === typeof window[callback]) {
            params['callback'] = window[callback];
          }

          var expired_callback = divs[j].getAttribute('data-expired-callback');

          if (expired_callback && 'function' === typeof window[expired_callback]) {
            params['expired-callback'] = window[expired_callback];
          }

          var widget_id = grecaptcha.render(divs[j], params);
          recaptchaWidgets.push(widget_id);
          break;
        }
      }
    }
  }
</script>
HTML;
	}

	/**
	 * Echo sharing items(Facebook, Twitter, Google+, Viadeo, LinkedIn)
	 *
	 * @since    1.0.0
	 * @access   public
	 * @see      \Wp_Eolia_App_Public::declare_shortcodes
	 *
	 * @param array  $atts    Shortcode attributes.
	 * @param string $content Shortcode content.
	 *
	 */
	public function add_sharelink( $atts, $content ) {
		$output = '';
		$pos    = '';
		if ( isset( $atts['position'] ) ) {
			$pos = $atts['position'];
		}
		if ( 1 === $this->options['is_share_btn'] && ( $pos === $this->options['share_btn_pos'] || 'both' === $this->options['share_btn_pos'] ) ) {
			wp_enqueue_style( 'font-awesome' );
			$output .= '
            <ul class="share-social-menu">
                <li class="share_label">' . apply_filters(
					'eolia_filter_share_btn_label',
					__( 'Partagez :', 'eolia-app' )
				) . '</li>
                <li><a href="#" title="' . sprintf( __( 'Share on %s', 'eolia-app' ), 'Facebook' ) . '" data-ga="Social::Share::Facebook" class="facebook_link"><span class="fa fa-fw fa-facebook"></span></a></li>
                <li><a href="#" title="' . sprintf( __( 'Share on %s', 'eolia-app' ), 'Twitter' ) . '" data-ga="Social::Share::Twitter" class="twitter_link"><span class="fa fa-fw fa-twitter"></span></a></li>
                <li><a href="#" title="' . sprintf( __( 'Share on %s', 'eolia-app' ), 'Google+' ) . '" data-ga="Social::Share::Google+" class="googleplus_link"><span class="fa fa-fw fa-google-plus"></span></a></li>
                <li><a href="#" title="' . sprintf( __( 'Share on %s', 'eolia-app' ), 'Viadeo' ) . '" data-ga="Social::Share::Viadeo" class="viadeo_link"><span class="fa fa-fw fa-viadeo"></span></a></li>
                <li><a href="#" title="' . sprintf( __( 'Share on %s', 'eolia-app' ), 'LinkedIn' ) . '" data-ga="Social::Share::LinkedIn" class="linkedin_link"><span class="fa fa-fw fa-linkedin"></span></a></li>
            </ul>';
		}

		echo $output;
	}

	/**
	 * Show the search engine template
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @throws \Exception Return exception if no cireteria selected.
	 */
	public function display_search_engine() {
		global $results;
		wp_enqueue_style( 'eolia-app' );
		wp_enqueue_script( 'eolia-app' );

		ob_start();
		if ( ! locate_template( 'single-jobsearch.php', true ) ) {
			require_once dirname( __DIR__ ) . '/public/partials/single-jobsearch.php';
		}
		$content = ob_get_contents();
		ob_end_clean();

		return $content;

	}

	/**
	 * Return value from $_REQUEST, return default value as fallback
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param string $param   The param identifier.
	 * @param string $default The default value fallback.
	 *
	 * @return string
	 */
	public
	function get_value(
		$param,
		$default
	) {
		return isset( $_REQUEST[ $param ] ) && '' !== $_REQUEST[ $param ] ? esc_html( $_REQUEST[ $param ] ) : $default;
	}

	/**
	 * Return results to ajax caller
	 *
	 * @since    1.0.0
	 * @access   public
	 * @see      \Wp_Eolia_App::define_public_hooks
	 */
	public
	function ajax_get_results_callback() {
		static $options;
		if ( null === $options ) {
			$options = get_option( 'eolia-app' );
		}

		global $results;
		$results = array();

		$orderby = $options['res_orderby'];
		$order   = $options['res_order'];
		$results = array();

		$args                         = array(
			'post_type' => 'job',
			'showposts' => - 1,
			'meta_key'  => $orderby,
			'order'     => $order,
			'orderby'   => 'meta_value',
		);
		$args['meta_query']['job_id'] = array(
			'key'     => 'job_id',
			'value'   => $_POST['ids'],
			'compare' => 'IN',
		);

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$group                             = get_post_meta( get_the_ID(), $options['res_main_category'], true );
				$results[ $group ][ get_the_ID() ] = get_post();
			}
		}
		wp_reset_query();

		if ( ! locate_template( 'single-results.php', true ) ) {
			require_once dirname( __DIR__ ) . '/public/partials/single-results.php';
		}

		die();
	}

	/**
	 * Return Application form
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array $atts The Job ID to show form for.
	 *
	 */
	public
	function display_application_form(
		$atts
	) {
		wp_enqueue_style( 'eolia-app' );
		wp_enqueue_script( 'eolia-app' );
		$job  = null;
		$atts = shortcode_atts( array( 'job_id' => null ), $atts );
		if ( ! empty( $atts['job_id'] ) ) {
			$job = $atts['job_id'];
		}

		ob_start();
		echo '<div class="page-header">';
		echo '<h1 class="page-title">' . get_the_title() . '</h1>';
		echo '</div>';
		$job = new JobController( $job );
		do_action( 'eolia_action_before_form', $job );
		echo $job->render_form();
		do_action( 'eolia_action_after_form', $job );
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}
