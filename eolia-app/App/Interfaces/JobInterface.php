<?php
/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author     Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date       2017
 * @copyright  Eolia Software (http://www.eolia-consulting.com)
 * @licence    GNU
 *
 * @file       JobInterface.php
 * @package    eolia-app
 * @version    1.0.0
 */

/**
 * Created by PhpStorm.
 * User: eolia
 * Date: 13/04/2017
 * Time: 19:46
 */

namespace Eolia\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

/**
 * Interface JobInterface
 * @package Eolia\Interfaces
 */
interface JobInterface {


	/**
	 * JobInterface constructor.
	 *
	 * @param int $job
	 */
	public function __construct( $job );

	/**
	 * @return mixed
	 */
	public function get_id();

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function set_id( $id );

	/**
	 * @return mixed
	 */
	public function get_ref();

	/**
	 * @param $ref
	 *
	 * @return mixed
	 */
	public function set_ref( $ref );

	/**
	 * @return mixed
	 */
	public function get_title();

	/**
	 * @param $title
	 *
	 * @return mixed
	 */
	public function set_title( $title );

	/**
	 * @return mixed
	 */
	public function get_created();

	/**
	 * @param $created
	 *
	 * @return mixed
	 */
	public function set_created( $created );

	/**
	 * @return mixed
	 */
	public function get_category();

	/**
	 * @param $category
	 *
	 * @return mixed
	 */
	public function set_category( $category );

	/**
	 * @param bool $twoletters Return two letters code if set to true.
	 *
	 * @return mixed
	 */
	public function get_language( $twoletters = null );

	/**
	 * @param $lang
	 *
	 * @return mixed
	 */
	public function set_language( $lang );

	/**
	 * @return mixed
	 */
	public function get_business_unit();

	/**
	 * @param $business_unit
	 *
	 * @return mixed
	 */
	public function set_business_unit( $business_unit );

	/**
	 * @param $key
	 * @param $value
	 *
	 * @return mixed
	 */
	public function add_additionnal_field( $key, $value );

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public function get_additionnal_field( $key );


	/**
	 * @return int
	 */
	public function get_postId();

	/**
	 * @param int $id
	 *
	 * @return int
	 */
	public function set_postId( $id );

	/**
	 * @return array
	 */
	public function get_location();

	/**
	 * @param $lat
	 * @param $lng
	 *
	 * @return int|void
	 */
	public function set_location( $lat, $lng );

	/**
	 * @return mixed
	 */
	public function save();

	/**
	 * @return string
	 */
	public function render_view();

	/**
	 * @return string
	 */
	public function render_form();

}