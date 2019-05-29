<?php
	get_header();

	// Récupère le cours de l'action
	$stock_quote_last_price = false;
	$stock_quote = get_option('wiztopic_stockquote');
	if ($stock_quote!==false) {
		$quote_object = json_decode($stock_quote);
		if (isset($quote_object) && isset($quote_object->last) && isset($quote_object->last->last_price)) {
			$stock_quote_symbol = $quote_object->symbol;
			$stock_quote_last_price = $quote_object->last->last_price;
		}
	}
?>
<?php if ( have_posts() ) : the_post(); ?>
	<?php if (super_have_rows('slider')) : ?>
		<div class='slider-home' id='sliderHome'>
			<button class="slider-control" type="button">Pause</button>
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
				<li class='slide slide-img js-slide-img <?php echo $first_class; ?>'>
					<div class='img' style='background-image:url(<?php echo $image_url; ?>)'></div>
				</li>
				<?php
						$first_class = '';
					endwhile; // while (super_have_rows('slider')) :
				?>
			</ul>
			<ul class='slider-txt'>
				<?php
					$first_class = 'first-on';
					$title_level = 'h1';
					$titles_list = [];
					while (super_have_rows('slider')) :
						the_row();
						$title = get_sub_field('homeslider_title');
						$excerpt = get_sub_field('homeslider_excerpt');
						$link = get_sub_field('homeslider_link');
						$link_label = get_sub_field('homeslider_link_label');

						$titles_list[] = $title;
				?>
				<li class='slide slide-txt js-slide-txt <?php echo $first_class; ?>'>
					<h2  class='title'><?php echo $title; ?></h2>
					<p class='txt'><?php echo $excerpt; ?></p>
					<?php if (!empty($link)) : ?>
					<p class='button'>
						<a href='<?php echo $link; ?>' class='a-btn a-btn-light'>
							<?php echo $link_label; ?>
						</a>
					</p>
					<?php endif; ?>
				</li>
				<?php
						$title_level = 'h2';
						$first_class = '';
					endwhile; // while (super_have_rows('slider')) :
				?>
			</ul>
			<?php if( $count > 1 ) : $navCount = 0; ?>
				<ul class='slider-nav js-slider-nav'>
					<?php while( $navCount < $count ) : ?>
						<li>

							<button class="slider-nav-button <?php echo $navCount === 0 ? 'on': '' ?>" type='button'>
								<span class="slider-nav-title"><?php echo $titles_list[$navCount] ?></span>
								<span class="slider-nav-line"></span>
							</button>
						</li>
					<?php $navCount ++; endwhile; ?>
				</ul>
			<?php endif; ?>
			<?php if ($stock_quote_last_price) : ?>
			<div class="action-wrapper">
				<a href='<?php the_field('actionLink', 'options'); ?>' class='action' target='_blank'><?php _e("Cours de l'action", 'beneteau'); ?>: <strong><span>(<?php echo $stock_quote_symbol; ?>)</span> <?php echo $stock_quote_last_price; ?><span>&nbsp;€</span></strong></a>
			</div>
			<?php endif; ?>
		</div>
	<?php endif; // if (super_have_rows('slider')) : ?>

	<div>
		<?php echo the_content(); ?>
	</div>

<?php else : ?>
	<div class='container'>
		<h1>404</h1>
	</div>

<?php endif; ?>

<?php get_footer(); ?>
