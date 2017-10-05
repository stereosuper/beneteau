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
<div class='container'>
	<div class="page-header">
		<h1 class="page-title"><?php the_title() ?></h1>
	</div>
	<?php do_action( 'eolia_action_before_form', $job ) ?>
	<?php the_content() ?>
	<?php do_action( 'eolia_action_after_form', $job ) ?>
</div>
<!-- single-apply.php -->
<?php get_footer() ?>

