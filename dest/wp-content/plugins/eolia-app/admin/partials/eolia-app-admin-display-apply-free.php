<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

use Eolia\Controllers\FieldController;

$options            = get_option( 'eolia-app' );
$application_fields = eolia_get_fields( 'application_fields' );
?>
<form method="post" action="options.php">
	<?php
	settings_fields( $this->plugin_name );
	do_settings_sections( $this->plugin_name );
	?>
	<div id="application_layout_component">

		<div class="available_fields_area">
			<h4><?= _x( 'Available general fields', 'admin form builder heading', $this->plugin_name ) ?></h4>
			<ul class="availables_fields">
				<li class="field multiple_field" data-type="custom" data-component="title">
					<span class="field_label"> <span class="fa fa-fw fa-text-height"></span><?= _x(
							'Title',
							'admin form builder',
							$this->plugin_name
						) ?></span>
					<input type="button" data-id="custom_title" class="add_field button button-small"
					       value="<?= _x( 'Add to form', 'admin form builder button', $this->plugin_name ); ?>"
					       title="<?= _x( 'Click to add', 'admin form builder placeholder', $this->plugin_name ) ?>"/>
				</li>
				<li class="field multiple_field" data-type="custom" data-component="hr">
					<span class="field_label"> <span class="fa fa-fw fa-minus"></span><?= _x(
							'Separator',
							'admin form builder',
							$this->plugin_name
						) ?></span>
					<input type="button" data-id="custom_hr" class="add_field button button-small"
					       value="<?= _x( 'Add to form', 'admin form builder button', $this->plugin_name ); ?>"
					       title="<?= _x( 'Click to add', 'admin form builder placeholder', $this->plugin_name ) ?>"/>
				</li>
				<li class="field multiple_field" data-type="custom" data-component="text">
					<span class="field_label"> <span class="fa fa-fw fa-align-left"></span><?= _x(
							'Text',
							'admin form builder',
							$this->plugin_name
						) ?></span>
					<input type="button" data-id="custom_text" class="add_field button button-small"
					       value="<?= _x( 'Add to form', 'admin form builder button', $this->plugin_name ); ?>"
					       title="<?= _x( 'Click to add', 'admin form builder placeholder', $this->plugin_name ) ?>"/>
				</li>
			</ul>
			<h4><?= _x( 'Available apply fields', 'admin form builder heading', $this->plugin_name ) ?></h4>
			<ul class="availables_fields">
				<?php
				/** @var FieldController $field */
				foreach ( $application_fields as $field ) {
					if ( ! empty( $field->get_label() ) ) {
						echo '<li class="field" data-type="' . $field->get_type(
							) . '" data-component="' . $field->get_component() . '"><span class="field_label">';
						switch ( $field->get_component() ) {
							case 'text':
								echo '<span class="fa fa-fw fa-i-cursor" title="' . _x(
										'Entry field',
										'admin form builder input-type',
										$this->plugin_name
									) . '"></span>';
								break;
							case 'select':
								echo '<span class="fa fa-fw fa-caret-square-o-down" title="' . _x(
										'Select field',
										'admin form builder input-type',
										$this->plugin_name
									) . '"></span>';
								break;
							case 'datepicker':
								echo '<span class="fa fa-fw fa-calendar" title="' . _x(
										'Date field',
										'admin form builder input-type',
										$this->plugin_name
									) . '"></span>';
								break;
							case 'file':
								echo '<span class="fa fa-fw fa-paperclip" title="' . _x(
										'Upload field',
										'admin form builder input-type',
										$this->plugin_name
									) . '"></span>';
								break;
						}
						echo $field->get_label();
						if ( strtolower( $field->get_label() ) !== $field->get_id() ) {
							echo ' <small>(' . $field->get_id() . ')</small>';
						}
						if ( $field->get_parentId() ) {
							$parent = new FieldController( $field->get_parentId(), 'application_fields' );
							echo ' <em>[parent : ' . $parent->get_label() . ']</em>';
						}
						echo '</span>';
						echo '<input type="button" data-id="' . $field->get_id() . '" data-type="' . $field->get_type(
							) . '" data-component="' . $field->get_component(
							) . '" class="add_field button button-small" value="' . _x(
							     'Add to form',
							     'admin form builder button', $this->plugin_name
						     ) . '" title="' . _x(
							     'Click to add',
							     'admin form builder placeholder', $this->plugin_name
						     ) . '"/>';
						echo '</li> ';
					}
				}
				?>
			</ul>
		</div>

		<div class="selected_fields_area">
			<h4><?= _x( 'Selected form field', 'admin form builder heading', $this->plugin_name ) ?></h4>
			<p><?= _x(
					'Drag and drop to reorder the table rows',
					'admin form builder subheading',
					$this->plugin_name
				) ?></p>
			<table id="selected_fields" class="widefat sortable">
				<thead>
				<tr>
					<th><?= _x( 'Field', 'admin form builder table-header', $this->plugin_name ) ?></th>
					<th><?= _x( 'Required', 'admin form builder table-header', $this->plugin_name ) ?></th>
					<th><?= _x( 'Mobile', 'admin form builder table-header', $this->plugin_name ) ?></th>
					<th><?= _x( 'Component', 'admin form builder table-header', $this->plugin_name ) ?></th>
					<th></th>
				</tr>
				</thead>
				<tr class="none">
					<td><em><?= _x( 'No field selected', 'admin form builder', $this->plugin_name ) ?></em></td>
				</tr>
			</table>
			<script type="text/json"><?php
				$selected_fields = json_decode( $options['application_layout'] );
				if ( $selected_fields ) {
					echo $options['application_layout'];
				}
				?></script>
		</div>

	</div>
	<input type="hidden" id="<?php echo $this->plugin_name; ?>-application_layout"
	       name="<?php echo $this->plugin_name; ?>[application_layout]" value=""/>

	<hr/>

	<?php submit_button(); ?>
</form>