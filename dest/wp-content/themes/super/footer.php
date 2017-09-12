		</main>

		<footer role='contentinfo' class='footer'>
			<div class='container'>
				<p>
					<?php _e( sprintf('Copyright Groupe Bénéteau %1$s &copy;', date('Y') ), 'beneteau'); ?>
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


		<svg width='0' height='0' style='position:absolute;z-index:-10'>
			<defs>
				<clipPath id='clipImg' clipPathUnits="objectBoundingBox">
					<path d='M0.1744899,0.1969967C0.0224832,0.2373544,0.0005927,0.2689686,0,0.4032062L0.00419,1
							c0.0111598-0.0764089,0.0497083-0.1031455,0.1698911-0.1350859L1,0.6678032V0L0.1744899,0.1969967z'/>
				</clipPath>
			</defs>
		</svg>

		<?php wp_footer(); ?>

		</body>
	</html>
