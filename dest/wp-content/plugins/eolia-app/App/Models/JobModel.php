<?php
/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author     Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date       2017
 * @copyright  Eolia Software (http://www.eolia-consulting.com)
 * @licence    GNU
 *
 * @file       JobModel.php
 * @package    eolia-app
 * @version    1.0.0
 */

/**
 * Created by PhpStorm.
 * User: eolia
 * Date: 14/04/2017
 * Time: 00:08
 */

namespace Eolia\Models;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

use Eolia\Interfaces\JobInterface;
use WP_Query;

/**
 * Class JobModel
 *
 * @package Eolia\Models
 */
class JobModel implements JobInterface {


	/**
	 * @var
	 */
	private $id;
	/**
	 * @var
	 */
	private $description;
	/**
	 * @var
	 */
	private $post_id;
	/**
	 * @var
	 */
	private $ref;
	/**
	 * @var
	 */
	private $title;
	/**
	 * @var
	 */
	private $client;
	/**
	 * @var
	 */
	private $created;
	/**
	 * @var
	 */
	private $modified;
	/**
	 * @var
	 */
	private $category;
	/**
	 * @var
	 */
	private $lang;
	/**
	 * @var
	 */
	private $business_unit;
	/**
	 * @var
	 */
	private $additionnal_fields;
	/**
	 * @var
	 */
	private $location;
	/**
	 * @var
	 */
	private $questions;

	/**
	 * JobModel constructor.
	 *
	 * @param integer $job
	 */
	public function __construct( $job = null ) {
		if ( isset( $job ) ) {
			$this->get_job( $job );
		}
	}

	/**
	 * @param int $id
	 *
	 * @return bool|\Eolia\Interfaces\JobInterface
	 */
	private function get_job( $id ) {
		static $options;
		if ( null === $options ) {
			$options = get_option( 'eolia-app' );
		}
		$args = array(
			'post_type'  => 'job',
			'showposts'  => 1,
			'meta_query' => array(
				array(
					'key'     => 'job_id',
					'value'   => $id,
					'compare' => '=',
				),
			),
		);

		$query = new WP_Query( $args );

		if ( ! $query->have_posts() ) {
			wp_reset_query();

			return false;
		}
		wp_reset_query();

		$location = get_post_meta( $query->post->ID, 'location', true );
		if ( isset( $location->lat, $location->lng ) ) {
			$this->set_location( $location->lat, $location->lng );
		}
		$this->set_title( $query->post->post_title )
		     ->set_description( $query->post->post_content )
		     ->set_postId( $query->post->ID )
		     ->set_id( get_post_meta( $query->post->ID, 'job_id', true ) )
		     ->set_ref( get_post_meta( $query->post->ID, 'ref', true ) )
		     ->set_client( get_post_meta( $query->post->ID, 'nomclient', true ) )
		     ->set_created( new \DateTime() )
		     ->set_language( get_post_meta( $query->post->ID, 'lang', true ) );

		if ( $questions = get_post_meta( $query->post->ID, 'questions', true ) ) {
			$this->set_questions( $questions );
		}

		foreach ( (array) get_metadata( 'post', $query->post->ID ) as $key => $value ) {
			if ( strpos( $key, 0, 1 ) === '_' ) {
				continue;
			}
			$this->add_additionnal_field( $key, get_post_meta( $query->post->ID, $key, true ) );
		}

		if ( false !== strpos( 'uncategorized', $options['res_main_category'] ) || !get_post_meta( $query->post->ID, $options['res_main_category'], true ) ) {
			$this->set_category( _x( 'Uncategorized', 'search-results', 'eolia-app' ) );
		} else {
			$this->set_category( get_post_meta( $query->post->ID, $options['res_main_category'], true ) );
		}

		return $this;
	}

	/**
	 * @param mixed $lang
	 *
	 * @return $this|mixed
	 */
	public function set_language( $lang ) {
		$this->lang = (string) $lang;

		return $this;
	}

	/**
	 * @param $key
	 * @param $value
	 *
	 * @return $this|mixed
	 */
	public function add_additionnal_field( $key, $value ) {
		$this->additionnal_fields[ $key ] = $value;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_client() {
		return $this->client;
	}

	/**
	 * @param mixed $client
	 *
	 * @return $this
	 */
	public function set_client( $client ) {
		$this->client = (string) $client;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_category() {
		return $this->category;
	}

	/**
	 * @param string $category
	 *
	 * @return $this|mixed
	 */
	public function set_category( $category ) {
		$this->category = (string) $category;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_business_unit() {
		return $this->business_unit;
	}

	/**
	 * @param mixed $business_unit
	 *
	 * @return $this|mixed
	 */
	public function set_business_unit( $business_unit ) {
		$this->business_unit = (string) $business_unit;

		return $this;
	}

	/**
	 * @return array|bool|false|\WP_Post
	 */
	public function delete() {
		if ( null === $this->get_postId() ) {
			return false;
		}

		return wp_delete_post( $this->get_postId() );
	}

	/**
	 * @return int
	 */
	public function get_postId() {
		return $this->post_id;
	}

	/**
	 * @param int $post_id
	 *
	 * @return $this|int
	 */
	public function set_postId( $post_id ) {
		$this->post_id = $post_id;

		return $this;
	}

	/**
	 * @return int|\WP_Error
	 */
	public function save() {
		$args  = array(
			'post_type'  => 'job',
			'showposts'  => 1,
			'meta_query' => array(
				array(
					'key'     => 'job_id',
					'value'   => $this->id,
					'compare' => '=',
				),
				array(
					'key'     => 'lang',
					'value'   => $this->lang,
					'compare' => '=',
				),
			),
		);
		$query = new WP_Query( $args );

		$post = array(
			'post_author'       => 1,
			'post_title'        => $this->get_title(),
			'post_name'         => sanitize_title( implode( '-',
				array( $this->get_id(), $this->get_title(), $this->get_language( true ) ) ) ),
			'post_date'         => $this->get_created()->format( 'Y-m-d H:i:s' ),
			'post_modified'     => $this->get_modified()->format( 'Y-m-d H:i:s' ),
			'post_modified_gmt' => '',
			'post_content'      => $this->get_description() ?: '',
			'post_type'         => 'job',
			'post_status'       => 'publish',
			'comment_status'    => 'closed',
			'meta_input'        => array_merge(
				(array) $this->get_additionnalFields(),
				array(
					'job_id'    => $this->get_id(),
					'ref'       => $this->get_ref(),
					'lang'      => $this->get_language(),
					'location'  => $this->get_location(),
					'questions' => $this->get_questions(),
				) ),
		);

		if ( $query->have_posts() ) {
			if ( $this->get_modified() !== $this->get_created() && $query->post->post_modified < $this->get_modified()->format( 'Y-m-d H:i:s' ) ) {
				$post    = array_merge( (array) $query->post, $post );
				$post_id = $post['ID'];
				wp_update_post( $post );
				if ( function_exists( 'pll_set_post_language' ) ) {
					pll_set_post_language( $post_id, $this->get_language( true ) );
				}
			}
		} else {
			if ( ! $post_id = wp_insert_post( $post ) ) {
				wp_die( printf( __( 'Error while saving the %d offer', 'eolia-app' ), $this->get_id() ) );
			}

			$this->set_postId( $post_id );

			if ( function_exists( 'pll_set_post_language' ) ) {
				pll_set_post_language( $post_id, $this->get_language( true ) );
			}
		}
		wp_reset_query();


		if ( ! isset( $post_id ) ) {
			return false;
		}

		// Force update modified.
		global $wpdb;
		$wpdb->query( 'UPDATE ' . $wpdb->prefix . 'posts SET post_modified = \'' . esc_sql( $this->get_modified()->format( 'Y-m-d H:i:s' ) ) . '\' WHERE ID = ' . esc_sql( $post_id ) );

		return $post_id;
	}

	/**
	 * @return mixed
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @param mixed $title
	 *
	 * @return $this|mixed
	 */
	public function set_title( $title ) {
		$this->title = (string) $title;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 *
	 * @return $this|mixed
	 */
	public function set_id( $id ) {
		$this->id = (integer) $id;

		return $this;
	}

	/**
	 * @param bool $twoletters Return two letters code if set to true.
	 *
	 * @return mixed
	 */
	public function get_language( $twoletters = false ) {
		if ( false !== $twoletters ) {
			return substr( $this->lang, 0, 2 );
		}

		return $this->lang;
	}

	/**
	 * @return \DateTime
	 */
	public function get_created() {
		return $this->created;
	}

	/**
	 * @param \DateTime|string $created
	 *
	 * @return $this|mixed
	 */
	public function set_created( $created ) {
		if ( is_string( $created ) ) {
			$created = \DateTime::createFromFormat( 'Y-m-d H:i:s', $created );
		}
		$this->created = $created;

		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function get_modified() {
		if ( null === $this->modified && null !== $this->created ) {
			return $this->created;
		}

		return $this->modified;
	}

	/**
	 * @param \DateTime|string $modified
	 *
	 * @return $this
	 */
	public function set_modified( $modified ) {
		if ( is_string( $modified ) ) {
			$modified = \DateTime::createFromFormat( 'Y-m-d H:i:s', $modified );
		}
		$this->modified = $modified;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @param mixed $description
	 *
	 * @return $this
	 */
	public function set_description( $description ) {
		$this->description = $description;

		return $this;
	}

	/**
	 * @return array|mixed
	 */
	public function get_additionnalFields() {
		return $this->additionnal_fields;
	}

	/**
	 * @param \SimpleXMLElement $fields
	 *
	 * @return $this
	 */
	public function set_additionnalFields( $fields ) {
		$this->additionnal_fields = array();
		/** @var \SimpleXMLElement $value */
		foreach ( $fields as $key => $value ) {
			if ( is_a( $value, 'SimpleXMLElement' ) ) {
				if ( ! empty( $value->__toString() ) && $value->count() === 0 ) {
					$this->additionnal_fields[ $key ] = $value->__toString();
				} elseif ( $value->count() > 1 ) {
					$this->additionnal_fields[ $key ] = (array) $value;
				}
			}
		}

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_ref() {
		return $this->ref;
	}

	/**
	 * @param mixed $ref
	 *
	 * @return $this|mixed
	 */
	public function set_ref( $ref ) {
		$this->ref = $ref;

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_location() {
		if ( ! $this->location ) {
			return $this->get_additionnal_field( 'location' );
		}

		return $this->location;
	}

	/**
	 * @param $lat
	 * @param $lng
	 *
	 * @return \Eolia\Models\JobModel
	 * @internal param mixed $location
	 */
	public function set_location( $lat, $lng ) {
		if ( empty( $lat ) || empty( $lng ) ) {
			return $this;
		}

		$this->location      = new \stdClass();
		$this->location->lat = $lat;
		$this->location->lng = $lng;

		return $this;
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public function get_additionnal_field( $key ) {
		if ( ! is_array( $this->additionnal_fields ) || ! array_key_exists( $key, $this->additionnal_fields ) ) {
			return false;
		}

		return $this->additionnal_fields[ $key ];
	}

	/**
	 * @return mixed
	 */
	public function get_questions() {
		return $this->questions;
	}

	/**
	 * @param mixed $questions
	 */
	public function set_questions( $questions ) {
		$this->questions = $questions;
	}

	/**
	 *
	 */
	public function render_view() {
		// TODO: Implement render_view() method.
	}

	/**
	 *
	 */
	public function render_form() {
		// TODO: Implement render_form() method.
	}
}