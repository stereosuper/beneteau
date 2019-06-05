		</main>

		<?php $cookie = isset($_COOKIE['beneteau-cookies']) ? true : false; ?>

		<footer role='contentinfo' class='footer' id='footer'>
			
			<?php if(!$cookie){ ?>
				<div class='cookies' id='cookies'>
					<div class='container'>
						<?php the_field('cookie', 'options'); ?>
						<button type='button' id='btnCookies'><?php _e('Close the cookie warning', 'beneteau'); ?></button>
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
					'theme_location' => 'primary',
					'container' => false,
					'menu_class' => 'menu-main'
				) ); ?>
			</div>
		</footer>

		<div id='menuBg' class='menu-bg'></div>

	</div>

	<svg width='0' height='0' style='position:absolute;z-index:-10'>
		<defs>
			<clipPath id='clipImg' clipPathUnits='objectBoundingBox'>
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

			<symbol id='icon-video' viewBox='0 0 32 32'>
				<title>Vidéo</title>
				<path fill='#fff' opacity='0.9' d='M15.995 1.183c-8.25 0-14.938 6.688-14.938 14.938s6.688 14.938 14.938 14.938c8.25 0 14.938-6.688 14.938-14.938s-6.688-14.938-14.938-14.938zM15.995 0.294c8.741 0 15.827 7.086 15.827 15.826s-7.086 15.827-15.827 15.827c-8.741 0-15.826-7.086-15.826-15.827s7.086-15.826 15.826-15.826z'></path>
				<path fill='#fff' opacity='0.9' d='M24.223 16.121l-12.343-7.126v14.252l12.343-7.126z'></path>
			</symbol>

			<symbol id='icon-twitter' viewBox="0 0 16 13" fill="none">
				<path d="M16 1.539a6.839 6.839 0 0 1-1.89.518A3.262 3.262 0 0 0 15.553.244a6.555 6.555 0 0 1-2.08.794 3.28 3.28 0 0 0-5.674 2.243c0 .26.022.51.076.748A9.284 9.284 0 0 1 1.114.598a3.285 3.285 0 0 0 1.008 4.384A3.24 3.24 0 0 1 .64 4.578v.036a3.295 3.295 0 0 0 2.628 3.223 3.274 3.274 0 0 1-.86.108 2.9 2.9 0 0 1-.621-.056 3.311 3.311 0 0 0 3.065 2.285 6.59 6.59 0 0 1-4.067 1.399c-.269 0-.527-.012-.785-.045A9.234 9.234 0 0 0 5.032 13c6.036 0 9.336-5 9.336-9.334 0-.145-.005-.285-.012-.424A6.544 6.544 0 0 0 16 1.539z" fill="currentColor"/>
			</symbol>

			<symbol id="icon-linkedin" viewBox="0 0 16 16" fill="none">
				<path d="M3.635 5.677H.198V16h3.437V5.677zM14.917 6.62c-.723-.789-1.678-1.183-2.865-1.183-.437 0-.835.054-1.193.162-.357.107-.66.258-.906.453a4.444 4.444 0 0 0-.588.542 4.47 4.47 0 0 0-.407.55V5.678H5.531l.01.5c.008.333.011 1.36.011 3.083 0 1.722-.007 3.969-.02 6.74h3.426v-5.76c0-.355.038-.636.115-.845.146-.354.366-.65.661-.89.296-.24.662-.36 1.1-.36.596 0 1.036.207 1.317.62.281.413.422.984.422 1.714v5.52H16v-5.916c0-1.52-.361-2.675-1.083-3.463zM1.937.708c-.576 0-1.043.169-1.4.505C.178 1.55 0 1.975 0 2.49c0 .507.174.93.52 1.271.348.34.806.51 1.376.51h.02c.584 0 1.055-.17 1.412-.51.358-.34.533-.764.526-1.27-.007-.515-.184-.94-.531-1.277-.347-.337-.81-.505-1.386-.505z" fill="currentColor"/>
			</symbol>


			<symbol id="icon-burger" viewBox="0 0 21 14" fill="none">
				<path d="M1 13h19M1 7h19M1 1h19" stroke="currentColor" stroke-width="2" stroke-linecap="square"/>
			</symbol>

			<symbol id="icon-cross" viewBox="0 0 23 22" fill="none">
				<path fill="currentColor" d="M20.815.92l1.414 1.413-19.52 19.52-1.415-1.413z"/>
				<path fill="currentColor" d="M21.41 20.44l-1.414 1.414L.476 2.334 1.888.918z"/>
			</symbol>

		</defs>
	</svg>

	<?php wp_footer(); ?>

	</body>
</html>
