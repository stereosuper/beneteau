<?php
/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author     Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date       2017
 * @copyright  Eolia Software (http://www.eolia-consulting.com)
 * @licence    GNU
 *
 * @file       FieldController.php
 * @package    eolia-app
 * @version    1.0.0
 */

/**
 * Created by PhpStorm.
 * User: eolia
 * Date: 13/04/2017
 * Time: 19:49
 */

namespace Eolia\Controllers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

use Eolia\EoliaWordpress;
use Eolia\Models\FieldModel;


/**
 * Class Field
 *
 * @package Eolia\Controllers
 */
class FieldController extends FieldModel {
	/**
	 * Field constructor.
	 *
	 * @param null|string $field The field ID.
	 * @param string      $type  application_fields|offer_fields.
	 *
	 */
	public function __construct( $field = null, $type = 'offer_fields' ) {
		if ( null !== $field ) {
			parent::__construct( $field, $type );
		}
	}

	/**
	 * @param array $attributes The field attributes array.
	 *
	 * @return string
	 */
	public static function formatAttributes( $attributes ) {
		$attributes = array_map(
			function ( $value, $key ) {
				return $key . '="' . $value . '"';
			},
			array_values( $attributes ),
			array_keys( $attributes )
		);

		$attributes = array_filter( $attributes );

		return implode( ' ', $attributes );
	}

	/**
	 * @return mixed
	 */
	public function get_field_attributes() {
		$attributes = array(
			'name'             => $this->get_id(),
			'id'               => $this->get_id(),
			'class'            => 'eolia_input eolia_input--' . $this->get_component(),
			'placeholder'      => esc_attr( $this->get_label() ),
			'data-placeholder' => esc_attr( $this->get_label() ),
			'data-field-id'    => $this->get_id(),
			'data-field-name'  => sanitize_title( $this->get_label() ),
		);

		$options = get_option( 'eolia-app' );

		switch ( $this->get_component() ) {
			case 'selectpicker':
				$attributes['placeholder'] = _x(
					'Choose...',
					'form select placeholder',
					'eolia-app'
				);
				$attributes['multiple']    = 'multiple';
				$attributes['name']        .= '[]';
				break;
			case 'select':
				$attributes['placeholder'] = _x(
					'Choose...',
					'form select placeholder',
					'eolia-app'
				);
				break;
			case 'checkbox':
				$attributes['name']        = $this->get_id() . '[]';
				$attributes['placeholder'] = _x(
					'Choose...',
					'form select placeholder',
					'eolia-app'
				);
				break;
			case 'radio':
				$attributes['placeholder'] = _x(
					'Choose...',
					'form select placeholder',
					'eolia-app'
				);
				break;
			case 'file':
				$attributes['placeholder']         = _x(
					'Browse...',
					'form select placeholder',
					'eolia-app'
				);
				$attributes['data-filesize']       = $options['maxfilesize'];
				$attributes['data-filesize-error'] = sprintf(
					_x( 'File size exceed %s Mo !', 'alert', 'eolia-app' ),
					$options['maxfilesize']
				);
				$attributes['accept']              = '.' . implode( ',.', EoliaWordpress::$file_extensions );
				unset( $attributes['class'] );
				break;
			case 'questions':
				$attributes['placeholder'] = _x(
					'Reply here...',
					'form select placeholder',
					'eolia-app'
				);
				$attributes['name']        .= '[]';
				$attributes['class']       .= ' eolia_input--textarea';
				break;
			case 'textarea':
				$attributes['placeholder'] = __( 'Type here...', 'eolia-app' );
				break;

		}

		if ( $this->get_parentId() ) {
			$attributes['data-parent-id'] = $this->get_parentId();
		}

		if ( $this->get_required() ) {
			$attributes['required'] = 'required';
		}

		$attributes['data-placeholder'] = $attributes['placeholder'];

		if ( ! $this->is_mobile() ) {
			if ( array_key_exists( 'class', $attributes ) ) {
				$attributes['class'] .= ' eolia_input--mobile-hidden';
			} else {
				$attributes['class'] = 'eolia_input--mobile-hidden';
			}
		}

		return apply_filters( "eolia_filter_form_field_{$this->get_id()}_attributes", $attributes, $this );
	}
}