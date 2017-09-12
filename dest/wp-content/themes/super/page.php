<?php get_header(); ?>

<?php if ( have_posts() ) : the_post(); ?>

	<!-- TODO: Dynamic menu + check if menu exist to display corresponding layout -->

	<div class='container container-sidebar'>
		<aside class='sidebar'>
			<ul class='sidebar-menu'>
				<li><a href='#'>Histoire</a></li>
				<li class='current_page_item'>
					<a href='#'>Stratégie</a>
					<ul>
						<li><a href='#'>Mot des présidents</a></li>
						<li><a href='#'>Gouvernance</a></li>
					</ul>
				</li>
				<li><a href='#'>Valeurs</a></li>
			</ul>
		</aside>

		<div class='content'>
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</div>
	</div>
	
<?php else : ?>
	<div class='container'>			
		<h1>404</h1>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
