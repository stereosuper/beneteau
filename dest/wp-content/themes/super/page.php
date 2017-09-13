<?php get_header(); ?>

<?php if ( have_posts() ) : the_post(); ?>

	<?php $sidebar_menu = wp_nav_menu( array(
		'echo' => false,
		'theme_location' => 'primary',
		'container' => false,
		'menu_class' => 'sidebar-menu',
		'depth' => 0,
		'walker' => new CustomWalkerNavSubMenu()
		) );
	?>

	<div class='container<?php echo (!empty($sidebar_menu))?' container-sidebar':''; ?>'>
		
		<?php if (!empty($sidebar_menu)) : ?>
			<aside class='sidebar'>
				<?php echo $sidebar_menu; ?>
			</aside>
		<?php endif; ?>

		<div class='content'>
			<?php if( function_exists('yoast_breadcrumb') ){ yoast_breadcrumb('<div class="breadcrumbs">','</div>'); } ?>

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
