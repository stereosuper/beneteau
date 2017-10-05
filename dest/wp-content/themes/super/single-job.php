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

<div class='container container-sidebar'>

	<aside class='sidebar wrapper-sticky' id='sidebar'>
		<div class='content-sidebar' id='blockSticky'>
			<span class='logo-reduced'></span>
			<span class='bg-sidebar'></span>
			<?php dynamic_sidebar( 'job-menu' ); ?>
		</div>
	</aside>

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
