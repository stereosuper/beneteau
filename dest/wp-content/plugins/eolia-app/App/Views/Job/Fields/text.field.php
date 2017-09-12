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
 * @file       text.field.php
 * @package    demo
 * @version    1.0.0
 */

/**
 * Created by PhpStorm.
 * User: eolia
 * Date: 24/05/2017
 * Time: 09:19
 */
/** @var \Eolia\Controllers\FieldController $field */
if ( ! $field->get_value() ) {
	return;
}

$options    = $field->get_options();
$attributes = array(
	'class' => 'eolia_field eolia_field--' . $field->get_component(),
);
if ( ! isset( $options->markup ) ) {
	$options->markup = 'div';
}

?>
<div class="<?php echo $attributes['class'] ?>" data-field-id="<?php echo $field->get_id() ?>" data-field-value="<?php echo esc_attr($field->get_value()) ?>">
	<div class="eolia_field_inner eolia_field_inner--<?php echo $field->get_component() ?>">
		<<?php echo $options->markup ?>>
		<span class="eolia_field_inner_before">
		<?php echo isset( $options->before_txt ) ? $options->before_txt : null ?>
	</span>
		<span
			class="eolia_field_inner_content"<?php if ( isset( $options->microdata ) ): ?> itemprop="<?php echo $options->microdata ?>"<?php endif ?>>
		<?php echo $field->get_value() ?>
	</span>
		<span class="eolia_field_inner_after">
		<?php echo isset( $options->after_txt ) ? $options->after_txt : null ?>
	</span>
	</<?php echo $options->markup ?>>
</div>
</div>