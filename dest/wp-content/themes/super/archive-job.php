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

<?php $sidebar_menu = wp_nav_menu( array(
	'echo' => false,
	'theme_location' => 'primary',
	'container' => false,
	'menu_class' => 'sidebar-menu',
	'menu_id' => 'submenu',
	'depth' => 0,
	'walker' => new CustomWalkerNavSubMenu()
	) );
?>

<div class='container<?php echo (strpos($sidebar_menu, '<li')!==FALSE)?' container-sidebar':''; ?>'>

	<?php if (strpos($sidebar_menu, '<li')!==FALSE) : ?>
		<aside class='sidebar wrapper-sticky' id='sidebar'>
			<div class='content-sidebar' id='blockSticky'>
				<span class='logo-reduced'></span>
				<span class='bg-sidebar'></span>
				<?php echo $sidebar_menu; ?>
			</div>
		</aside>
	<?php endif; ?>

	<div class='content'>
		<?php if( function_exists('yoast_breadcrumb') ){ yoast_breadcrumb('<div class="breadcrumbs">','</div>'); } ?>

		<h1 class='isAnimated'><?php post_type_archive_title() ?></h1>

		<?php
		if ( ! locate_template( 'single-results.php', true ) ) {
			require_once dirname( __DIR__ ) . '/partials/single-results.php';
		} ?>
	</div>

</div>

<?php get_footer() ?>
