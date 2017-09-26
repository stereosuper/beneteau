<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');
$content = get_sub_field('content');


?>

<h2 class='align<?php echo $title_align; ?>'><?php echo $title; ?></h2>

<div class='home-news'>
    <div class='push-container'>
        <?php
            // Cf. http://codex.wordpress.org/Class_Reference/WP_Query
            $query_args = array(
                // Type Parameters
                'post_type' => 'post',
                // Status Parameters
                'post_status' => 'publish',
                // Pagination Parameters
                'posts_per_page' => 2,
            );

            $news_query = new WP_Query($query_args);
            if ($news_query->have_posts()) :
                while ($news_query->have_posts()) :
                    $news_query->the_post();
        ?>
        <a href='<?php the_permalink() ?>'>
            <div class='img-wrapper'>
                <div class='img'>
                    <div class='bg'></div>
                    <?php the_post_thumbnail('medium'); ?>
                </div>
            </div>
            <time><?php the_time(__('d/m/Y', 'beneteau')); ?></time>
            <h3><?php the_title(); ?></h3>
        </a>
        <?php
                endwhile; // while ($news_query->have_posts())
                wp_reset_query();
            endif; // if ($news_query->have_posts())
        ?>
    </div>

    <div class='text isAnimated'>
        <p><?php echo $content; ?></p>
        <a href='<?php echo (get_option('page_for_posts')?get_permalink(get_option('page_for_posts')):get_site_url()); ?>' class='link'><?php _e('Toutes les actualités', 'beneteau'); ?></a>
    </div>
</div>
