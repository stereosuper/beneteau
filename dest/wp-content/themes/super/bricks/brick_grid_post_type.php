<?php

// Champs possibles pour le row
$title = get_sub_field('title');
$titleImg = get_sub_field('tittle_main_img');
$img = get_sub_field('img');
$text = get_sub_field('text');
$job1 = get_sub_field('job1');
$job2 = get_sub_field('job2');
$job3 = get_sub_field('job3');
$btn = get_sub_field('btn');
$btnLink = get_sub_field('btn_link');
$postType = get_sub_field('post_type');

?>

<div class='grid-post-type'>
    <div class='grid-post-type-title'>
        <div class='content'>
            <h2 <?php if( $titleImg ){ ?> class='no-text' <?php } ?>>
                <?php echo $title; ?>
                <?php echo wp_get_attachment_image( $titleImg, 'large' ); ?>
            </h2>
            <p><?php echo $text; ?></p>
        </div>
        <?php echo wp_get_attachment_image( $img, 'large' ); ?>
    </div>

    <?php
    $queryPosts = new WP_Query( array('post_type' => $postType) );

    if( $queryPosts->have_posts() ) : ?>

        <div class='grid-post-type-cta'>
            <p>
                <?php echo $job1; ?>
                <span><?php echo $job2; ?></span>
                <?php echo $job3; ?>
            </p>
            <a href='<?php echo $btnLink; ?>' class='btn-invert'><?php echo $btn; ?></a>
        </div>

        <?php while( $queryPosts->have_posts() ) : ?>
            <?php $queryPosts->the_post(); $subtitleImg = get_field('title_img'); ?>

            <a href='<?php the_permalink(); ?>' class='grid-post-type-item'>
                <div class='content'>
                    <div class='subtitle'>
                        <svg class='icon icon-video'><use xlink:href='#icon-video'></use></svg>
                        <span>
                            <span class='name'><?php the_field('name'); ?></span>
                            <span><?php the_field('job'); ?></span>
                        </span>
                    </div>
                    <h3 <?php if( $subtitleImg ){ ?> class='no-text' <?php } ?>>
                        <?php the_field('title'); ?>
                        <?php echo wp_get_attachment_image( $subtitleImg, 'large' ); ?>
                    </h3>
                </div>
                <?php echo wp_get_attachment_image( get_field('video_img'), 'large' ); ?>
            </a>

        <?php endwhile; wp_reset_postdata(); ?>
    
    <?php endif; ?>
</div>
