<?php get_header(); ?>

    <?php $sidebar_menu = wp_nav_menu( array(
            'echo' => false,
            'theme_location' => 'job',
            'container' => false,
            'menu_class' => 'sidebar-menu',
            'menu_id' => 'submenu',
		) );
	?>

<div class='container<?php echo (strpos($sidebar_menu, '<li')!==FALSE)?' container-sidebar':''; ?>'>

	<?php if (strpos($sidebar_menu, '<li')!==FALSE) : ?>
			
        <aside class='sidebar wrapper-sticky' id='sidebar'>
			<div class='content-sidebar' id='blockSticky'>
				<span class='logo-reduced'></span>
				<span class='bg-sidebar'></span>
				<?php echo $sidebar_menu; ?>
			</div>
		</aside>

	<?php endif; ?>

    
    <div class='content'>

        <?php if ( have_posts() ) : the_post(); ?>       

            <?php if( function_exists('yoast_breadcrumb') ){ yoast_breadcrumb('<div class="breadcrumbs">','</div>'); } ?>

            <h1 class='isAnimated'><?php the_title(); ?></h1>

            <?php if( get_field('video') ): ?>
                <div class='video js-video' data-id='<?php the_field('video'); ?>'>
                    <div class='iframe'></div>
                    <div class='overlay' style='background-image: url(<?php echo wp_get_attachment_url( get_field('video_img'), 'full'); ?>)'>
                        <div>
                            <div class='subtitle'>
                                <svg class='icon icon-video'><use xlink:href='#icon-video'></use></svg>
                                <span><span class='name'><?php the_field('name'); ?>,</span> <?php the_field('job'); ?></span>
                            </div>
                            <h3 <?php $subtitleImg = get_field('title_img'); if( $subtitleImg ){ ?> class='no-text' <?php } ?>>
                                <?php the_field('title'); ?>
                                <?php echo wp_get_attachment_image( $subtitleImg, 'full' ); ?>
                            </h3>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if( get_field('video') ){ ?>
                <!--<div class='video'>
                    <iframe id='videoWork' width='640' height='360' src='<?php the_field('video'); ?>?enablejsapi=1' frameborder='0'></iframe>
                    <div class='overlay'>
                        <div>
                            <div class='subtitle'>
                                <svg class='icon icon-video'><use xlink:href='#icon-video'></use></svg>
                                <span><span class='name'><?php the_field('name'); ?>,</span> <?php the_field('job'); ?></span>
                            </div>
                            <h3 <?php $subtitleImg = get_field('title_img'); if( $subtitleImg ){ ?> class='no-text' <?php } ?>>
                                <?php the_field('title'); ?>
                                <?php echo wp_get_attachment_image( $subtitleImg, 'full' ); ?>
                            </h3>
                        </div>
                    </div>
                </div>-->
            <?php } ?>

            <div class='clearfix isAnimated nav-next-prev'>
                <?php previous_post_link( '%link', '%title' ); ?>
                <?php next_post_link( '%link', '%title' ); ?>
            </div>

            <div class='work-content'>
                <?php the_content(); ?>
            </div>

        <?php else : ?>
                        
            <h1 class='isAnimated'>404</h1>

        <?php endif; ?>
    
    </div>

</div>

<?php get_footer(); ?>
