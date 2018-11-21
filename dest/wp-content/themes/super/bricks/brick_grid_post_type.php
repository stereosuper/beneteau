<?php

// Champs possibles pour le row
$title = get_sub_field('title');
$titleImg = get_sub_field('title_img');
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
        <h2 data-url='<?php echo $titleImg; ?>'><?php echo $title; ?></h2>
        <p><?php echo $text; ?></p>
        <?php echo wp_get_attachment_image( $img, 'full' ); ?>
    </div>

    <?php
    $queryPosts = new WP_Query( array('post_type' => $postType) );

    if( $queryPosts->have_posts() ) : $count = 0; ?>

    <?php while( $queryPosts->have_posts() ) : $count ++; ?>
            <?php $queryPosts->the_post(); ?>

            <a href='<?php the_permalink(); ?>' class='grid-post-type-item'>
                <div class='content'>
                    <?php the_field('name'); ?>
                    <span><?php the_field('job'); ?></span>
                    <h3 data-url='<?php the_field('title_img'); ?>'><?php the_title(); ?></h3>
                </div>
                <?php echo wp_get_attachment_image( get_field('video_img'), 'medium' ); ?>  
            </a>

            <a href='<?php the_permalink(); ?>' class='grid-post-type-item'>
                <div class='content'>
                    <?php the_field('name'); ?>
                    <span><?php the_field('job'); ?></span>
                    <h3 data-url='<?php the_field('title_img'); ?>'><?php the_title(); ?></h3>
                </div>
                <?php echo wp_get_attachment_image( get_field('video_img'), 'medium' ); ?>
            </a>

            <div class='grid-post-type-cta grid-post-type-item'>
                <p>
                    <?php echo $job1; ?>
                    <span><?php echo $job2; ?></span>
                    <?php echo $job3; ?>
                </p>
                <a href='<?php echo $btnLink; ?>' class='btn-invert'><?php echo $btn; ?></a>
            </div>

            <a href='<?php the_permalink(); ?>' class='grid-post-type-item'>
                <div class='content'>
                    <?php the_field('name'); ?>
                    <span><?php the_field('job'); ?></span>
                    <h3 data-url='<?php the_field('title_img'); ?>'><?php the_title(); ?></h3>
                </div>
                <?php echo wp_get_attachment_image( get_field('video_img'), 'medium' ); ?>
            </a>
            <a href='<?php the_permalink(); ?>' class='grid-post-type-item'>
                <div class='content'>
                    <?php the_field('name'); ?>
                    <span><?php the_field('job'); ?></span>
                    <h3 data-url='<?php the_field('title_img'); ?>'><?php the_title(); ?></h3>
                </div>
                <?php echo wp_get_attachment_image( get_field('video_img'), 'medium' ); ?>
            </a>
            <a href='<?php the_permalink(); ?>' class='grid-post-type-item'>
                <div class='content'>
                    <?php the_field('name'); ?>
                    <span><?php the_field('job'); ?></span>
                    <h3 data-url='<?php the_field('title_img'); ?>'><?php the_title(); ?></h3>
                </div>
                <?php echo wp_get_attachment_image( get_field('video_img'), 'medium' ); ?>
            </a>
            <a href='<?php the_permalink(); ?>' class='grid-post-type-item'>
                <div class='content'>
                    <?php the_field('name'); ?>
                    <span><?php the_field('job'); ?></span>
                    <h3 data-url='<?php the_field('title_img'); ?>'><?php the_title(); ?></h3>
                </div>
                <?php echo wp_get_attachment_image( get_field('video_img'), 'medium' ); ?>
            </a>
            <a href='<?php the_permalink(); ?>' class='grid-post-type-item'>
                <div class='content'>
                    <?php the_field('name'); ?>
                    <span><?php the_field('job'); ?></span>
                    <h3 data-url='<?php the_field('title_img'); ?>'><?php the_title(); ?></h3>
                </div>
                <?php echo wp_get_attachment_image( get_field('video_img'), 'medium' ); ?>
            </a>

        <?php endwhile; wp_reset_postdata(); ?>
    
    <?php endif; ?>
</div>
