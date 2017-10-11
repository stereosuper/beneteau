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
					<label for="<?php echo $this->plugin_name; ?>-offers_feed_url">
						<span><?php _ex( 'Job offers feed (WS)', 'admin general settings', $this->plugin_name ); ?>
							:</span>
					</label>
				</th>
				<td>
					<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="large-text"
					       id="<?php echo $this->plugin_name; ?>-offers_feed_url"
					       name="<?php echo $this->plugin_name; ?>[offers_feed_url]"
					       value="<?php echo $options['offers_feed_url']; ?>"/>
				</td>
			</tr>
			<tr>
				<th class="row">
					<label for="<?php echo $this->plugin_name; ?>-offer_fields_url">
					<span><?php _ex( 'Job offer fields (WSP)', 'admin general settings', $this->plugin_name ); ?>
						:</span>
					</label>
				</th>
				<td>
					<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="large-text"
					       id="<?php echo $this->plugin_name; ?>-offer_fields_url"
					       name="<?php echo $this->plugin_name; ?>[offer_fields_url]"
					       value="<?php echo $options['offer_fields_url']; ?>"/>
				</td>
			</tr>
			<tr>
				<th class="row">
					<label for="<?php echo $this->plugin_name; ?>-application_fields_url">
					<span><?php _ex(
							'User application fields (WSC)', 'admin general settings',
							$this->plugin_name
						); ?> :</span>
					</label>
				</th>
				<td>
					<input type="<?php echo is_admin() ? 'text' : 'password' ?>" class="large-text"
					       id="<?php echo $this->plugin_name; ?>-application_fields_url"
					       name="<?php echo $this->plugin_name; ?>[application_fields_url]"
					       value="<?php echo $options['application_fields_url']; ?>"/>
				</td>
			</tr>
			<tr>
				<th class="row">
					<label for="<?php echo $this->plugin_name; ?>-application_email">
					<span><?php _ex( 'Apply receiving mail(robot)', 'admin general settings', $this->plugin_name ); ?>
						:</span>
					</label>
				</th>
				<td>
					<input type="text" class="large-text" id="<?php echo $this->plugin_name; ?>-application_email"
					       name="<?php echo $this->plugin_name; ?>[application_email]"
					       value="<?php echo $options['application_email']; ?>"/>
				</td>
			</tr>
			<tr>
				<th class="row">
					<label for="<?php echo $this->plugin_name; ?>-thanks_offer">
					<span><?php _ex( 'Thanks page URL(Offers)', 'admin general settings', $this->plugin_name ); ?>
						:</span>
					</label>
				</th>
				<td>
					<input type="url" name="<?php echo $this->plugin_name; ?>[thanks_offer]"
					       id="<?php echo $this->plugin_name; ?>-thanks_offer"
					       value="<?php echo $options['thanks_offer'] ?>">
				</td>
			</tr>
			<tr>
				<th class="row">
					<label for="<?php echo $this->plugin_name; ?>-thanks_application">
					<span><?php _ex( 'Tanks page(Spontaneous Apply)', 'admin general settings', $this->plugin_name ); ?>
						:</span>
					</label>
				</th>
				<td>
					<input type="url" name="<?php echo $this->plugin_name; ?>[thanks_application]"
					       id="<?php echo $this->plugin_name; ?>-thanks_application"
					       value="<?php echo $options['thanks_application'] ?>">
				</td>
			</tr>
			</tbody>
		</table>
		<?php submit_button(); ?>
	</form>

<?php if ( $options['offers_feed_url'] != '' && $options['application_fields_url'] != '' && $options['offer_fields_url'] != '' ) { ?>
	<hr/>
	<h2><?= _x( 'Manual Import', 'admin heading', $this->plugin_name ) ?></h2>
	<form method="post" action="options-general.php?page=eolia-app">
		<input type="hidden" name="action" value="update_jobs"/>
		<?php submit_button( _x( 'Import now', 'admin button', $this->plugin_name ) ); ?>
	</form>

	<hr/>
	<h2><?= _x( 'Cron update URL', 'admin heading', $this->plugin_name ) ?></h2>
	<?php $url = get_bloginfo( 'url' ) . '/?action=update_jobs'; ?>
	<a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a>

	<hr/>
	<h2><?= _x( 'Eolia Shortcodes', 'admin heading', $this->plugin_name ) ?></h2>

	<label>
		<span style="font-size: 130%;font-weight: bold;margin-bottom: 15px;">
			<?= _x( 'Search Engine shortcode', 'admin shortcode', $this->plugin_name ) ?>
		</span>
		<input type="text" readonly="readonly" class="large-text selectable_shortcode" value="[eolia_search/]"/>
	</label>

	<label>
		<span style="font-size: 130%;font-weight: bold;margin-bottom: 15px;">
			<?= _x( 'Spontaneous Apply form', 'admin shortcode', $this->plugin_name ) ?>
		</span>
		<input type="text" readonly="readonly" class="large-text selectable_shortcode" value="[eolia_form/]"/>
	</label>

	<?php
}