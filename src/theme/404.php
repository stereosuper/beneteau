<?php get_header(); ?>

<div class='container'>
	<h1>404</h1>
	<p><?php _e("We're sorry, the page you're looking for doesn't exist.", 'beneteau'); ?></p>
	<p><a href='<?php echo site_url(); ?>'><?php _e("Go back to home", 'beneteau'); ?></a></p>
</div>

<?php get_footer(); ?>