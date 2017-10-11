<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author     Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date       2017
 * @copyright  Eolia Software (http://www.eolia-consulting.com)
 * @licence    GNU
 *
 * @file       selectpicker.input.php
 * @package    demo
 * @version    1.0.0
 */

use Eolia\Controllers\FieldController;

/**
 * Created by PhpStorm.
 * User: eolia
 * Date: 24/05/2017
 * Time: 19:02
 */
/** @var FieldController $field */
if ( ! $field->get_values() ) {
	return false;
}
$attributes = $field->get_field_attributes();
$attributes['class'] = $attributes['class'] . ' eolia_input--select';
?>
<div class="eolia_form-row eolia_form-row--<?php echo $field->get_component() ?><?php echo ! $field->is_mobile(
) ? ' eolia_form-row--mobile-hidden' : null ?>" data-field-id="<?php echo $field->get_id() ?>">
	<div class="eolia_form-group<?php echo $field->get_required() ? ' eolia_form-group--required' : null ?>"
	     data-field-id="<?php echo $field->get_id() ?>">
		<label class="eolia_field_label<?php echo $field->get_required() ? ' eolia_field_label--required' : null ?>"
		       for="<?php echo $field->get_id() ?>">
			<?php echo $field->get_label() ?>
		</label>
		<select <?php echo FieldController::formatAttributes( $attributes ) ?>>
			<?php foreach ( $field->get_values() as $key => $value ): ?>
				<option
					value="<?php echo $key ?>"<?php if ( array_key_exists(
					'parent_id',
					$value
				) ): ?> data-parent-id="<?php echo $value['parent_id'] ?>"<?php endif ?><?php if ( isset(
					                                                                                   $_GET[ $field->get_id(
					                                                                                   ) ]
				                                                                                   ) && in_array(
					                                                                                   $key,
					                                                                                   $_GET[ $field->get_id(
					                                                                                   ) ]
				                                                                                   ) ) : ?> selected<?php endif ?>>
					<?php echo $value['label'] ?>
				</option>
			<?php endforeach ?>
		</select>
	</div>
</div>