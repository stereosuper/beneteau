<?php get_header(); ?>

<article class='container'>

	<?php if ( have_posts() ) : the_post(); ?>

		<?php if( function_exists('yoast_breadcrumb') ){ yoast_breadcrumb('<div class="breadcrumbs">','</div>'); } ?>

		<h1 class='isAnimated'><?php the_title(); ?></h1>
		<?php the_content(); ?>

	<?php else : ?>
					
		<h1 class='isAnimated'>404</h1>

	<?php endif; ?>

</article>

<?php get_footer(); ?>
