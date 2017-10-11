<?php
/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author     Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date       2017
 * @copyright  Eolia Software (http://www.eolia-consulting.com)
 * @licence    GNU
 *
 * @file       FieldModel.php
 * @package    eolia-app
 * @version    1.0.0
 */

/**
 * Created by PhpStorm.
 * User: eolia
 * Date: 14/04/2017
 * Time: 07:45
 */

namespace Eolia\Models;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

use Eolia\Interfaces\FieldInterface;

/**
 * Class FieldModel
 *
 * @package Eolia\Models
 */
class FieldModel implements FieldInterface {


	/**
	 * @var
	 */
	public $label;
	/**
	 * @var
	 */
	protected $id;
	/**
	 * @var
	 */
	protected $type;
	/**
	 * @var
	 */
	protected $component;
	/**
	 * @var
	 */
	protected $values;

	/**
	 * @var
	 */
	protected $textkernal_path;

	/**
	 * @var
	 */
	protected $parent_id;

	/**
	 * @var
	 */
	protected $required;

	/**
	 * @var
	 */
	protected $language;
	/**
	 * @var
	 */
	protected $options;


	/**
	 * FieldModel constructor.
	 *
	 * @param null|string $field_id
	 * @param string      $type
	 *
	 * @throws \Exception
	 */
	public function __construct( $field_id = null, $type = 'offer_fields' ) {
		if ( null !== $field_id && is_string( $field_id ) ) {
			$fields = eolia_get_fields( $type );
			if ( is_array( $fields ) && array_key_exists( $field_id, $fields ) ) {
				foreach ( get_object_vars( $fields[ $field_id ] ) as $key => $value ) {
					$this->{$key} = $value;
				}
			} else {
				throw new \Exception( 'No field with the id : ' . $field_id . ' in our DB' );
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @param mixed $type
	 *
	 * @return $this
	 */
	public function set_type( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_parentId() {
		return $this->parent_id;
	}

	/**
	 * @param string|null $parent_id
	 *
	 * @return $this
	 */
	public function set_parentId( $parent_id ) {
		$this->parent_id = strtolower( $parent_id );

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_textkernalPath() {
		return $this->textkernal_path;
	}

	/**
	 * @param string|null $textkernal_path
	 *
	 * @return $this
	 */
	public function set_textkernalPath( $textkernal_path ) {
		$this->textkernal_path = $textkernal_path;

		return $this;
	}

	/**
	 * @param string|null $lang
	 *
	 * @return mixed
	 */
	public function get_label() {
		return apply_filters( "eolia_filter_form_field_{$this->get_id()}_label", $this->label, $this );
	}

	/**
	 * @param string $labels
	 *
	 * @return $this
	 */
	public function set_label( $label ) {
		$this->label = $label;

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
	 * @return $this
	 */
	public function set_id( $id ) {
		$this->id = strtolower( $id );

		return $this;
	}

	/**
	 * @return bool
	 */
	public function is_valid() {
		return $this->get_id() ? true : false;
	}

	/**
	 * @param int $id
	 *
	 * @return mixed
	 */
	public function get_value( $id = null ) {
		$values = $this->get_values();
		if ( null !== $id && false !== in_array( $this->component, array( 'select', 'selectpicker' ), true ) ) {
			if ( is_array( $values ) && array_key_exists( $id, $values ) ) {
				return $values[ $id ];
			}

			return false;
		}
		if ( ! is_string( $values ) && is_single() ) {
			return get_post_meta( get_the_ID(), $this->id, true );
		}

		return $values;
	}

	/**
	 * @param null $lang
	 *
	 * @return mixed
	 */
	public function get_values() {
		return apply_filters( "eolia_filter_form_field_{$this->get_id()}_values", $this->values, $this );
	}

	/**
	 * @param mixed $values
	 *
	 * @return $this
	 */
	public function set_values( $values ) {
		$this->values = $values;

		return $this;
	}

	/**
	 *
	 */
	public function render_form() {
		$field        = $this;
		$field_output = '';
		$template     = dirname( __DIR__ ) . '/Views/Form/Fields/' . $this->get_component() . '.input.php';
		if ( ! file_exists( $template ) ) {
			$template = dirname( __DIR__ ) . '/Views/Form/Fields/text.input.php';
		}
		ob_start();
		include $template;
		$field_output .= ob_get_contents();
		ob_end_clean();

		return apply_filters( "eolia_filter_form_field_{$this->get_id()}_html", $field_output, $field );

	}

	/**
	 * @return mixed
	 */
	public function get_component() {
		return $this->component;
	}

	/**
	 * @param mixed $component
	 *
	 * @return $this
	 */
	public function set_component( $component ) {
		$this->component = strtolower( $component );

		return $this;
	}

	public function render_view() {
		$field        = $this;
		$field_output = '';
		$template     = dirname( __DIR__ ) . '/Views/Job/Fields/' . $this->get_component() . '.field.php';
		if ( ! file_exists( $template ) ) {
			$template = dirname( __DIR__ ) . '/Views/Job/Fields/text.field.php';
		}
		ob_start();
		include $template;
		$field_output .= ob_get_contents();
		ob_end_clean();

		return apply_filters( "eolia_filter_view_field_{$this->get_id()}_html", $field_output, $field );
	}

	public function get_required() {
		return $this->required;
	}

	public function set_required( $required ) {
		$this->required = $required;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_language( $twoletters = false ) {
		if ( false !== $twoletters ) {
			return substr( $this->language, 0, 2 );
		}

		return $this->language;
	}

	/**
	 * @param mixed $language
	 *
	 * @return $this
	 */
	public function set_language( $language ) {
		switch ( $language ) {
			case 'fr':
				$language = 'fr_FR';
				break;
			case 'en':
				$language = 'en_US';
				break;
			case 'de':
				$language = 'de_DE';
				break;
		}
		$this->language = $language;


		if ( false !== $language && ! strpos( $language, '_' ) ) {
			$this->language = $language . '_' . strtoupper( $language );
		}

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_options() {
		return apply_filters( "eolia_filter_form_field_{$this->get_id()}_options", $this->options, $this );
	}

	/**
	 * @param mixed $options
	 */
	public function set_options( $options ) {
		if ( is_array( $options ) ) {
			$options = json_decode( json_encode( $options ) );
		}
		$this->options = $options;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function is_mobile() {
		return ( isset( $this->options->mobile ) && $this->options->mobile != false );
	}
}