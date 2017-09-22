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
				<span><?php _ex( 'Order by', 'admin results settings', $this->plugin_name ); ?> :</span>
			</th>
			<td>
				<select name="<?php echo $this->plugin_name; ?>[res_main_category]">
					<option value="uncategorized"><?php _ex( 'Uncategorized', 'Default category slug' ); ?></option>
					<?php /** @var \Eolia\Controllers\FieldController $field */
					foreach ( $fields as $field ) :
						if ( empty( $field->get_label() ) ) {
							continue;
						}
						?>
						<option
							value="<?= $field->get_id() ?>"<?= ( $options['res_main_category'] === $field->get_id(
						) ? ' selected="selected"' : '' ) ?>><?= trim( $field->get_label() ) ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<th class="row">
				<span><?php _ex( 'Order offers by', 'admin results settings', $this->plugin_name ); ?> :</span>
			</th>
			<td>
				<select name="<?php echo $this->plugin_name; ?>[res_orderby]">
					<?php /** @var \Eolia\Controllers\FieldController $field */
					foreach ( $fields as $field ) :
						if ( empty( $field->get_label() ) ) {
							continue;
						}
						?>
						<option
							value="<?= $field->get_id() ?>" <?= ( $options['res_orderby'] === $field->get_id(
						) ? ' selected="selected"' : '' ) ?>><?= trim( $field->get_label() ) ?></option>
					<?php endforeach; ?>
				</select>
				<select name="<?php echo $this->plugin_name; ?>[res_order]">
					<option value="asc" <?php echo ( $options['res_order'] == 'asc' ) ? 'selected="selected"' : ''; ?> >
						<?= __( 'ASC' ) ?>
					</option>
					<option
						value="desc" <?php echo ( $options['res_order'] == 'desc' ) ? 'selected="selected"' : ''; ?>>
						<?= __( 'DESC' ) ?>
					</option>
				</select>
			</td>
		</tr>
		<tr>
			<th class="row">
				<label for="<?php echo $this->plugin_name; ?>-description_field">
					<span><?= __( 'SEO Jobs description field', 'admin form builder label', $this->plugin_name ); ?>
						:</span>
				</label>
			</th>
			<td>
				<select name="<?php echo $this->plugin_name; ?>[description_field]">
					<?php /** @var \Eolia\Controllers\FieldController $field */
					foreach ( $fields as $field ) :
						if ( empty( $field->get_label() ) ) {
							continue;
						}
						?>
						<option
							value="<?= $field->get_id() ?>" <?= ( array_key_exists(
								                                      'description_field', $options
							                                      ) && $options['description_field'] === $field->get_id(
						) ? ' selected="selected"' : '' ) ?>><?= trim( $field->get_label() ) ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<th class="row">
				<label for="<?php echo $this->plugin_name; ?>-accordion_limit">
					<span><?php _ex( 'Accordeon start limit', 'admin results settings', $this->plugin_name ); ?>
						:</span>
				</label>
			</th>
			<td>
				<input type="text" class="regular-text" placeholder=""
				       id="<?php echo $this->plugin_name; ?>-accordion_limit"
				       name="<?php echo $this->plugin_name; ?>[accordion_limit]"
				       value="<?php echo $options['accordion_limit']; ?>"/>
			</td>
		</tr>

		</tbody>
	</table>
	<hr/>
	<h3><?php _ex( 'Available fields', 'admin form builder heading', $this->plugin_name ); ?> :</h3>

	<div id="result_headers_component">

		<div class="available_fields_area">
			<h4><?php _ex( 'Availables columns', 'admin results settings heading', $this->plugin_name ); ?> :</h4>
			<ul id="availables_fields">
				<?php
				foreach ( $fields as $field ) {
					if ( ! $field->get_label() ) {
						continue;
					}
					echo '
				                            <li class="field">
				                                &raquo; <span class="field_label">' . $field->get_label() . '</span>
				                                <input type="button" data-type="' . $field->get_type(
						) . '" data-id="' . $field->get_id() . '" class="add_field button button-small" value="' . _x(
						     'Add to columns',
						     'admin results settings', $this->plugin_name
					     ) . '" title="' . _x(
						     'Click to add',
						     'admin form builder placeholder'
					     ) . '" />
				                            </li>
				                        ';
				}
				?>
			</ul>
		</div>

		<div class="selected_fields_area">
			<h4><?php _ex( 'Selected columns', 'admin results settings heading', $this->plugin_name ); ?> :</h4>
			<p><?= _x(
					'Drag and drop to reorder the table rows', 'admin results settings subheading',
					$this->plugin_name
				) ?></p>
			<table id="selected_fields" class="widefat">
				<tr class="sortable">
					<td class="none"><em><?= _x(
								'No headings selected', 'admin results settings subheading',
								$this->plugin_name
							) ?></em></td>
				</tr>
			</table>
			<table class="widefat content_simulator">
				<tr>

					<td><?= _x(
							'Your offers will be displayed below', 'admin results settings subheading',
							$this->plugin_name
						) ?></td>
				</tr>
				<tr class="alternate">
					<td>Offre #1</td>
				</tr>
				<tr>
					<td>Offre #2</td>
				</tr>
				<tr class="alternate">
					<td>Offre #3</td>
				</tr>
			</table>
			<script type="text/json">
                <?php
				$selected_fields = json_decode( $options['result_headers'] );
				if ( $selected_fields ) {
					echo $options['result_headers'];
				}
				?>



















			
			</script>
		</div>

	</div>
	<input type="hidden" id="<?php echo $this->plugin_name; ?>-result_headers" style="width:100%;"
	       name="<?php echo $this->plugin_name; ?>[result_headers]" value=""/>
	<hr/>
	<?php submit_button(); ?>
</form>