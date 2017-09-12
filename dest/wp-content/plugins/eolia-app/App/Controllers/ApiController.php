<?php
/**
 * Created by PhpStorm.
 * User: eolia
 * Date: 27/07/2017
 * Time: 07:39
 */

namespace Eolia;

use WP_Error;

class ApiController extends \WP_REST_Posts_Controller {
	/**
	 * @inheritDoc
	 */
	public function __construct( $post_type ) {
		parent::__construct( $post_type );
		$this->meta = new \WP_REST_Post_Meta_Fields( $this->post_type );
	}


	/**
	 * @inheritDoc
	 */
	protected function get_post( $id ) {
		$post  = null;
		$error = new WP_Error( 'rest_post_invalid_id', __( 'Invalid post ID.' ), array( 'status' => 404 ) );
		if ( (int) $id <= 0 ) {
			return $error;
		}

		$args  = array(
			'post_type'  => $this->post_type,
			'showposts'  => 1,
			'meta_key'   => 'job_id',
			'meta_value' => (int) $id,
		);
		$query = new \WP_Query( $args );
		if ( $query->have_posts() ) {
			$post = $query->post;
		}
		if ( empty( $post ) || empty( $post->ID ) || $this->post_type !== $post->post_type ) {
			return $error;
		}

		return $post;
	}
}