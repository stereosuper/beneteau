<?php
/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author     Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date       2017
 * @copyright  Eolia Software (http://www.eolia-consulting.com)
 * @licence    GNU
 *
 * @file       FieldInterface.php
 * @package    eolia-app
 * @version    1.0.0
 */

/**
 * Created by PhpStorm.
 * User: eolia
 * Date: 13/04/2017
 * Time: 19:49
 */

namespace Eolia\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

/**
 * Interface FieldInterface
 * @package Eolia\Interfaces
 */
interface FieldInterface {


	/**
	 * FieldInterface constructor.
	 *
	 * @param null   $field_id
	 * @param string $type
	 */
	public function __construct( $field_id = null, $type = 'offer_fields' );

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function set_id( $id );

	/**
	 * @return mixed
	 */
	public function get_id();

	/**
	 * @param $values
	 *
	 * @return mixed
	 */
	public function set_values( $values );

	/**
	 * @return mixed
	 */
	public function get_values();

	/**
	 * @param $type
	 *
	 * @return mixed
	 */
	public function set_type( $type );

	/**
	 * @return mixed
	 */
	public function get_type();

	/**
	 * @return mixed
	 */
	public function get_value();

	/**
	 * @param $component
	 *
	 * @return mixed
	 */
	public function set_component( $component );

	/**
	 * @return mixed
	 */
	public function get_component();

	/**
	 * @param $label
	 *
	 * @return mixed
	 */
	public function set_label( $label );

	/**
	 * @return mixed
	 */
	public function get_label();

	/**
	 * @param $textkernel_path
	 *
	 * @return mixed
	 */
	public function set_textkernalPath( $textkernel_path );

	/**
	 * @return mixed
	 */
	public function get_textkernalPath();

	/**
	 * @param $parent
	 *
	 * @return mixed
	 */
	public function set_parentId( $parent );

	/**
	 * @return mixed
	 */
	public function get_parentId();

	/**
	 * @param $required
	 *
	 * @return mixed
	 */
	public function set_required( $required );

	/**
	 * @return mixed
	 */
	public function get_required();

}