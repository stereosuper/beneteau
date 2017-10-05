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
	<?php if( function_exists('yoast_breadcrumb') ){ yoast_breadcrumb('<div class="breadcrumbs">','</div>'); } ?>

	<h1 class='isAnimated'><?php the_title(); ?></h1>
	<?php do_action( 'eolia_action_before_form', $job ) ?>
	<?php the_content() ?>
	<?php do_action( 'eolia_action_after_form', $job ) ?>
</div>
<!-- single-apply.php -->
<?php get_footer() ?>

