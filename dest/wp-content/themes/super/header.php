<!DOCTYPE html>
<html <?php language_attributes(); ?> class='no-js'>
	<head>
		<meta charset='utf-8'>
		<meta name='viewport' content='width=device-width,initial-scale=1'>
		<meta name='format-detection' content='telephone=no'>

		<link rel='alternate' type='application/rss+xml' title='<?php echo get_bloginfo('sitename') ?> Feed' href='<?php echo get_bloginfo('rss2_url') ?>'>

		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>

		<header role='banner' class='header' id='header'>
			<div class='container'>
			
				<a href='<?php echo home_url('/'); ?>' title='Groupe Bénéteau' rel='home' class='logo'><img src='<?php echo get_template_directory_uri(); ?>/layoutImg/logo-beneteau.svg' alt='Groupe Bénéteau'></a>

				<button class='burger' id='burger'>Menu</button>

				<nav role='navigation' class='nav' id='nav'>
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'menu-main' ) ); ?>

					<!-- TODO: Lang switcher + contact page (acf field) -->

					<button class='menu-close' id='close'>Close</button>
				</nav>

			</div>
		</header>

		<main role='main' class='main'>
