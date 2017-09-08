<?php get_header(); ?>

<?php if ( have_posts() ) : the_post(); ?>

	<div class='container'>

		<h1><?php the_title(); ?></h1>

	</div>
	
	<?php the_content(); ?>
	
<?php else : ?>
	<div class='container'>			
		<h1>404</h1>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
