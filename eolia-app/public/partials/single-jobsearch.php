<?php
/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author     Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date       2017
 * @copyright  Eolia Software (http://www.eolia-consulting.com)
 * @licence    GNU
 *
 * @file       single-jobsearch.php
 * @package    eolia-app
 * @version    1.0.0
 */
$options = get_option( 'eolia-app' );
$fields  = eolia_get_fields();
$lang    = substr( get_locale(), 0, 2 );
$row_id  = 0;
$app     = \Eolia\EoliaWordpress::get_instance();
$results = $app->archive_query();

if ( ! $selected_fields = json_decode( $options['search_criteria'] ) ): ?>
	<div class="alert alert-warning"><?php _e( 'Missing search criteria in Eolia App admin.', 'eolia-app' ); ?></div>
	<?php return ?>
<?php endif ?>
<div class="eolia_search">
	<!-- single-jobsearch.php -->
	<form method="get" action="" name="eolia_search_engine">
		<input type="hidden" name="post_type" value="job"/>
		<?php if(is_page()) : ?>
			<input type="hidden" name="page_id" value="<?php the_ID() ?>"/>
		<?php endif ?>
		<div class="eolia_row">
			<!-- Search fields -->
			<div class="eolia_search_fields">
				<?php if ( array_key_exists( 'keywordsearch', $options ) && $options['keywordsearch'] ) : ?>
					<div class="eolia_form-row eolia_row--keywords">
						<div class="eolia_form-group eolia_form-form-group--keywords">
							<label class="eolia_field_label" for="keywords"><?php _ex( 'Keywords',
									'form search label',
									'eolia-app' ) ?></label>
							<input type="text" class="eolia_input eolia_input--text eolia_input--keywords" id="keywords"
							       name="keywords"
							       value="<?php echo array_key_exists( 'keywords',
								       $_REQUEST ) ? $_REQUEST['keywords'] : '' ?>"
							       placeholder="<?php _ex( 'Keywords', 'form search placeholder', 'eolia-app' ) ?>"/>
						</div>
					</div>
				<?php endif ?>

				<?php foreach ( $selected_fields as $selected_field ) : ?>
					<?php if ( ! array_key_exists( $selected_field->name, $fields ) )
						continue ?>
					<?php
					/** @var \Eolia\Controllers\FieldController $field */
					$field = $fields[ $selected_field->name ];

					if ( 'select' === $field->get_component() ) {
						$field_name   = $field->get_id() . ( $selected_field->is_multiple ? '[]' : '' );
						$field_id     = $field->get_id();
						$display_name = $field->get_label();
						$fieldOptions = array( 'row_id' => $row_id ++ );
						if ( $selected_field->is_multiple ) {
							$field->set_component( 'selectpicker' );
						}
						$field->set_options( $fieldOptions );
						echo $field->render_form();
						?>
						<?php
					} ?>
				<?php endforeach; ?>
				<div class="eolia_form-row--input eolia_form-row--submit">
					<div class="eolia_form-group eolia_form-form-group--keywords">
						<input type="submit" value="<?php _e( 'Search', 'eolia-app' ) ?>" name="eolia_search"
						       class="eolia_input eolia_input--button"/>
					</div>
				</div>
			</div>
			<!-- End Search Fields -->
			<!-- GoogleMap -->
			<?php
			wp_enqueue_script( 'googlemap' );
			?>
			<div class="eolia_search_map">
				<div class="eolia_search_map_inner" id="eolia-gmap"></div>
				<?php if ( isset( $results ) && ! empty( $results ) ): ?>
					<script type="text/javascript">
						const geolocs = [<?php
							foreach ( (array) $results as $category ) {
								foreach ( $category as $key => $job ) {
									$location = (array) get_post_meta( $job->ID, 'location', true );
									if ( isset( $location['lat'], $location['lng'] ) ) {
										echo '[' . $location['lat'] . ', ' . $location['lng'] . ', ' . get_post_meta( $job->ID,
												'id',
												true ) . ', "' . get_the_title( $job->ID ) . '", "' . get_the_permalink( $job->ID ) . '"],';
									}
								}
							}
							?>];
					</script>
					<div class="modal fade" id="mapClusterResults" tabindex="-1" role="dialog">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"
									        aria-label="<?php _e( 'Close', 'eolia-app' ) ?>"><span
											aria-hidden="true">&times;</span>
									</button>
									<h4 class="modal-title"><?php _e( 'All our offers', 'eolia-app' ) ?></h4>
								</div>
								<div class="modal-body"></div>
								<div class="modal-footer">
									<button type="button" class="btn btn-primary"
									        data-dismiss="modal"><?php _e( 'Close', 'eolia-app' ) ?></button>
								</div>
							</div>
						</div>
					</div>
				<?php endif ?>
			</div>
			<!-- End Googlemap -->
		</div>
	</form>
</div>
<?php if ( array_key_exists( 'eolia_search', $_REQUEST ) ): ?>
	<hr>
	<?php
	if ( ! locate_template( 'single-results.php', true ) ) {
		require_once \Eolia\EoliaWordpress::getPluginPath() . '/App/Views/Templates/single-results.php';
	} ?>
<?php endif ?>
<!-- END single-jobsearch.php -->

