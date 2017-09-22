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
 * @file       hr.php
 * @package    eolia-app
 * @version    1.0.0
 */

/** @var \Eolia\Controllers\FieldController $field */
$options    = $field->get_options();
$attributes = array(
	'class' => 'eolia_field eolia_field--' . $field->get_component(),
);
?>
<div class="<?php echo $attributes['class'] ?>" data-field-id="<?php echo $field->get_id() ?>">
	<div class="eolia_field_inner eolia_field_inner--hr">
		<hr/>
	</div>
</div>
