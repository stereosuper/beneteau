<?php
/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author     Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date       2017
 * @copyright  Eolia Software (http://www.eolia-consulting.com)
 * @licence    GNU
 *
 * @file       single-results.php
 * @package    demo
 * @version    1.0.0
 */

use Eolia\Controllers\FieldController;

$options = get_option( 'eolia-app' );
$headers = json_decode( $options['result_headers'] );
$fields  = eolia_get_fields();
$app     = \Eolia\EoliaWordpress::get_instance();
?>
<?php if ( $results = $app->archive_query() ): ?>
	<!-- single-results.php -->
	<div class="eolia_results">
		<!--		todo: if only 1 category, show directly the results-->
		<?php foreach ( (array) $results as $category => $items ) : ?>
			<div class="eolia_results_category" id="<?php echo sanitize_title( $category ) ?>"
			     data-category="<?php echo sanitize_title( $category ) ?>" data-count="<?php echo count( $items ); ?>" data-collapse="false">
				<?php if ( is_post_type_archive( 'job' ) ): ?>
				<h2 class="eolia_results_category_title">
					<?php else : ?>
					<h1 class="eolia_results_category_title">
						<?php endif ?>
						<span class="eolia_results_category_title_inner">
							<?php if ( ! empty( $category ) ): ?>
								<?php $term = get_term_by( 'name', $category, 'job_category' ); ?>
								<?php if ( ! is_wp_error( $term ) ): ?>
									<?php $term_link = get_term_link( $term, 'job_category' ); ?>
									<a href="<?php echo $term_link ?>" title="<?php echo $term->name ?>">
										<?php echo $category ?>
									</a>
								<?php else: ?>
									<?php echo $category ?>
								<?php endif ?>
							<?php else : ?>
								<?php _ex( 'Uncategorized', 'search-results', 'eolia-app' ) ?>
							<?php endif; ?>
					</span>
						<?php if ( is_post_type_archive( 'job' ) ) : ?>
						<span class="badge"><?php echo count( $items ); ?></span><span class="fa fa-caret-down pull-right"></span>
				</h2>
				<?php else : ?>
				<span class="badge"><?php echo count( $items ); ?></span>
				</h1>
				<?php endif ?>
				<table class="eolia_results_category_table">
					<thead>
					<tr>
						<?php /** @var \Eolia\Controllers\FieldController $field */
						foreach ( (array) $headers as $header ) :
							if ( ! array_key_exists( $header->name, $fields ) ) {
								continue;
							}
							$field = $fields[ $header->name ];
							?>
							<th data-column="<?php echo $header->name ?>"><?php echo $field->get_label() ?></th>
						<?php endforeach; ?>
					</tr>
					</thead>
					<tbody>
					<?php /** @var \WP_Post $offre */
					foreach ( (array) $items as $key => $offre ) : ?>
						<?php
						$row_datas = array(
							'data-offer' => get_post_meta( $offre->ID, 'id', true ),
						);
						foreach ( (array) $headers as $header ) {
							$value                                = get_post_meta( $offre->ID, $header->name, true );
							$row_datas[ 'data-' . $header->name ] = $value;
						}
						?>
						<tr <?php echo FieldController::formatAttributes( $row_datas ) ?>
							onclick="document.location.href='<?php the_permalink( $offre->ID ) ?>';">
							<?php foreach ( (array) $headers as $header ): ?>
								<?php if ( ! array_key_exists( $header->name, $fields ) )
									continue ?>
								<?php $field = $fields[ $header->name ]; ?>
								<td data-column="<?php echo $header->name ?>"
								    data-th="<?php echo $field->get_label() ?>">
									<a href="<?php the_permalink( $offre->ID ) ?>"
									   title="<?php echo $offre->post_title ?>">
										<?php echo get_post_meta( $offre->ID, $header->name, true ) ?>
									</a>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endforeach; ?>
	</div>
	<!-- END single-results.php -->
<?php else: ?>
	<div
		class="eolia_results eolia_results--no-results"><?php _ex(
			'No offers founded.',
			'seaerch-results',
			'eolia-app'
		) ?></div>
<?php endif ?>

