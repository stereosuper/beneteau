<?php
/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author     Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date       2017
 * @copyright  Eolia Software (http://www.eolia-consulting.com)
 * @licence    GNU
 *
 * @file       taxonomy-job.php
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

<?php $sidebar_menu = wp_nav_menu( array(
	'echo' => false,
	'theme_location' => 'job',
	'container' => false,
	'menu_class' => 'sidebar-menu',
	'menu_id' => 'submenu',
	) );
?>

<div class='container<?php echo (strpos($sidebar_menu, '<li')!==FALSE)?' container-sidebar':''; ?>'>

	<?php if (strpos($sidebar_menu, '<li')!==FALSE) : ?>
		<aside class='sidebar wrapper-sticky' id='sidebar'>
			<div class='content-sidebar' id='blockSticky'>
				<span class='bg-sidebar'></span>
				<?php echo $sidebar_menu; ?>
			</div>
		</aside>
	<?php endif; ?>

	<div class='content'>
		<?php if( function_exists('yoast_breadcrumb') ){ yoast_breadcrumb('<div class="breadcrumbs">','</div>'); } ?>

		<h1 class='isAnimated'><?php single_term_title(); ?> </h1>

		<?php get_template_part( 'single-results' ); ?>
	</div>

</div>

<?php get_footer() ?>