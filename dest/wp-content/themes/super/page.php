<?php get_header(); ?>

<?php if ( have_posts() ) : the_post(); ?>

	<div class='container'>

		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>

        <p class='intro'>Moteur de l'innovation, la recherche figure au premier rang des priorités du groupe qui consacre à ce secteur près de 20 millions d'euros chaque année.</p>

        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna, <a href='#'>sed vehicula leo odio ac leo</a>. ssa arcu laoreet magna, sed vehicula leo odioa arcu laoreet magna, sed vehicula leo odio ac leo. ssa arcu laoreet magna, sed vehicula leo odioa arcu laoreet magna, sed vehicula leo odio ac leo. ssa arcu laoreet mao ac leo. Mn diam pellentesque consectetur. Mauris et lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna, sed vehicula leo odio ac leo. Morbi eleifend lectus in diam pellentesque consectetur.</p>

        <h2>Modules en colonne</h2>

		<div class='grid'>
			<div class='col-2'>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna, <br>
				Duis pellentesque enim commodo sem tristique ullamcorper. Duis sapien erat, mollis id auctor sit amet, commodo vel arcu. Aliquam euismod mauris eros, a fermentum enim aliquam quis. Aenean tincidunt fringilla lectus, nec molestie odio semper ultrices. Duis nec nibh vel ligula lacinia blandit quis ac orci. Pellentesque vel sapien nibh. Donec fringilla auctor nisi eu tristique. Phasellus lobortis aliquet viverra.</p>

				<blockquote><p>Limiter l'impact environnemental de nos produits</p></blockquote>

				<p>Duis pellentesque enim commodo sem tristique ullamcorper. Duis sapien erat, mollis id auctor sit amet, commodo vel arcu. </p>
			</div>

			<div class='col-2'>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna</p>

				<ul>
					<li>Aliquam euismod mauris eros</li>
					<li>ligula lacinia blandit quis ac orci aliquam</li>
					<li>euismod mauris eros</li>
					<li>Sed nec placerat massa.</li>
				</ul>

				<p>Quisque eu purus et eros consequat sagittis et at ex. Suspendisse sit amet quam in justo mollis tristique et eu neque. Ut risus felis, tincidunt ut tortor in, lacinia convallis neque. Sed porttitor erat sed turpis mattis volutpat.. </p>
			</div>

			<div class='col-2'>
				<p>Duis pellentesque enim commodo sem tristique ullamcorper. Duis sapien erat, mollis id auctor sit amet, commodo vel arcu. Aliquam euismod mauris eros, a fermentum enim aliquam quis. Aenean tincidunt fringilla lectus, nec molestie odio semper ultrices. Duis nec nibh vel ligula lacinia blandit quis ac orci. Pellentesque vel sapien nibh. Donec fringilla auctor nisi eu tristique. Phasellus lobortis aliquet viverra.</p>
			</div>

			<div class='col-2'>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna</p>
				
				<h3>Un titre de troisième niveau</h3>

				<ol>
					<li>Aliquam euismod mauris eros</li>
					<li>ligula lacinia blandit quis ac orci aliquam</li>
					<li>euismod mauris eros</li>
					<li>Sed nec placerat massa.</li>
				</ol>

				<p>Duis pellentesque enim commodo sem tristique ullamcorper. Duis sapien erat, mollis id auctor sit amet, commodo vel arcu. </p>
			</div>
		</div>

        <h2>Modules en colonne</h2>

		<div class='grid'>
			<div class='col-2'>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna, <br>
				Duis pellentesque enim commodo sem tristique ullamcorper. Duis sapien erat, mollis id auctor sit amet, commodo vel arcu. Aliquam euismod mauris eros, a fermentum enim aliquam quis. Aenean tincidunt fringilla lectus, nec molestie odio semper ultrices. Duis nec nibh vel ligula lacinia blandit quis ac orci. Pellentesque vel sapien nibh. Donec fringilla auctor nisi eu tristique. Phasellus lobortis aliquet viverra.</p>
			</div>

			<div class='col-2'>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna</p>
				<a href='#' class='link-doc'>Télécharger notre rapport d'activité</a>
				<p>Duis pellentesque enim commodo sem tristique ullamcorper. Duis sapien erat, mollis id auctor sit amet, commodo vel arcu. Aliquam euismod mauris eros, a fermentum enim aliquam quis.</p>
			</div>
		</div>
	
	</div>

	<div class='highlighted'>
		<div class='container'>
			<span class='title'>Savoir faire :</span>
			<strong>Concevoir l'espace et optimiser les volumes pour accompagner de nouveaux modes de vie.</strong>
		</div>
	</div>

	<div class='push-wrapper'>
		<div class='container'>
			<a href='#' class='push'>
				<div class='img'>
					<img src='<?php echo get_template_directory_uri(); ?>/img/boat.png' alt=''>
				</div>
				<strong>Cotation boursière : en savoir plus</strong>
				<span class='link'>Infos financières</span>
			</a>
			<a href='#' class='push'>
				<div class='img'>
					<img src='<?php echo get_template_directory_uri(); ?>/img/boat.png' alt=''>
				</div>
				<strong>Les premières navigations</strong>
				<span class='link'>Infos de traversé</span>
			</a>
			<a href='#' class='push'>
				<div class='img'></div>
				<strong>Nos gammes complètes</strong>
				<span class='link'>Infos des gammes</span>
			</a>
		</div>
	</div>
	
<?php else : ?>
	<div class='container'>			
		<h1>404</h1>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
