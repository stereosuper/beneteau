<?php
/*
Template Name: Contact
*/

get_header();
?>

<?php if ( have_posts() ) : the_post(); ?>

	<?php $sidebar_menu = wp_nav_menu( array(
		'echo' => false,
		'theme_location' => 'primary',
		'container' => false,
		'menu_class' => 'sidebar-menu',
		'menu_id' => 'submenu',
		'depth' => 0,
		'walker' => new CustomWalkerNavSubMenu()
		) );
	?>

	<div class='container<?php echo (strpos($sidebar_menu, '<li')!==FALSE)?' container-sidebar':''; ?>'>

		<?php if (strpos($sidebar_menu, '<li')!==FALSE) : ?>
			<aside class='sidebar wrapper-sticky'>
				<div class='content-sidebar' id='blockSticky'>
					<span class='bg-sidebar'></span>
					<?php echo $sidebar_menu; ?>
				</div>
			</aside>
		<?php endif; ?>

		<div class='content'>
			<?php if( function_exists('yoast_breadcrumb') ){ yoast_breadcrumb('<div class="breadcrumbs">','</div>'); } ?>

			<h1 class='isAnimated'><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</div>

	</div>

<?php else : ?>
	<div class='container'>
		<h1>404</h1>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
