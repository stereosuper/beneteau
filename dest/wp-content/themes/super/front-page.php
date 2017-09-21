<?php get_header(); ?>

<?php if ( have_posts() ) : the_post(); ?>

	<?php if (super_have_rows('slider')) : ?>
	<div class='slider-home' id='sliderHome'>
		<ul class='slider-img'>
			<?php
				$first_class = 'first-on';
				while (super_have_rows('slider')) :
					the_row();
					$image = get_sub_field('homeslider_image');
					$image_url = '';
					if(is_array($image) && isset($image['ID'])) {
						list($image_url, $w, $h) = wp_get_attachment_image_src($image['ID'], 'full');
					}
			?>
			<li class='slide slide-img <?php echo $first_class; ?>'>
				<div class='img' style='background-image:url(<?php echo $image_url; ?>)'></div>
			</li>
			<?php
					$first_class = '';
				endwhile; // while (super_have_rows('slider')) :
			?>
		</ul>
		<div class='bg-txt'></div>
		<ul class='slider-txt'>
			<?php
				$first_class = 'first-on';
				while (super_have_rows('slider')) :
					the_row();
					$title = get_sub_field('homeslider_title');
					$excerpt = get_sub_field('homeslider_excerpt');
					$link = get_sub_field('homeslider_link');
					$link_label = get_sub_field('homeslider_link_label');
			?>
			<li class='slide slide-txt <?php echo $first_class; ?>'>
				<div class='container clearfix'>
					<div class='wrapper-txt'>
						<div>
							<h1 class='title'><?php echo $title; ?></h1>
							<p class='txt'><?php echo $excerpt; ?></p>
							<?php if (!empty($link)) : ?><div class='button'><a href='<?php echo $link; ?>' class='btn-invert'><?php echo $link_label; ?></a></div><?php endif; ?>
						</div>
					</div>
				</div>
			</li>
			<?php
					$first_class = '';
				endwhile; // while (super_have_rows('slider')) :
			?>
		</ul>
		<ul class='slider-nav'>
			<li><button type='button' class='on'>1</button></li>
			<li><button type='button'>2</button></li>
		</ul>
		<p>Cours de l'action: <strong>14,515<span>€</span></strong></p>
	</div>
	<?php endif; // if (super_have_rows('slider')) : ?>

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
