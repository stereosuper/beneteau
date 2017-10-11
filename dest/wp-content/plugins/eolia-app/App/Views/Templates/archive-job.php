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
 * @package    demo
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
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="post-content">
				<?php
				if ( ! locate_template( 'single-results.php', true ) ) {
					require_once \Eolia\EoliaWordpress::getPluginPath() . 'App/Views/Templates/single-results.php';
				} ?>
			</div>
		</main>
	</div>
<?php get_footer() ?>