<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
} ?>
<form method="post" action="options.php">
	<?php
	settings_fields( $this->plugin_name );
	do_settings_sections( $this->plugin_name );
	$options = get_option( 'eolia-app' );
	$fields  = eolia_get_fields();
	?>
	<table class="form-table">
		<tbody>
		<tr>
			<th class="row">
                <span><?php _ex( 'Show social share buttons', 'admin social', $this->plugin_name ); ?>
	                :</span>
			</th>
			<td>
				<label for="<?php echo $this->plugin_name; ?>-is_share_btn1">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-is_share_btn1"
					       name="<?php echo $this->plugin_name; ?>[is_share_btn]"
					       value="1" <?php echo ( $options['is_share_btn'] == 1 ) ? 'checked="checked"' : ''; ?> /><?= __(
						'Yes'
					); ?>
				</label>
				<label for="<?php echo $this->plugin_name; ?>-is_share_btn0">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-is_share_btn0"
					       name="<?php echo $this->plugin_name; ?>[is_share_btn]"
					       value="0" <?php echo ( $options['is_share_btn'] == 0 ) ? 'checked="checked"' : ''; ?> /><?= __(
						'No'
					); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th class="row">
				<span><?php _ex( 'Show social share buttons', 'admin social', $this->plugin_name ); ?>:</span>
			</th>
			<td>
				<label for="<?php echo $this->plugin_name; ?>-share_btn_pos0">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-share_btn_pos0"
					       name="<?php echo $this->plugin_name; ?>[share_btn_pos]"
					       value="top" <?php echo ( $options['share_btn_pos'] == 'top' ) ? 'checked="checked"' : ''; ?> /><?php _ex(
						'Show before', 'admin social', $this->plugin_name
					); ?> /
				</label>
				<label for="<?php echo $this->plugin_name; ?>-share_btn_pos1">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-share_btn_pos1"
					       name="<?php echo $this->plugin_name; ?>[share_btn_pos]"
					       value="bottom" <?php echo ( $options['share_btn_pos'] == 'bottom' ) ? 'checked="checked"' : ''; ?> /><?php _ex(
						'Show afters', 'admin social', $this->plugin_name
					); ?> /
				</label>
				<label for="<?php echo $this->plugin_name; ?>-share_btn_pos2">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-share_btn_pos2"
					       name="<?php echo $this->plugin_name; ?>[share_btn_pos]"
					       value="both" <?php echo ( $options['share_btn_pos'] == 'both' ) ? 'checked="checked"' : ''; ?> /><?php _ex(
						'Show before AND after', 'admin social', $this->plugin_name
					); ?>
				</label>
			</td>
		</tr>
		</tbody>
	</table>
	<hr/>
	<h3><?= _x( 'Offer page builder', 'admin form builder heading', $this->plugin_name ) ?></h3>


	<div id="offer_layout_component">

		<div class="available_fields_area">
			<h4><?= _x( 'Available fields', 'admin form builder heading', $this->plugin_name ) ?></h4>
			<ul class="availables_fields">
				<li class="field multiple_field" data-type="custom" data-component="title">
					<span class="field_label"> <span class="fa fa-fw fa-text-height"></span><?= _x(
							'Title', 'admin form builder', $this->plugin_name
						) ?></span>
					<input type="button" data-id="custom_title" class="add_field button button-small"
					       value="<?= _x( 'Add to form', 'admin form builder button', $this->plugin_name ) ?>"/>
				</li>
				<li class="field multiple_field" data-type="custom" data-component="hr">
					<span class="field_label"> <span class="fa fa-fw fa-minus"></span><?= _x(
							'Separator', 'admin form builder', $this->plugin_name
						) ?></span>
					<input type="button" data-id="custom_hr" class="add_field button button-small"
					       value="<?= _x( 'Add to form', 'admin form builder button', $this->plugin_name ) ?>"/>
				</li>
				<li class="field multiple_field" data-type="custom" data-component="text">
					<span class="field_label"> <span class="fa fa-fw fa-align-left"></span><?= _x(
							'Text', 'admin form builder', $this->plugin_name
						) ?></span>
					<input type="button" data-id="custom_text" class="add_field button button-small"
					       value="<?= _x( 'Add to form', 'admin form builder button', $this->plugin_name ) ?>"/>
				</li>
				<li class="field multiple_field" data-type="custom" data-component="navigation">
					<span class="field_label"> <span class="fa fa-fw fa-map-signs"></span><?= _x(
							'Apply/Back button', 'admin form builder', $this->plugin_name
						) ?></span>
					<input type="button" data-id="custom_navigation" class="add_field button button-small"
					       value="<?= _x( 'Add to form', 'admin form builder button', $this->plugin_name ) ?>"/>
				</li>
			</ul>
			<h4><?php _ex( 'Available fields', 'admin form builder heading', $this->plugin_name ); ?> :</h4>
			<ul class="availables_fields">
				<?php
				/** @var \Eolia\Models\FieldModel $field */
				foreach ( $fields as $field ) {
					if ( !$field->get_label()) {
						continue;
					}
					echo '
                            <li class="field" data-type="' . $field->get_type() . '" data-component="'.$field->get_component().'">
                                <span class="field_label"><span class="fa fa-fw ' . ( $field->get_type() === 'datetime' ? 'fa-calendar' : 'fa-font' ) . '"></span>' . stripslashes(
							$field->get_label()
						) . '</span>
                                <input type="button" data-id="' . $field->get_id() . '" data-type="' . $field->get_type() . '" data-component="'.$field->get_component().'" class="add_field button button-small" value="' . _x( 'Add to form',
							'admin form builder button', $this->plugin_name ) . '" title="' . _x( 'Click to add',
							'admin form builder placeholder' ) . '" />
                            </li>
                        ';
				}
				?>
			</ul>
		</div>

		<div class="selected_fields_area">
			<h4><?= _x( 'Selected form field', 'admin form builder heading', $this->plugin_name ) ?></h4>
			<p><?= _x(
					'Drag and drop to reorder the table rows', 'admin form builder subheading', $this->plugin_name
				) ?></p>
			<table id="selected_fields" class="widefat sortable">
				<thead>
				<tr>
					<th><?= _x( 'Title', 'admin form builder table-header', $this->plugin_name ) ?> <span
							class="fa fa-fw fa-info-circle"
							title="<?= _x(
								'Leave empty to use default', 'admin form builder table-header tooltip',
								$this->plugin_name
							) ?>"></span></th>
					<th><?= _x( 'Microdata type', 'admin form builder table-header', $this->plugin_name ) ?></th>
					<th><?= _x( 'Before/After text', 'admin form builder table-header', $this->plugin_name ) ?></th>
					<th><?= _x( 'Show as', 'admin form builder table-header', $this->plugin_name ) ?></th>
					<th></th>
				</tr>
				</thead>
				<tr class="none">
					<td><em><?= _x( 'No field selected', 'admin form builder', $this->plugin_name ) ?></em></td>
				</tr>
			</table>
			<script type="text/json"><?php
				$selected_fields = json_decode( $options['offer_layout'] );
				if ( $selected_fields ) {
					echo $options['offer_layout'];
				}
				?></script>
		</div>

	</div>
	<input type="hidden" id="<?php echo $this->plugin_name; ?>-offer_layout"
	       name="<?php echo $this->plugin_name; ?>[offer_layout]" value=""/>
	<hr/>
	<?php submit_button(); ?>
</form>