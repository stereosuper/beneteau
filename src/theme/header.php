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

				<a href='<?php echo home_url('/'); ?>' title='<?php _e('Groupe Bénéteau', 'beneteau'); ?>' rel='home' class='logo'><img src='<?php echo get_template_directory_uri(); ?>/layoutImg/logo-beneteau.svg' alt='<?php _e('Groupe Bénéteau', 'beneteau'); ?>'></a>

				<button class='burger' id='burger'><?php _e('Menu', 'beneteau'); ?> <i></i></button>

				<nav role='navigation' class='nav' id='nav'>
					<div>
					<?php echo beneteau_mlp_navigation(); ?>

						<?php wp_nav_menu( array(
							'theme_location' => 'top',
							'container' => false,
							'menu_class' => 'menu-main'
						) ); ?>

						<?php wp_nav_menu( array(
							'theme_location' => 'primary',
							'container' => false,
							'menu_class' => 'menu-main'
						) ); ?>
					</div>
				</nav>

			</div>
		</header>

		<main role='main' class='main'>
