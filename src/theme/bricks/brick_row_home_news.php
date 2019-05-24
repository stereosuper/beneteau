<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');
$content = get_sub_field('content');
$link = get_sub_field('link');
$btn = get_sub_field('btn');


?>

<h2 class='home-news-title'><?php echo $title; ?></h2>

<div class='home-news'>
    <div class='home-news-items'>
        <?php
            global $post;

            // Cf. http://codex.wordpress.org/Class_Reference/WP_Query
            $query_args = array(
                // Type Parameters
                'post_type' => 'post',
                // Status Parameters
                'post_status' => 'publish',
                // Pagination Parameters
                'posts_per_page' => 3,
            );

            $news_query = new WP_Query($query_args);
            if ($news_query->have_posts()) :
                while ($news_query->have_posts()) :
                    $news_query->the_post();
                    $img_uri = false;
                    $wztp_uri = false;
                    if (class_exists(('WiztopicSync'))) {
                        $wztp_uri =  WiztopicSync::getPermalink($post->ID);
                        $img_uri = WiztopicSync::getMediaPermalink($post->ID, 'medium');
                    }
        ?>
        <a href='<?php echo ($wztp_uri)?$wztp_uri:'#';  ?>'>
            
            <div class='img-wrapper'>
                <div class='img'>
                    <div class='inner'>
                        <?php if (empty($img_uri)) { ?>
                            <div class='bg'></div>
                        <?php }else{ ?>
                            <img src="<?php echo $img_uri; ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
                        ><?php } ?>
                    </div>
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
        <?php if( $link && $btn ) : ?>
            <a href='<?php echo $link; ?>' class='link'><?php echo $btn; ?></a>
        <?php endif; ?>
    </div>
</div>
