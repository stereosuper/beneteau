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

		<?php
		$use_ga = false;
		if (isset($_COOKIE['goliath-rgpd-prefs'])) {
			$cookie = json_decode(stripslashes($_COOKIE['goliath-rgpd-prefs']));
			if (isset($cookie->ga) && $cookie->ga == 'accept') {
				$use_ga = true;
			}
		} else {
			if (isset($_COOKIE['beneteau-cookies']) && $_COOKIE['beneteau-cookies'] == 'true') {
				$use_ga = true;
			}
		}
		if($use_ga) :
		?>
		<!-- Google Analytics -->
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-11897367-1', 'auto');
		ga('require', 'displayfeatures');
		ga('set', 'anonymizeIp', true);
		ga('send', 'pageview');
		</script>
		<!-- End Google Analytics -->
		<?php
		endif;
		?>
	</head>

	<?php $class = get_field('scrollreveal') ? 'no-sr' : ''; ?>

	<body <?php body_class( $class ); ?>>
		<div class='wrapper'>
			<p class='access-to-main' id='access'><a href='#main' class='sr-only sr-only-focusable' id='access-to-main'><?php _e('Access main content', 'beneteau'); ?></a></p>

			<header role='banner' class='header <?php if( !is_front_page() && !has_post_thumbnail() ) echo "on"; ?>' id='header'>
				<div class='container'>
					<button type='button' id='contrast' class='contrast' data-on='<?php _e('Contrast version', 'beneteau'); ?>' data-off='<?php _e('Classic version', 'beneteau'); ?>'>
						<?php _e('Contrast version', 'beneteau'); ?>
					</button>

					<?php if( is_front_page() ){ ?>
						<h1 class='logo' id='logo'>
							<div><img src='<?php echo get_template_directory_uri() . '/layoutImg/logo-beneteau-white.svg'; ?>' alt='<?php _e('Beneteau Group', 'beneteau'); ?>' class='logo-img-white'><img src='<?php echo get_template_directory_uri() . '/layoutImg/logo-beneteau.svg'; ?>' alt='<?php _e('Beneteau Group', 'beneteau'); ?>' class='logo-img'></div>
						</h1>
					<?php }else{ ?>
						<?php if( has_post_thumbnail() ){ ?>
							<a href='<?php echo home_url('/'); ?>' rel='home' class='logo' id='logo'>
								<div><img src='<?php echo get_template_directory_uri() . '/layoutImg/logo-beneteau-white.svg'; ?>' alt='<?php _e('Back to home - Beneteau Group', 'beneteau'); ?>' class='logo-img-white'><img src='<?php echo get_template_directory_uri() . '/layoutImg/logo-beneteau.svg'; ?>' alt='<?php _e('Back to home - Beneteau Group', 'beneteau'); ?>' class='logo-img'></div>
							</a>
						<?php }else{ ?>
							<a href='<?php echo home_url('/'); ?>' rel='home' class='logo' id='logo'>
								<div><img src='<?php echo get_template_directory_uri() . '/layoutImg/logo-beneteau.svg'; ?>' alt='<?php _e('Back to home - Beneteau Group', 'beneteau'); ?>'></div>
							</a>
						<?php } ?>
					<?php } ?>

					<nav id="nav" class="main-navigation" role="navigation">
						<button id="burger" class="burger" type="button">
							<span><?php _e('Menu', 'beneteau'); ?></span>
							<svg class="icon" aria-hidden='true' focusable='false'><use xlink:href="#icon-burger"></use></svg>
						</button>
						<div class="main-menus">
							<div class="main-menus-wrapper" id='main-menus'>
								<button id="main-navigation-cross" class="main-navigation-cross" type="button">
									<span><?php _e('Fermer', 'beneteau'); ?></span>
									<svg class="icon" aria-hidden='true' focusable='false'><use href="#icon-cross"/></svg>
								</button>
								<?php echo beneteau_mlp_navigation(); ?>
								<?php /*wp_nav_menu( array(
									'theme_location' => 'primary',
									'container' => false,
									'menu_class' => 'menu-main',
									'walker' => new Wrap_Submenu()
								) ); */?>

								<?php get_template_part('bricks/menu'); ?>
							</div>
						</div>
					</nav>

				</div>
			</header>

			<main role='main' class='main <?php if( has_post_thumbnail() ) echo "has-thumbnail"; ?>' id='main'>
