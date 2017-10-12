<?php
	get_header();

	// Récupère le cours de l'action
	$stock_quote_last_price = false;
	$stock_quote = get_option('wiztopic_stockquote');
	if ($stock_quote!==false) {
		$quote_object = json_decode($stock_quote);
		if (isset($quote_object) && isset($quote_object->last) && isset($quote_object->last->last_price)) {
			$stock_quote_last_price = $quote_object->last->last_price;
		}
	}
?>
<?php if ( have_posts() ) : the_post(); ?>

	<?php if (super_have_rows('slider')) : ?>
	<div class='slider-home' id='sliderHome'>
		<ul class='slider-img'>
			<?php
				$first_class = 'first-on';
				$count = 0;
				while (super_have_rows('slider')) :
					the_row();
					$image = get_sub_field('homeslider_image');
					$image_url = '';
					if(is_array($image) && isset($image['ID'])) {
						list($image_url, $w, $h) = wp_get_attachment_image_src($image['ID'], 'full');
					}
					$count ++;
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
				$title_level = 'h1';
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
							<<?php echo $title_level; ?>  class='title'><?php echo $title; ?></<?php echo $title_level; ?>>
							<p class='txt'><?php echo $excerpt; ?></p>
							<?php if (!empty($link)) : ?><div class='button'><a href='<?php echo $link; ?>' class='btn-invert'><?php echo $link_label; ?></a></div><?php endif; ?>
						</div>
					</div>
				</div>
			</li>
			<?php
					$title_level = 'h2';
					$first_class = '';
				endwhile; // while (super_have_rows('slider')) :
			?>
		</ul>
		<?php if( $count > 1 ) : $navCount = 0; ?>
			<ul class='slider-nav'>
				<?php while( $navCount < $count ) : ?>
					<li><button type='button' <?php if( $navCount === 0 ) echo "class='on'"; ?>><?php echo $navCount+1; ?></button></li>
				<?php $navCount ++; endwhile; ?>
			</ul>
		<?php endif; ?>
		<?php if ($stock_quote_last_price) : ?><p>Cours de l'action: <strong><?php echo $stock_quote_last_price; ?><span>€</span></strong></p><?php endif; ?>
	</div>
	<?php endif; // if (super_have_rows('slider')) : ?>

	<div class='container'>

		<?php echo the_content(); ?>

	</div>

<?php else : ?>
	<div class='container'>
		<h1>404</h1>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
