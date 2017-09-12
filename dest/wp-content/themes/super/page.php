<?php get_header(); ?>

<?php if ( have_posts() ) : the_post(); ?>

	<!-- TODO: Dynamic menu + check if menu exist to display corresponding layout -->

	<div class='container container-sidebar'>
		<aside class='sidebar'>
			<?php wp_nav_menu( array(
				'theme_location' => 'primary',
				'container' => false,
				'menu_class' => 'sidebar-menu',
				'depth' => 0,
				'walker' => new CustomWalkerNavSubMenu()
			 ) );
			?>
		</aside>

		<div class='content'>
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</div>
	</div>

<?php else : ?>
	<div class='container'>
		<h1>404</h1>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
