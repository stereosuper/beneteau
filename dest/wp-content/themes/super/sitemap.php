<?php
/*
Template Name: Sitemap
*/
get_header(); ?>

	<div class='container'>

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>

				<?php
					$pagesQuery = new WP_Query(
						array(
							'post_type' => 'page',
							'order' => 'ASC',
							'orderby' => 'title',
							'posts_per_page' => -1,
							'meta_query' => array(
								array(
									'key'     => 'sitemap_hide',
									'value'   => 0
								)
							)
						)
					);

					if ( $pagesQuery->have_posts() ) {
						echo '<ul>';
						while ( $pagesQuery->have_posts() ) {
							$pagesQuery->the_post();
							echo '<li><a href='.get_the_permalink().'>' . get_the_title() . '</a></li>';
						}
						echo '</ul>';
					}
				?>

			<?php endwhile; ?>

		<?php else : ?>

			<h1>404</h1>

		<?php endif; ?>

	</div>

<?php get_footer(); ?>
