<?php
/**
 * The template for displaying application form.
 *
 * $this = eolia plugin public
 *
 */
/** @var \Eolia\Controllers\JobController $job */
$job    = eolia_get_job();
$fields = eolia_get_fields( 'application_fields' );
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

		<h1 class='isAnimated'><?php the_title(); ?></h1>
		<?php do_action( 'eolia_action_before_form', $job ) ?>
		<?php the_content() ?>
		<?php do_action( 'eolia_action_after_form', $job ) ?>

		<?php the_field('applicationText', 'options'); ?>
	</div>

</div>

<?php get_footer() ?>
