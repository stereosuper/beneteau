		</main>

		<?php $cookie = isset($_COOKIE['beneteau-cookies']) ? true : false; ?>

		<footer role='contentinfo' class='footer'>
			<?php if(!$cookie){ ?>
				<div class='cookies' id='cookies'>
					<div class='container'>
						<p>En poursuivant votre navigation sur ce site, vous acceptez l’utilisation de cookies. Pour en savoir plus consultez notre politique vis-à-vis des cookies</p>
						<button type='button' id='btnCookies'>Close</button>
					</div>
				</div>
			<?php } ?>
			<div class='container'>
				<p>
					<?php _e( sprintf('Copyright Groupe Beneteau %1$s &copy;', date('Y') ), 'beneteau'); ?>
					<?php wp_nav_menu( array(
						'theme_location' => 'legals',
						'container' => false,
						'items_wrap' => '%3$s',
						'fallback_cb' => 'none',
						'walker' => new CustomWalkerNavOnlyA()
					) ); ?>
				</p>

				<?php wp_nav_menu( array(
					'theme_location' => 'footer',
					'container' => false,
					'menu_class' => 'menu-footer',
					'fallback_cb' => 'none'
				) ); ?>
			</div>
		</footer>

		<div id='menuBg' class='menu-bg'></div>

		<svg width='0' height='0' style='position:absolute;z-index:-10'>
			<defs>
				<clipPath id='clipImg' clipPathUnits="objectBoundingBox">
					<path d='M0.1744899,0.1969967C0.0224832,0.2373544,0.0005927,0.2689686,0,0.4032062L0.00419,1 c0.0111598-0.0764089,0.0497083-0.1031455,0.1698911-0.1350859L1,0.6678032V0L0.1744899,0.1969967z'/>
				</clipPath>

				<symbol id='icon-email' viewBox='0 0 43 32'>
					<title>Contact</title>
					<path d='M38.667 0h-34.667c-2.133 0-4 1.867-4 4v24c0 2.133 1.867 4 4 4h34.667c2.133 0 4-1.867 4-4v-24c0-2.133-1.867-4-4-4zM4 2.667h34.667c0.8 0 1.333 0.533 1.333 1.333v1.6c-14.933 10.133-18.133 11.733-18.667 11.733s-3.733-1.6-18.667-11.733v-1.6c0-0.8 0.533-1.333 1.333-1.333zM38.667 29.333h-34.667c-0.8 0-1.333-0.533-1.333-1.333v-19.2c16.533 11.2 17.867 11.2 18.667 11.2s2.133 0 18.667-11.2v19.2c0 0.8-0.533 1.333-1.333 1.333z'/>
				</symbol>

				<symbol id='icon-left' viewBox='0 0 147 32'>
					<path d='M147.2 12.8h-126.080v-12.8l-21.12 16 21.12 16v-12.8h126.080z'/>
				</symbol>

				<symbol id='icon-right' viewBox='0 0 147 32'>
					<path d='M-0.013 19.2h126.080v12.8l21.12-16-21.12-16v12.8h-126.080z'/>
				</symbol>

				<symbol id='icon-tel' viewBox='0 0 32 32'>
					<title>Phone</title>
					<path d='M31.38 27.539l-2.394 2.394c-2.4 2.405-6.175 2.753-8.975 0.828-3.726-2.461-7.258-5.204-10.564-8.207-3.002-3.306-5.746-6.839-8.207-10.565-1.925-2.8-1.577-6.574 0.828-8.975l2.394-2.394c0.397-0.397 0.935-0.62 1.496-0.62s1.099 0.223 1.496 0.62l3.988 3.988c0.397 0.397 0.62 0.935 0.62 1.496s-0.223 1.099-0.62 1.496l-1.718 1.718c-0.462 0.461-0.546 1.179-0.204 1.735 1.399 2.342 3.046 4.527 4.913 6.517 1.99 1.867 4.175 3.514 6.517 4.913 0.556 0.342 1.274 0.257 1.735-0.205l1.718-1.718c0.826-0.826 2.165-0.826 2.991 0l3.988 3.988c0.397 0.397 0.62 0.935 0.619 1.496s-0.223 1.099-0.62 1.495v0z'/>
				</symbol>
			</defs>
		</svg>

		<?php wp_footer(); ?>

		</body>
	</html>
