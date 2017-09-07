<?php get_header(); ?>

<div class='container'>

	<?php if ( have_posts() ) : the_post(); ?>

		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>

		<h1>Modèle de page type</h1>

        <p class='introduction'>Moteur de l'innovation, la recherche figure au premier rang des priorités du groupe qui consacre à ce secteur près de 20 millions d'euros chaque année.</p>

        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna, <a href='#'>sed vehicula leo odio ac leo</a>. ssa arcu laoreet magna, sed vehicula leo odioa arcu laoreet magna, sed vehicula leo odio ac leo. ssa arcu laoreet magna, sed vehicula leo odioa arcu laoreet magna, sed vehicula leo odio ac leo. ssa arcu laoreet mao ac leo. Mn diam pellentesque consectetur. Mauris et lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna, sed vehicula leo odio ac leo. Morbi eleifend lectus in diam pellentesque consectetur.</p>

        <h2>Modules en colonne</h2>

        <div class='col'>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna, <br>
            Duis pellentesque enim commodo sem tristique ullamcorper. Duis sapien erat, mollis id auctor sit amet, commodo vel arcu. Aliquam euismod mauris eros, a fermentum enim aliquam quis. Aenean tincidunt fringilla lectus, nec molestie odio semper ultrices. Duis nec nibh vel ligula lacinia blandit quis ac orci. Pellentesque vel sapien nibh. Donec fringilla auctor nisi eu tristique. Phasellus lobortis aliquet viverra.</p>

            <blockquote>Limiter l'impact environnemental de nos produits</blockquote>

            <p>Duis pellentesque enim commodo sem tristique ullamcorper. Duis sapien erat, mollis id auctor sit amet, commodo vel arcu. </p>
        </div>

        <div class='col'>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna</p>

            <ul>
                <li>Aliquam euismod mauris eros</li>
                <li>ligula lacinia blandit quis ac orci aliquam</li>
                <li>euismod mauris eros</li>
                <li>Sed nec placerat massa.</li>
            </ul>

        	<p>Quisque eu purus et eros consequat sagittis et at ex. Suspendisse sit amet quam in justo mollis tristique et eu neque. Ut risus felis, tincidunt ut tortor in, lacinia convallis neque. Sed porttitor erat sed turpis mattis volutpat.. </p>
        </div>

        <div class='col'>
            <p>Duis pellentesque enim commodo sem tristique ullamcorper. Duis sapien erat, mollis id auctor sit amet, commodo vel arcu. Aliquam euismod mauris eros, a fermentum enim aliquam quis. Aenean tincidunt fringilla lectus, nec molestie odio semper ultrices. Duis nec nibh vel ligula lacinia blandit quis ac orci. Pellentesque vel sapien nibh. Donec fringilla auctor nisi eu tristique. Phasellus lobortis aliquet viverra.</p>
        </div>

        <div class='col'>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna</p>

            <ol>
                <li>Aliquam euismod mauris eros</li>
                <li>ligula lacinia blandit quis ac orci aliquam</li>
                <li>euismod mauris eros</li>
                <li>Sed nec placerat massa.</li>
            </ol>

            <p>Duis pellentesque enim commodo sem tristique ullamcorper. Duis sapien erat, mollis id auctor sit amet, commodo vel arcu. </p>
        </div>

        <h2>Modules en colonne</h2>

        <div class='col'>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna, <br>
            Duis pellentesque enim commodo sem tristique ullamcorper. Duis sapien erat, mollis id auctor sit amet, commodo vel arcu. Aliquam euismod mauris eros, a fermentum enim aliquam quis. Aenean tincidunt fringilla lectus, nec molestie odio semper ultrices. Duis nec nibh vel ligula lacinia blandit quis ac orci. Pellentesque vel sapien nibh. Donec fringilla auctor nisi eu tristique. Phasellus lobortis aliquet viverra.</p>
        </div>

        <div class='col'>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quis venenatis risus, nec aliquet velit. Nunc scelerisque, est id vehicula bibendum, massa arcu laoreet magna</p>
            <a href='#' class='link-doc'>Télécharger notre rapport d'activité</a>
            <p>Duis pellentesque enim commodo sem tristique ullamcorper. Duis sapien erat, mollis id auctor sit amet, commodo vel arcu. Aliquam euismod mauris eros, a fermentum enim aliquam quis.</p>
        </div>
	
	<?php else : ?>
				
		<h1>404</h1>

	<?php endif; ?>

</div>

<?php get_footer(); ?>