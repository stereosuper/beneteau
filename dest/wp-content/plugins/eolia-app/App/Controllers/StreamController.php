<?php
/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author     Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date       2017
 * @copyright  Eolia Software (http://www.eolia-consulting.com)
 * @licence    GNU
 *
 * @file       StreamController.php
 * @package    eolia-app
 * @version    1.0.0
 */

/**
 * Created by PhpStorm.
 * User: eolia
 * Date: 13/04/2017
 * Time: 19:19
 */

namespace Eolia\Controllers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}
/**
 * Class Stream
 *
 * @package Eolia\Controllers
 */

/**
 * Class StreamController
 *
 * @package Eolia\Controllers
 */
class StreamController {


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
	private static $offers;
	/**
	 * @var
	 */
	private static $offer_fields;
	/**
	 * @var
	 */
	private static $application_fields;

	/**
	 * Stream constructor.
	 */
	private function __construct() {
		$this->options = get_option( 'eolia-app' );
	}

	/**
	 * @param string $type      The stream type.
	 *
	 * @param null|bool $forced If we force cache refresh.
	 *
	 * @return string
	 * @throws \ErrorException
	 */
	public function get_stream( $type, $forced = false ) {
		$options = get_option( 'eolia-app' );
		switch ( $type ) {
			case 'offers':
				if ( null === self::$offers ) {
					self::$offers = $this->load_file( $options['offers_feed_url'], $forced );
				}
				$output = self::$offers;
				break;
			case 'application_fields':
				if ( null === self::$application_fields ) {
					self::$application_fields = $this->load_file( $options['application_fields_url'], $forced );
				}
				$output = self::$application_fields;
				break;
			case 'offer_fields':
				if ( null === self::$offer_fields ) {
					self::$offer_fields = $this->load_file( $options['offer_fields_url'], $forced );
				}
				$output = self::$offer_fields;
				break;
		}

		if ( ! isset( $output ) ) {
			return false;
		}

		return $output;
	}

	/**
	 * @param  string $url     The file url to get.
	 * @param null|bool $force If we force refresh.
	 *
	 * @return bool|string
	 * @throws \ErrorException
	 *
	 */
	public function load_file( $url, $force = false ) {
		$filename   = strtok( basename( $url ), '.' ) . '.xml';
		$upload     = wp_upload_dir();
		$upload_dir = $upload['basedir'] . DIRECTORY_SEPARATOR . 'eolia-app';
		$full_path  = $upload_dir . '/' . $filename;

		if ( ! @mkdir( $upload_dir, 0700 ) && ! is_dir( $upload_dir ) ) {
			throw new \ErrorException( 'Error while creating eolia-app upload directory' );
		}

		if ( false !== $force || ! file_exists( $full_path ) ) {
			$curl = curl_init();
			curl_setopt( $curl, CURLOPT_URL, $url );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $curl, CURLOPT_HEADER, false );
			$stream = curl_exec( $curl );
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close( $curl );

			if ( ! $stream || $httpcode !== 200 ) {
				wp_die(
					sprintf(
						_x(
							'<h1>Error while updating jobs</h1><p>XML body seem to be empty (%s)</p>',
							'admin',
							'eolia-app'
						),
						$url
					),
					sprintf( _x( 'Error - %s', 'admin', 'eolia-app' ), get_bloginfo( 'name' ) )
				);
			}

			if ( ! file_put_contents( $full_path, $stream ) ) {
				wp_die(
					_x(
						'<h1>Error while updating jobs</h1><p>One or more streams do not respond to the specified addresses</p>',
						'admin',
						'eolia-app'
					),
					sprintf( _x( 'Error - %s', 'admin', 'eolia-app' ), get_bloginfo( 'name' ) ),
					array( 'response' => 404 )
				);
			}

			$output = $stream;
		} else {
			$output = file_get_contents( $full_path );
		}

		return $output;

	}

	/**
	 * @return \Eolia\Controllers\StreamController
	 */
	public static function get_instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}


}