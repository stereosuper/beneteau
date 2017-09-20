<?php get_header(); ?>

<?php if ( have_posts() ) : the_post(); ?>

	<!-- Alain:  le premier titre de la première slide est le h1 de la page --> 
	<div class='slider-home' id='sliderHome'>
		<ul class='slider'>
			<li class='slide on'>
				<div class='img' style='background-image:url(<?php echo get_template_directory_uri(); ?>/img/boat.png)'></div>
				<div class='container clearfix'>
					<div class='txt'>
						<div>
							<h1 class='title'><?php the_title(); ?></h1>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscingvitae enim cursus pretium. Etiam ultricies pharetra tempor. Mauris nec dolor molestie purus maximus mattis vel ut</p>
							<a href='#' class='btn-invert'>Découvrir le groupe</a>
						</div>
					</div>
				</div>
			</li>
			<li class='slide'>
				<div class='img' style='background-image:url(<?php echo get_template_directory_uri(); ?>/img/boat.png)'></div>
				<div class='container clearfix'>
					<div class='txt'>
						<div>
							<h2 class='title'>Nous rejoindre</h2>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscingvitae enim cursus pretium. Etiam ultricies pharetra tempor. Mauris nec dolor molestie purus maximus mattis vel ut</p>
							<a href='#' class='btn-invert'>Consulter nos offres</a>
						</div>
					</div>
				</div>
			</li>
		</ul>
		<ul class='slider-nav'>
			<li><button type='button' class='on'>1</button></li>
			<li><button type='button'>2</button></li>
		</ul>
		<p>Cours de l'action: <strong>14,515<span>€</span></strong></p>
	</div>

	<div class='container'>

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
			<div class='home-carousel'>
				<ul>
					<li><img src='<?php echo get_template_directory_uri(); ?>/img/beneteau.svg'></li>
					<li><img src='<?php echo get_template_directory_uri(); ?>/img/bh.svg'></li>
					<li><img src='<?php echo get_template_directory_uri(); ?>/img/cnb-pro.svg'></li>
					<li><img src='<?php echo get_template_directory_uri(); ?>/img/cnb-yb.svg'></li>
					<li><img src='<?php echo get_template_directory_uri(); ?>/img/coco-sweet.svg'></li>
					<li><img src='<?php echo get_template_directory_uri(); ?>/img/eyb.svg'></li>
					<li><img src='<?php echo get_template_directory_uri(); ?>/img/four-winns.svg'></li>
					<li><img src='<?php echo get_template_directory_uri(); ?>/img/glastron.svg'></li>
					<li><img src='<?php echo get_template_directory_uri(); ?>/img/irm.svg'></li>
				</ul>
			</div>
		</div>

	</div>

<?php else : ?>
	<div class='container'>
		<h1>404</h1>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
