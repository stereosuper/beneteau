<?php
/**
 * The template for displaying single job.
 *
 * $this = eolia plugin public
 *
 */
$job = eolia_get_job();
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
				<span class='logo-reduced'></span>
				<span class='bg-sidebar'></span>
				<?php echo $sidebar_menu; ?>
			</div>
		</aside>
	<?php endif; ?>

	<div class='content'>
		<?php if( function_exists('yoast_breadcrumb') ){ yoast_breadcrumb('<div class="breadcrumbs">','</div>'); } ?>
		<?php echo do_shortcode( '[eolia_sharelink position="top" /]' ); ?>
		<?php do_action( 'eolia_action_before_view', $job ) ?>
		<?php the_content() ?>
		<?php do_action( 'eolia_action_after_view', $job ) ?>
		<?php echo do_shortcode( '[eolia_sharelink position="bottom" /]' ); ?>
	</div>

</div>

<?php get_footer() ?>
