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
<!-- single-apply.php -->
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="post-content">
			<div class="page-header">
				<h1 class="page-title"><?php the_title() ?></h1>
			</div>
			<?php do_action( 'eolia_action_before_jobform', $job ) ?>
			<?php the_content() ?>
			<?php do_action( 'eolia_action_after_jobform', $job ) ?>
		</div>
	</main>
</div>
<!-- single-apply.php -->
<?php get_footer() ?>

