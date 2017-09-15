<?php get_header(); ?>

<?php if ( have_posts() ) : the_post(); ?>

	<div class='container'>
		
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>

		<h2 class='aligncenter'>Actualités</h2>

		<div class='home-news'>
			<div class='push-container'>
				<a href='#'>
					<div class='img'>
						<img src='<?php echo get_template_directory_uri(); ?>/img/boat.png' alt=''>
					</div>
					<time>10/08/2017</time>
					<h3>Les salons nautiques européens de la rentrée</h3>
				</a>
				<a href='#'>
					<div class='img'>
						<img src='<?php echo get_template_directory_uri(); ?>/img/boat.png' alt=''>
					</div>
					<time>24/07/2017</time>
					<h3>CNB 66 : Les premières navigations</h3>
				</a>
			</div>

			<div class='text'>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna.</p>
				<a href='#' class='link'>Toutes les actualités</a>
			</div>
		</div>

		<div class='home-talent'>
			<img src='<?php echo get_template_directory_uri(); ?>/img/boat.png' alt=''>

			<div class='text'>
				<h2>Talents</h2>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, arcu laoreet magna.</p>
				<a href='#' class='btn-invert'>Découvrir nos métiers</a>
			</div>
		</div>

		<hr>

		<div class='home-brands'>
			<div class='text'>
				<h2>Marques & Services</h2>
				<p>Maecenas id posuere massa, facilisis imperdiet nunc. Nulla quis consequat ante. Aenean in ligula lacinia, convallis orci sed, feugiat tortor.</p>
				<a href='#' class='btn-invert'>Nos marques et services</a>
			</div>
			<div></div>
		</div>

	</div>

<?php else : ?>
	<div class='container'>
		<h1>404</h1>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
