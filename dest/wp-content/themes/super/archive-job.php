<?php
/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author     Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date       2017
 * @copyright  Eolia Software (http://www.eolia-consulting.com)
 * @licence    GNU
 *
 * @file       archive-job.php
 * @package    eolia-app
 * @version    1.0.0
 */

/**
 * The template for displaying single job.
 *
 * $this = eolia plugin public
 *
 */
get_header();
?>
	<div class='container'>
		<div class="page-header">
			<h1 class="page-title"><?php post_type_archive_title() ?></h1>
		</div>
		<?php
		if ( ! locate_template( 'single-results.php', true ) ) {
			require_once dirname( __DIR__ ) . '/partials/single-results.php';
		} ?>
	</div>
<?php get_footer() ?>