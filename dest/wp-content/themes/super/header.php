<!DOCTYPE html>
<html <?php language_attributes(); ?> class='no-js'>
	<head>
		<meta charset='utf-8'>
		<meta name='viewport' content='width=device-width,initial-scale=1'>
		<meta name='format-detection' content='telephone=no'>

		<link rel='alternate' type='application/rss+xml' title='<?php echo get_bloginfo('sitename') ?> Feed' href='<?php echo get_bloginfo('rss2_url') ?>'>

		<link rel='apple-touch-icon' sizes='180x180' href='/apple-touch-icon.png'>
		<link rel='icon' type='image/png' sizes='32x32' href='/favicon-32x32.png'>
		<link rel='icon' type='image/png' sizes='16x16' href='/favicon-16x16.png'>
		<link rel='manifest' href='/manifest.json'>
		<link rel='mask-icon' href='/safari-pinned-tab.svg' color='#5bbad5'>
		<meta name='theme-color' content='#ffffff'>

		<?php wp_head(); ?>

		<script>document.getElementsByTagName('html')[0].className = 'js';</script>

		<!-- Google Analytics -->
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-11897367-1', 'auto');
		ga('require', 'displayfeatures');
		ga('send', 'pageview');
		</script>
		<!-- End Google Analytics -->
	</head>

	<?php $class = get_field('scrollreveal') ? 'no-sr' : ''; ?>

	<body <?php body_class( $class ); ?>>

		<header role='banner' class='header' id='header'>
			<div class='container'>

				<a href='<?php echo home_url('/'); ?>' title='<?php _e('Groupe Bénéteau', 'beneteau'); ?>' rel='home' class='logo'>
					<div><img src='<?php echo get_template_directory_uri(); ?>/layoutImg/logo-beneteau.svg' alt='<?php _e('Groupe Bénéteau', 'beneteau'); ?>'></div>
				</a>

				<button class='burger' id='burger'><?php _e('Menu', 'beneteau'); ?> <i></i></button>

				<nav role='navigation' class='nav' id='nav'>
					<div>
						<?php echo beneteau_mlp_navigation(); ?>

						<?php
							$contact_page_url = super_get_field('contact_page_url', 'option');
							if (!empty($contact_page_url)) :
						?>
							<a href='<?php echo $contact_page_url; ?>' class='link-contact'>
								<svg class='icon'><use xlink:href='#icon-email'></use></svg>
							</a>
						<?php endif; ?>

						<?php wp_nav_menu( array(
							'theme_location' => 'primary',
							'container' => false,
							'menu_class' => 'menu-main',
							'walker' => new Wrap_Submenu()
						) ); ?>
					</div>
				</nav>

			</div>
		</header>

		<main role='main' class='main'>
