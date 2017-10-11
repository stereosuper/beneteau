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
<!-- single-job.php -->
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="post-content">
			<?php echo do_shortcode( '[eolia_sharelink position="top" /]' ); ?>
			<?php do_action( 'eolia_action_before_jobview', $job ) ?>
			<?php the_content() ?>
			<?php do_action( 'eolia_action_after_jobview', $job ) ?>
			<?php echo do_shortcode( '[eolia_sharelink position="bottom" /]' ); ?>
		</div>
	</main>
</div>
<!-- END single-job.php -->
<?php get_footer() ?>

