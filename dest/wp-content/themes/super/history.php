
<?php 
	/*
	Template Name: Historique
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
			<aside class='sidebar wrapper-sticky' id='sidebar'>
				<div class='content-sidebar' id='blockSticky'>
					<span class='logo-reduced'></span>
					<span class='bg-sidebar'></span>
					<?php echo $sidebar_menu; ?>
				</div>
			</aside>
		<?php endif; ?>

		<div class='content'>
			<?php if( function_exists('yoast_breadcrumb') ){ yoast_breadcrumb('<div class="breadcrumbs">','</div>'); } ?>

			<h1 class='isAnimated'><?php the_title(); ?></h1>
			<ul class='history'>
				<li class='isAnimated'>
					<time datetime='1884'>1884</time>
					<img src='https://www.vulgaris-medical.com/sites/default/files/styles/big-lightbox/public/field/image/actualites/2016/02/12/le-chat-source-de-bienfaits-pour-votre-sante.jpg' alt=''>
					<p>Lorem ipsum dolor sit amet, fringilla rhoncus pulvinar aliquam, purus felis, velit enim facilisis morbi. Iaculis et felis, mi justo pretium, libero quam, magnis tincidunt. Lacinia vehicula eu et.</p>
					<a href='#' class='link'>En savoir plus</a>
				</li>
				<li class='isAnimated'>
					<time datetime='1884'>1884</time>
					<p>Lorem ipsum dolor sit amet, fringilla rhoncus pulvinar aliquam, purus felis, velit enim facilisis morbi. Iaculis et felis, mi justo pretium, libero quam, magnis tincidunt. Lacinia vehicula eu et.</p>
					<a href='#' class='link'>En savoir plus</a>
				</li>
				<li class='isAnimated'>
					<time datetime='1884'>1884</time>
					<p>Lorem ipsum dolor sit amet, fringilla rhoncus pulvinar aliquam, purus felis, velit enim facilisis morbi. Iaculis et felis, mi justo pretium, libero quam, magnis tincidunt. Lacinia vehicula eu et.</p>
					<a href='#' class='link'>En savoir plus</a>
				</li>
				<li class='isAnimated'>
					<time datetime='1884'>1884</time>
					<p>Lorem ipsum dolor sit amet, fringilla rhoncus pulvinar aliquam, purus felis, velit enim facilisis morbi. Iaculis et felis, mi justo pretium, libero quam, magnis tincidunt. Lacinia vehicula eu et.</p>
					<a href='#' class='link'>En savoir plus</a>
				</li>
			</ul>

		</div>

	</div>

<?php else : ?>
	<div class='container'>
		<h1>404</h1>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
