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
 * @file       navigation.field.php
 * @package    eolia-app
 * @version    1.0.0
 */

/** @var \Eolia\Controllers\FieldController $field */
$options    = $field->get_options();
$attributes = array(
	'class' => 'eolia_field eolia_field--' . $field->get_component(),
);
$job_id     = get_post_meta( get_the_ID(), 'job_id', true );
?>
<div class="<?php echo $attributes['class'] ?>" data-field-id="<?php echo $field->get_id() ?>">
	<?php if ( strpos( $options->parameters, 'R' ) !== false ): ?>
		<div class="eolia_field_inner eolia_field_inner--back-button">
			<a href="#" class="eolia_input eolia_input--button eolia_input--back-button"
			   onclick="window.history.back()">
				<?php echo apply_filters( 'eolia_filter_view_back_btn', _x( 'Back', 'apply button', 'eolia-app' ) ) ?>
			</a>
		</div>
	<?php endif ?>
	<?php if ( strpos( $options->parameters, 'P' ) !== false ): ?>
		<div class="eolia_field_inner eolia_field_inner--apply-button">
			<a href="<?php echo \Eolia\Controllers\JobController::get_apply_url( $job_id ) ?>"
			   data-ga="Offer::Access form::<?php echo $job_id . '-' . sanitize_title( get_the_title() ) ?>"
			   class="eolia_input eolia_input--button eolia_input--apply-button">
				<?php echo apply_filters( 'eolia_filter_view_apply_btn', _x( 'Apply', 'apply button', 'eolia-app' ) ) ?>
			</a>
		</div>
	<?php endif ?>
</div>
