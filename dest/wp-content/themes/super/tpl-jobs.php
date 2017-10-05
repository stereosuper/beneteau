<?php
/*
Template Name: Emploi liste
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

		<h1 class='isAnimated'><?php the_title() ?></h1>

        <?php the_content(); ?>
        
		<?php echo do_shortcode( '[eolia_search/]' ); ?>

		<?php get_template_part( 'single-results' ); ?>
	</div>

</div>

<?php get_footer() ?>
