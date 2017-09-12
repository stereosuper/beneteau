<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
} ?>

<form method="post" action="options.php">
	<?php
	settings_fields( $this->plugin_name );
	do_settings_sections( $this->plugin_name );
	$options = get_option( 'eolia-app' );
	?>
	<table class="form-table">
		<tbody>
		<tr>
			<th class="row">
				<label for="<?php echo $this->plugin_name; ?>-recaptcha_key">
					<span><?= __( 'reCaptcha Key', 'admin form builder label', $this->plugin_name ); ?> :</span>
				</label>
			</th>
			<td>
				<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="regular-text"
				       placeholder="<?= __(
					       'Your reCaptcha API Key',
					       'admin form builder placeholder',
					       $this->plugin_name
				       ); ?>"
				       id="<?php echo $this->plugin_name; ?>-recaptcha_key"
				       name="<?php echo $this->plugin_name; ?>[recaptcha_key]"
				       value="<?php echo $options['recaptcha_key']; ?>"/>
			</td>
		</tr>
		<tr>
			<th class="row">
				<label for="<?php echo $this->plugin_name; ?>-recaptcha_secret">
					<span><?= __( 'reCaptcha Secret', 'admin form builder label', $this->plugin_name ); ?> :</span>
				</label>
			</th>
			<td>
				<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="regular-text"
				       placeholder="<?= __(
					       'Your reCaptcha API Secret',
					       'admin form builder placeholder',
					       $this->plugin_name
				       ); ?>"
				       id="<?php echo $this->plugin_name; ?>-recaptcha_secret"
				       name="<?php echo $this->plugin_name; ?>[recaptcha_secret]"
				       value="<?php echo $options['recaptcha_secret']; ?>"/>
			</td>
		</tr>
		<tr>
			<th class="row">
				<label for="<?php echo $this->plugin_name; ?>-applyform_columns">
					<span><?= __( 'Maximum file size for uploads', 'admin form builder label', $this->plugin_name ); ?>
						:</span>
				</label>
			</th>
			<td>
				<input type="text" class="regular-text"
				       placeholder="<?= __(
					       'ex : 10 for 10Mo',
					       'admin form builder placeholder',
					       $this->plugin_name
				       ); ?>"
				       id="<?php echo $this->plugin_name; ?>-maxfilesize"
				       name="<?php echo $this->plugin_name; ?>[maxfilesize]"
				       value="<?php echo $options['maxfilesize']; ?>"/>
			</td>
		</tr>
		<tr>
			<th class="row">
				<p><span class="fa fa-fw fa-google fa-2x"></span></p>
				<span><?= __( 'Use GoogleDrive upload', 'admin form builder label', $this->plugin_name ); ?> :</span>
			</th>
			<td>
				<label for="<?php echo $this->plugin_name; ?>-is_applygoogle1">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-is_applygoogle1"
					       name="<?php echo $this->plugin_name; ?>[is_applygoogle]"
					       value="1" <?php echo ( $options['is_applygoogle'] == 1 ) ? 'checked="checked"' : ''; ?> /><?= __(
						'Yes'
					) ?>
				</label>
				<label for="<?php echo $this->plugin_name; ?>-is_applygoogle0">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-is_applygoogle0"
					       name="<?php echo $this->plugin_name; ?>[is_applygoogle]"
					       value="0" <?php echo ( false == $options['is_applygoogle'] ) ? 'checked="checked"' : ''; ?> /><?= __(
						'No'
					) ?>
				</label>
			</td>
		</tr>
		<tr class="if_is_applygoogle <?php echo ( false == $options['is_applygoogle'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row decal">
				<label for="<?php echo $this->plugin_name; ?>-google_api_key">
					<span><?php _e( 'API Key', $this->plugin_name ); ?> :</span>
				</label>
			</th>
			<td>
				<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="regular-text" placeholder="API Key"
				       id="<?php echo $this->plugin_name; ?>-google_api_key"
				       name="<?php echo $this->plugin_name; ?>[google_api_key]"
				       value="<?php echo $options['google_api_key']; ?>"/>
			</td>
		</tr>
		<tr class="if_is_applygoogle <?php echo ( false == $options['is_applygoogle'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row decal">
				<label for="<?php echo $this->plugin_name; ?>-google_id_client">
					<span><?= __( 'Client ID', 'admin form builder label', $this->plugin_name ); ?>
						:</span>
				</label>
			</th>
			<td>
				<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="regular-text"
				       placeholder="<?= __(
					       'Client ID',
					       'admin form builder placeholder',
					       $this->plugin_name
				       ); ?>"
				       id="<?php echo $this->plugin_name; ?>-google_id_client"
				       name="<?php echo $this->plugin_name; ?>[google_id_client]"
				       value="<?php echo $options['google_id_client']; ?>"/>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<hr/>
			</td>
		</tr>

		<tr>
			<th class="row">
				<p><span class="fa fa-fw fa-dropbox fa-2x"></span></p>
				<span><?= __( 'Use DropBox upload', 'admin form builder label', $this->plugin_name ); ?> :</span>
			</th>
			<td>
				<label for="<?php echo $this->plugin_name; ?>-is_applydropbox1">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-is_applydropbox1"
					       name="<?php echo $this->plugin_name; ?>[is_applydropbox]"
					       value="1" <?php echo ( true == $options['is_applydropbox'] ) ? 'checked="checked"' : ''; ?> /><?= __(
						'Yes'
					) ?>
				</label>
				<label for="<?php echo $this->plugin_name; ?>-is_applydropbox0">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-is_applydropbox0"
					       name="<?php echo $this->plugin_name; ?>[is_applydropbox]"
					       value="0" <?php echo ( false == $options['is_applydropbox'] ) ? 'checked="checked"' : ''; ?> /><?= __(
						'No'
					) ?>
				</label>
			</td>
		</tr>

		<tr class="if_is_applydropbox <?php echo ( false == $options['is_applydropbox'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row decal">
				<label for="<?php echo $this->plugin_name; ?>-dropbox_app_key">
					<span><?= __( 'App Key', 'admin form builder label', $this->plugin_name ); ?> :</span>
				</label>
			</th>
			<td>
				<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="regular-text"
				       placeholder="<?= __( 'AppId', 'admin form builder placeholder', $this->plugin_name ); ?>"
				       id="<?php echo $this->plugin_name; ?>-dropbox_app_key"
				       name="<?php echo $this->plugin_name; ?>[dropbox_app_key]"
				       value="<?php echo $options['dropbox_app_key']; ?>"/>
			</td>
		</tr>
		<tr class="if_is_applydropbox <?php echo ( false == $options['is_applydropbox'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row decal">
				<label for="<?php echo $this->plugin_name; ?>-dropbox_secret">
					<span><?= __( 'Secret', 'admin form builder label', $this->plugin_name ); ?> :</span>
				</label>
			</th>
			<td>
				<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="regular-text"
				       placeholder="<?= __( 'Secret', 'admin form builder placeholder', $this->plugin_name ); ?>"
				       id="<?php echo $this->plugin_name; ?>-dropbox_secret"
				       name="<?php echo $this->plugin_name; ?>[dropbox_secret]"
				       value="<?php echo $options['dropbox_secret']; ?>"/>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<hr/>
			</td>
		</tr>

		<tr>
			<th class="row">
				<p><span class="fa fa-fw fa-info fa-2x"></span></p>
				<span><?= sprintf(
						__( 'Use Apply with %s', 'admin form builder label', $this->plugin_name ),
						'Indeed'
					); ?> :</span>
			</th>
			<td>
				<label for="<?php echo $this->plugin_name; ?>-is_applyindeed1">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-is_applyindeed1"
					       name="<?php echo $this->plugin_name; ?>[is_applyindeed]"
					       value="1" <?php echo ( true == $options['is_applyindeed'] ) ? 'checked="checked"' : ''; ?> /><?= __(
						'Yes'
					) ?>
				</label>
				<label for="<?php echo $this->plugin_name; ?>-is_applyindeed0">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-is_applyindeed0"
					       name="<?php echo $this->plugin_name; ?>[is_applyindeed]"
					       value="0" <?php echo ( $options['is_applyindeed'] == false ) ? 'checked="checked"' : ''; ?> /><?= __(
						'No'
					) ?>
				</label>
			</td>
		</tr>

		<tr class="if_is_applyindeed <?php echo ( false == $options['is_applyindeed'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row decal">
				<label for="<?php echo $this->plugin_name; ?>-indeed_token">
					<span><?= __( 'Token', 'admin form builder label', $this->plugin_name ); ?> :</span>
				</label>
			</th>
			<td>
				<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="regular-text"
				       placeholder="<?= __( 'Token', 'admin form builder placeholder', $this->plugin_name ); ?>"
				       id="<?php echo $this->plugin_name; ?>-indeed_token"
				       name="<?php echo $this->plugin_name; ?>[indeed_token]"
				       value="<?php echo $options['indeed_token']; ?>"/>
			</td>
		</tr>
		<tr class="if_is_applyindeed <?php echo ( false == $options['is_applyindeed'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row decal">
				<label for="<?php echo $this->plugin_name; ?>-indeed_secret">
					<span><?= __( 'Secret', 'admin form builder label', $this->plugin_name ); ?> :</span>
				</label>
			</th>
			<td>
				<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="regular-text"
				       placeholder="<?= __( 'Secret', 'admin form builder placeholder', $this->plugin_name ); ?>"
				       id="<?php echo $this->plugin_name; ?>-indeed_secret"
				       name="<?php echo $this->plugin_name; ?>[indeed_secret]"
				       value="<?php echo $options['indeed_secret']; ?>"/>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<hr/>
			</td>
		</tr>

		<tr>
			<th class="row">
				<p><span class="fa fa-fw fa-viadeo fa-2x"></span></p>
				<span><?= sprintf(
						__( 'Use Apply with %s', 'admin form builder label', $this->plugin_name ),
						'Viadeo'
					); ?> :</span>
			</th>
			<td>
				<label for="<?php echo $this->plugin_name; ?>-is_applyviadeo1">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-is_applyviadeo1"
					       name="<?php echo $this->plugin_name; ?>[is_applyviadeo]"
					       value="1" <?php echo ( true == $options['is_applyviadeo'] ) ? 'checked="checked"' : ''; ?> /><?= __(
						'Yes'
					) ?>
				</label>
				<label for="<?php echo $this->plugin_name; ?>-is_applyviadeo0">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-is_applyviadeo0"
					       name="<?php echo $this->plugin_name; ?>[is_applyviadeo]"
					       value="0" <?php echo ( false == $options['is_applyviadeo'] ) ? 'checked="checked"' : ''; ?> /><?= __(
						'No'
					) ?>
				</label>
			</td>
		</tr>

		<tr class="if_is_applyviadeo <?php echo ( false == $options['is_applyviadeo'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row decal">
				<label for="<?php echo $this->plugin_name; ?>-viadeo_id">
					<span><?= __( 'Id', 'admin form builder label', $this->plugin_name ); ?> :</span>
				</label>
			</th>
			<td>
				<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="regular-text"
				       placeholder="<?= __( 'AppId', 'admin form builder placeholder', $this->plugin_name ); ?>"
				       id="<?php echo $this->plugin_name; ?>-viadeo_id"
				       name="<?php echo $this->plugin_name; ?>[viadeo_id]"
				       value="<?php echo $options['viadeo_id']; ?>"/>
			</td>
		</tr>
		<tr class="if_is_applyviadeo <?php echo ( false == $options['is_applyviadeo'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row decal">
				<label for="<?php echo $this->plugin_name; ?>-viadeo_secret">
					<span><?= __( 'Secret', 'admin form builder label', $this->plugin_name ); ?> :</span>
				</label>
			</th>
			<td>
				<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="regular-text"
				       placeholder="<?= __( 'Secret', 'admin form builder placeholder', $this->plugin_name ); ?>"
				       id="<?php echo $this->plugin_name; ?>-viadeo_secret"
				       name="<?php echo $this->plugin_name; ?>[viadeo_secret]"
				       value="<?php echo $options['viadeo_secret']; ?>"/>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<hr/>
			</td>
		</tr>

		<tr>
			<th class="row">
				<p><span class="fa fa-fw fa-linkedin fa-2x"></span></p>
				<span><?= sprintf(
						__( 'Use Apply with %s', 'admin form builder label', $this->plugin_name ),
						'LinkedIn'
					); ?> :</span>
			</th>
			<td>
				<label for="<?php echo $this->plugin_name; ?>-is_applylinkedin1">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-is_applylinkedin1"
					       name="<?php echo $this->plugin_name; ?>[is_applylinkedin]"
					       value="1" <?php echo ( $options['is_applylinkedin'] == 1 ) ? 'checked="checked"' : ''; ?> /><?= __(
						'Yes'
					) ?>
				</label>
				<label for="<?php echo $this->plugin_name; ?>-is_applylinkedin0">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-is_applylinkedin0"
					       name="<?php echo $this->plugin_name; ?>[is_applylinkedin]"
					       value="0" <?php echo ( false == $options['is_applylinkedin'] ) ? 'checked="checked"' : ''; ?> /><?= __(
						'No'
					) ?>
				</label>
			</td>
		</tr>

		<tr class="if_is_applylinkedin <?php echo ( false == $options['is_applylinkedin'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row decal">
				<label for="<?php echo $this->plugin_name; ?>-linkedin_id">
					<span><?= __( 'Id', 'admin form builder label', $this->plugin_name ); ?> :</span>
				</label>
			</th>
			<td>
				<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="regular-text"
				       placeholder="<?= __( 'AppId', 'admin form builder placeholder', $this->plugin_name ); ?>"
				       id="<?php echo $this->plugin_name; ?>-linkedin_id"
				       name="<?php echo $this->plugin_name; ?>[linkedin_id]"
				       value="<?php echo $options['linkedin_id']; ?>"/>
			</td>
		</tr>
		<tr class="if_is_applylinkedin <?php echo ( false == $options['is_applylinkedin'] ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row decal">
				<label for="<?php echo $this->plugin_name; ?>-linkedin_secret">
					<span><?= __( 'Secret', 'admin form builder label', $this->plugin_name ); ?> :</span>
				</label>
			</th>
			<td>
				<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="regular-text"
				       placeholder="<?= __( 'Secret', 'admin form builder placeholder', $this->plugin_name ); ?>"
				       id="<?php echo $this->plugin_name; ?>-linkedin_secret"
				       name="<?php echo $this->plugin_name; ?>[linkedin_secret]"
				       value="<?php echo $options['linkedin_secret']; ?>"/>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<hr/>
			</td>
		</tr>

		<tr>
			<th class="row">
				<p><span class="fa fa-fw fa-search fa-2x"></span><span class="fa fa-fw fa-cogs"
				                                                       style="margin-left:-10px;"></span></p>
				<span><?= __( 'Use TextKernel API', 'admin form builder label', $this->plugin_name ); ?> :</span>
			</th>
			<?php if ( ! class_exists( 'SoapClient' ) ) : ?>
				<td style="color: orange; font-weight: bold">
					<div class="alert alert-warning">
						<?php _e( 'You must install the "Soap" php extension on your server', 'eolia-app' ) ?>
					</div>
				</td>
			<?php endif ?>
			<td<?php if ( ! class_exists( 'SoapClient' ) ) : ?> style="display: none"<?php endif; ?>>
				<label for="<?php echo $this->plugin_name; ?>-is_textkernel1">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-is_textkernel1"
					       name="<?php echo $this->plugin_name; ?>[is_textkernel]"
					       value="1" <?php echo ( $options['is_textkernel'] == 1 && class_exists( 'SoapClient' ) ) ? 'checked="checked"' : ''; ?> /><?= __(
						'Yes'
					) ?>
				</label>
				<label for="<?php echo $this->plugin_name; ?>-is_textkernel0">
					<input type="radio" id="<?php echo $this->plugin_name; ?>-is_textkernel0"
					       name="<?php echo $this->plugin_name; ?>[is_textkernel]"
					       value="0" <?php echo ( false == $options['is_textkernel'] || ! class_exists( 'SoapClient' ) ) ? 'checked="checked"' : ''; ?> /><?= __(
						'No'
					) ?>
				</label>
			</td>
		</tr>
		<tr class="if_is_textkernel <?php echo ( false == $options['is_textkernel'] || ! class_exists( 'SoapClient' ) ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row decal">
				<label for="<?php echo $this->plugin_name; ?>-textkernel_url">
					<span><?= __( 'API Url', 'admin form builder label', $this->plugin_name ); ?> :</span>
				</label>
			</th>
			<td>
				<input type="url" class="large-text"
				       placeholder="<?= __( 'URL', 'admin form builder placeholder', $this->plugin_name ); ?>"
				       id="<?php echo $this->plugin_name; ?>-textkernel_url"
				       name="<?php echo $this->plugin_name; ?>[textkernel_url]"
				       value="<?php echo $options['textkernel_url']; ?>"/>
			</td>
		</tr>
		<tr class="if_is_textkernel <?php echo ( false == $options['is_textkernel'] || ! class_exists( 'SoapClient' ) ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row decal">
				<label for="<?php echo $this->plugin_name; ?>-textkernel_account">
					<span><?= __( 'TextKernel Account', 'admin form builder label', $this->plugin_name ); ?> :</span>
				</label>
			</th>
			<td>
				<input type="password" class="regular-text"
				       placeholder="<?= __( 'Account', 'admin form builder placeholder', $this->plugin_name ); ?>"
				       id="<?php echo $this->plugin_name; ?>-textkernel_account"
				       name="<?php echo $this->plugin_name; ?>[textkernel_account]"
				       value="<?php echo $options['textkernel_account']; ?>"/>
			</td>
		</tr>
		<tr class="if_is_textkernel <?php echo ( false == $options['is_textkernel'] || ! class_exists( 'SoapClient' ) ) ? 'wpeolia_hidden' : ''; ?>">
			<th class="row decal">
				<label for="<?php echo $this->plugin_name; ?>-textkernel_login">
					<span><?= __( 'TextKernel Login', 'admin form builder label', $this->plugin_name ); ?> :</span>
				</label>
			</th>
			<td>
				<?= __( 'Login', 'admin form builder placeholder', $this->plugin_name ); ?> : <input type="password"
				                                                                                     class="regular-text"
				                                                                                     placeholder="<?= __(
					                                                                                     'Login',
					                                                                                     'admin form builder placeholder',
					                                                                                     $this->plugin_name
				                                                                                     ); ?>"
				                                                                                     id="<?php echo $this->plugin_name; ?>-textkernel_login"
				                                                                                     name="<?php echo $this->plugin_name; ?>[textkernel_login]"
				                                                                                     value="<?php echo $options['textkernel_login']; ?>"/>
				<?= __( 'Password', 'admin form builder placeholder', $this->plugin_name ); ?> : <input
					type="password"
					class="password regular-text"
					placeholder="<?= __(
						'Password',
						'admin form builder placeholder',
						$this->plugin_name
					); ?>"
					id="<?php echo $this->plugin_name; ?>-textkernel_mdp"
					name="<?php echo $this->plugin_name; ?>[textkernel_mdp]"
					value="<?php echo $options['textkernel_mdp']; ?>"/>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<hr/>
				<h2><?= __( 'Custom form variable', 'admin form builder label', $this->plugin_name ); ?> :</h2></td>
		</tr>

		<tr>
			<th class="row">
				<label for="<?php echo $this->plugin_name; ?>-custom_form_param">
                    <span><?php _ex(
		                    'Select which field is gonna define custom form functions',
		                    'admin form builder',
		                    $this->plugin_name
	                    ); ?>
	                    :</span>
				</label>
			</th>
			<td>
				<select name="<?php echo $this->plugin_name; ?>[custom_form_param]" id="custom_form_param">
					<option value=""><?= _x(
							'Don\'t use custom forms override',
							'admin form builder',
							$this->plugin_name
						) ?></option>
					<?php /** @var \Eolia\Controllers\FieldController $field */
					foreach ( eolia_get_fields() as $field ) {
						if ( $options['custom_form_param'] == $field->get_id() ) {
							$selected = 'selected="selected"';
						} else {
							$selected = '';
						}
						echo '<option value="' . $field->get_id() . '" ' . $selected . '>' . ( ! empty( $field->get_label() ) ? $field->get_label() : $field->get_id() ) . '</option>';
					} ?>
				</select>
				<p class="help">
					<?= _x(
						'With this parameter, you can now create a PHP function in your theme like :<strong>doEoliaCustomForm<em>Value_Of_<span class="cfspan"></span></em>($atts), doEoliaCustomForm<em>Value2_Of_<span class="cfspan"></span></em>($atts)</strong>... in your theme function.php file',
						'admin form builder description',
						'eolia-app'
					) ?>
			</td>
		</tr>
		</tbody>
	</table>

	<hr/>

	<?php submit_button(); ?>
</form>