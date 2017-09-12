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
 * @file       datepicker.input.php
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
?>
<div class="eolia_form-row eolia_form-row--<?php echo $field->get_component() ?><?php echo ! $field->is_mobile() ? ' eolia_form-row--mobile-hidden' : null ?>" data-field-id="<?php echo $field->get_id() ?>">
<div class="eolia_form-group<?php echo $field->get_required() ? ' eolia_form-group--required' : null ?>"
     data-field-id="<?php echo $field->get_id() ?>">
	<label class="eolia_field_label<?php echo $field->get_required() ? ' eolia_field_label--required' : null ?>" for="<?php echo $field->get_id() ?>">
		<?php echo $field->get_label() ?>
	</label>
	<input type="date" <?php echo FieldController::formatAttributes( $field->get_field_attributes() ) ?> <?php if(isset($_GET[$field->get_id()]) && !empty($_GET[$field->get_id()])) : ?> value="<?php echo $_GET[$field->get_id()] ?>"<?php endif ?>/>
</div>
</div>