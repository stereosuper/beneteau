<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
} ?>
<form method="post" action="options.php">
	<?php
	settings_fields( 'eolia-app' );
	do_settings_sections( 'eolia-app' );
	$options = get_option( 'eolia-app' );
	$fields  = eolia_get_fields( 'offer_fields' );
	?>
	<table class="form-table">
		<tbody>
		<tr>
			<th class="row">
				<span><?= _x( 'Search by keywords', 'admin search settings label', 'eolia-app' ) ?></span>
			</th>
			<td>
				<label for="eolia-app-keywordsearch1">
					<input type="radio" id="eolia-app-keywordsearch1"
					       name="eolia-app[keywordsearch]"
					       value="1" <?php echo ( true == $options['keywordsearch'] ) ? 'checked' : null; ?> /><?php _e( 'Yes' ); ?>
				</label>
				<label for="eolia-app-keywordsearch0">
					<input type="radio" id="eolia-app-keywordsearch0"
					       name="eolia-app[keywordsearch]"
					       value="0" <?php echo ( false == $options['keywordsearch'] ) ? 'checked' : null; ?> /><?php _e( 'No' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th class="row">
				<span><?= _x(
						'Job offer geolocation',
						'admin search settings label',
						'eolia-app'
					) ?></span>
			</th>
			<td>
				<label for="eolia-app-geoloc1">
					<input type="radio" id="eolia-app-geoloc1"
					       name="eolia-app[geoloc]"
					       value="1" <?php echo ( true == $options['geoloc'] ) ? 'checked="checked"' : ''; ?> /><?= __(
						'Yes'
					); ?>
				</label>
				<label for="eolia-app-geoloc0">
					<input type="radio" id="eolia-app-geoloc0"
					       name="eolia-app[geoloc]"
					       value="0" <?php echo ( false == $options['geoloc'] ) ? 'checked="checked"' : ''; ?> /><?= __(
						'No'
					); ?>
				</label>
			</td>
		</tr>
		<tr class="if_geoloc <?php echo ( false == $options['geoloc'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row">
				<label for="eolia-app-gmap_key">
					<span><?= _x( 'GoogleMap Api key', 'admin search settings label', 'eolia-app' ) ?></span>
				</label>
			</th>
			<td>
				<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="regular-text"
				       placeholder="<?= _x( 'API Key', 'admin search settings placeholder', 'eolia-app' ) ?>"
				       id="eolia-app-gmap_key"
				       name="eolia-app[gmap_key]" value="<?php echo $options['gmap_key']; ?>"/>
			</td>
		</tr>
		<tr class="if_geoloc <?php echo ( false == $options['geoloc'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row">
				<label for="eolia-app-gmap_autozoom">
					<span><?= _x( 'Googlemap Autozoom/Autocenter', 'admin search settings label', 'eolia-app' ) ?></span>
				</label>
			</th>
			<td>
				<input type="checkbox" class="regular-text"
				       id="eolia-app-gmap_autozoom"
				       name="eolia-app[gmap_autozoom]" value="1" <?php echo false != $options['gmap_autozoom'] ? 'checked' : null; ?>/>
			</td>
		</tr>
		<tr class="if_geoloc <?php echo ( false == $options['geoloc'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row">
				<label for="eolia-app-gmap_lat">
					<span><?= _x( 'GoogleMap Default Latitude', 'admin search settings label', 'eolia-app' ) ?></span>
				</label>
			</th>
			<td>
				<input type="text" class="regular-text"
				       placeholder="<?= _x( 'Default Latitude', 'admin search settings placeholder', 'eolia-app' ) ?>"
				       id="eolia-app-gmap_lat"
				       name="eolia-app[gmap_lat]" value="<?php echo $options['gmap_lat']; ?>"/>
			</td>
		</tr>
		<tr class="if_geoloc <?php echo ( false == $options['geoloc'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row">
				<label for="eolia-app-gmap_lng">
					<span><?= _x( 'GoogleMap Default Longitude', 'admin search settings label', 'eolia-app' ) ?></span>
				</label>
			</th>
			<td>
				<input type="text" class="regular-text"
				       placeholder="<?= _x( 'Default Longitude', 'admin search settings placeholder', 'eolia-app' ) ?>"
				       id="eolia-app-gmap_lng"
				       name="eolia-app[gmap_lng]" value="<?php echo $options['gmap_lng']; ?>"/>
			</td>
		</tr>
		<tr class="if_geoloc <?php echo ( false == $options['geoloc'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row">
				<label for="eolia-app-gmap_lng">
					<span><?= _x( 'GoogleMap Default Zoom', 'admin search settings label', 'eolia-app' ) ?></span>
				</label>
			</th>
			<td>
				<input type="text" class="regular-text"
				       placeholder="<?= _x( 'Default Zoom', 'admin search settings placeholder', 'eolia-app' ) ?>"
				       id="eolia-app-gmap_zoom"
				       name="eolia-app[gmap_zoom]" value="<?php echo $options['gmap_zoom']; ?>"/>
			</td>
		</tr>
		<tr class="if_geoloc <?php echo ( false == $options['geoloc'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row">
				<label for="eolia-app-custom_form_param">
					<span><?= _x( 'GoogleMap markers', 'admin search settings label', 'eolia-app' ) ?></span>
				</label>
			</th>
			<td>
				<div class="marker-preview-wrapper">
					<img class="marker_preview" src="<?php echo wp_get_attachment_url( $options['marker_img'] ); ?>"
					     style="height:40px;vertical-align: top;"/>
					&nbsp; <input type="button" class="upload_img_button button"
					              data-binding="eolia-app[marker_img]"
					              value="<?php echo esc_attr_x( 'Select icon',
						              'admin search settings',
						              'eolia-app' ); ?>"/>
					<input type="hidden" name="eolia-app[marker_img]" class="img_attachment_id"
					       value="<?php echo $options['marker_img']; ?>">
				</div>
			</td>
		</tr>
		<tr class="if_geoloc <?php echo ( false == $options['geoloc'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row">
				<label for="eolia-app-custom_form_param">
					<span><?= sprintf(
							_x( 'Level %d Group Marker', 'admin settings search googlemap', 'eolia-app' ),
							1
						) ?> :</span>
				</label>
			</th>
			<td>
				<div class="marker-preview-wrapper">
					<img class="marker_preview"
					     src="<?php echo wp_get_attachment_url( $options['marker_level1_img'] ); ?>"
					     style="height:40px;vertical-align: top;"/>
					&nbsp; <input type="button" class="upload_img_button button"
					              data-binding="eolia-app[marker_level1_img]"
					              value="<?php echo esc_attr_x( 'Select icon',
						              'admin search settings',
						              'eolia-app' ); ?>"/>
					<input type="hidden" name="eolia-app[marker_level1_img]"
					       class="img_attachment_id" value="<?php echo $options['marker_level1_img']; ?>">
				</div>
			</td>
		</tr>
		<tr class="if_geoloc <?php echo ( false == $options['geoloc'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row">
				<label for="eolia-app-custom_form_param">
					<span><?= sprintf(
							_x( 'Level %d Group Marker', 'admin settings search googlemap', 'eolia-app' ),
							2
						) ?> :</span>
				</label>
			</th>
			<td>
				<div class="marker-preview-wrapper">
					<img class="marker_preview"
					     src="<?php echo wp_get_attachment_url( $options['marker_level2_img'] ); ?>"
					     style="height:40px;vertical-align: top;"/>
					&nbsp; <input type="button" class="upload_img_button button"
					              data-binding="eolia-app[marker_level2_img]"
					              value="<?php echo esc_attr_x( 'Select icon',
						              'admin search settings',
						              'eolia-app' ); ?>"/>
					<input type="hidden" name="eolia-app[marker_level2_img]"
					       class="img_attachment_id" value="<?php echo $options['marker_level2_img']; ?>">
				</div>
			</td>
		</tr>
		<tr class="if_geoloc <?php echo ( false == $options['geoloc'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row">
				<label for="eolia-app-custom_form_param">
					<span><?= sprintf(
							_x( 'Level %d Group Marker', 'admin settings search googlemap', 'eolia-app' ),
							3
						) ?> :</span>
				</label>
			</th>
			<td>
				<div class="marker-preview-wrapper">
					<img class="marker_preview"
					     src="<?php echo wp_get_attachment_url( $options['marker_level3_img'] ); ?>"
					     style="height:40px;vertical-align: top;"/>
					&nbsp; <input type="button" class="upload_img_button button"
					              data-binding="eolia-app[marker_level3_img]"
					              value="<?php echo esc_attr_x( 'Select icon',
						              'admin search settings',
						              'eolia-app' ); ?>"/>
					<input type="hidden" name="eolia-app[marker_level3_img]"
					       class="img_attachment_id" value="<?php echo $options['marker_level3_img']; ?>">
				</div>
			</td>
		</tr>
		<tr class="if_geoloc <?php echo ( false == $options['geoloc'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row">
				<label for="eolia-app-custom_form_param">
					<span><?= sprintf(
							_x( 'Level %d Group Marker', 'admin settings search googlemap', 'eolia-app' ),
							4
						) ?> :</span>
				</label>
			</th>
			<td>
				<div class="marker-preview-wrapper">
					<img class="marker_preview"
					     src="<?php echo wp_get_attachment_url( $options['marker_level4_img'] ); ?>"
					     style="height:40px;vertical-align: top;"/>
					&nbsp; <input type="button" class="upload_img_button button"
					              data-binding="eolia-app[marker_level4_img]"
					              value="<?php echo esc_attr_x( 'Select icon',
						              'admin search settings',
						              'eolia-app' ); ?>"/>
					<input type="hidden" name="eolia-app[marker_level4_img]"
					       class="img_attachment_id" value="<?php echo $options['marker_level4_img']; ?>">
				</div>
			</td>
		</tr>
		<tr class="if_geoloc <?php echo ( false == $options['geoloc'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row">
				<label for="eolia-app-custom_form_param">
					<span><?= sprintf(
							_x( 'Level %d Group Marker', 'admin settings search googlemap', 'eolia-app' ),
							5
						) ?> :</span>
				</label>
			</th>
			<td>
				<div class="marker-preview-wrapper">
					<img class="marker_preview"
					     src="<?php echo wp_get_attachment_url( $options['marker_level5_img'] ); ?>"
					     style="height:40px;vertical-align: top;"/>
					&nbsp; <input type="button" class="upload_img_button button"
					              data-binding="eolia-app[marker_level5_img]"
					              value="<?php echo esc_attr_x( 'Select icon',
						              'admin search settings',
						              'eolia-app' ); ?>"/>
					<input type="hidden" name="eolia-app[marker_level5_img]"
					       class="img_attachment_id" value="<?php echo $options['marker_level5_img']; ?>">
				</div>
			</td>
		</tr>
		</tbody>
	</table>
	<hr/>
	<h3><?= _x( 'Search Engine criterias', 'admin search settings heading', 'eolia-app' ) ?></h3>
	<div id="search_criterias_component">
		<div class="wrap">
			<div class="col-right">
				<div class="selected_fields_area">
					<h4><?= _x( 'Search Engine criterias', 'admin search settings heading', 'eolia-app' ) ?>
						:</h4>
					<p><?= _x(
							'Drag and drop to reorder the table rows',
							'admin search settings subheading',
							'eolia-app'
						) ?></p>
					<ul id="selected_fields" class="sortable">
						<li class="none">
							<em><?= _x( 'No fields selected', 'admin search settings subheading', 'eolia-app' ) ?></em>
						</li>
					</ul>
					<script type="text/json">
                        <?php
						$selected_fields = json_decode( $options['search_criteria'] );
						if ( $selected_fields ) {
							echo $options['search_criteria'];
						}
						?>

					</script>
				</div>
			</div>
			<div class="col-left">
				<div class="available_fields_area">
					<h4><?= _x( 'Available fields', 'admin search settings heading', 'eolia-app' ) ?> :</h4>
					<ul id="availables_fields">
						<?php
						$counter_fields = 0;
						/** @var \Eolia\Models\FieldModel $field */
						foreach ( $fields as $field ) {
							if ( 'select' === $field->get_component() && $field->get_label() ) {
								$counter_fields ++;
								echo '<li class="field ' . ( ( $counter_fields % 2 === 0 ) ? 'field_mod' : '' ) . '"><span class="field_label">&raquo; ' . $field->get_label();
								if ( $field->get_parentId() && array_key_exists( $field->get_parentId(), $fields ) ) {
									/** @var \Eolia\Models\FieldModel $parent */
									$parent = $fields[ $field->get_parentId() ];
									echo ' <em>[parent : ' . $parent->get_label() . ']</em>';
								}
								echo '</span><input type="button" data-id="' . $field->get_id() . '" class="add_field button button-small" value="' . esc_attr_x( 'Add to criteria',
										'admin search settings button',
										'eolia-app' ) . '" title="' . esc_attr_x( 'Click to add',
										'admin form builder placeholder' ) . '"/></li>';
							}
						}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="eolia-app-search_criteria"
	       name="eolia-app[search_criteria]" value=""/>
	<hr/>
	<?php submit_button(); ?>
</form>