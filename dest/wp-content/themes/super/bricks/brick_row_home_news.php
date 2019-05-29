<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');
$content = get_sub_field('content');
$link = get_sub_field('link');
$btn = get_sub_field('btn');

?>


<div class="home-news container">
    <header>
        <?php if ($title): ?>
            <h2 class="home-news-title"><?php echo $title; ?></h2>
        <?php endif; ?>
        <?php if ($title_social_networks = get_sub_field('title_social_networks')): ?>
            <h3 class="home-news-title-social"><?php echo $title_social_networks ?></h3>
        <?php endif; ?>
        <div class="home-news-social">
            <?php if ($twitter_link = get_sub_field('twitter_link')): ?>
                <a href="<?php echo $twitter_link ?>">
                    <svg class="icon" aria-hidden="true" focusable="false"><use href="#icon-twitter" /></svg>
                </a>
            <?php endif; ?>
            <?php if ($linkedin_link = get_sub_field('linkedin_link')): ?>
                <a href="<?php echo $linkedin_link ?>">
                    <svg class="icon" aria-hidden="true" focusable="false"><use href="#icon-linkedin" /></svg>
                </a>
            <?php endif; ?>
        </div>
    </header>
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
        if ($news_query->have_posts()) : ?>
        <ul class="home-news-list">
            <?php while ($news_query->have_posts()) :
                $news_query->the_post();
                $img_uri = false;
                $wztp_uri = false;
                if (class_exists(('WiztopicSync'))) {
                    $wztp_uri =  WiztopicSync::getPermalink($post->ID);
                    $img_uri = WiztopicSync::getMediaPermalink($post->ID, 'medium');
                }
        ?>
            <li class="home-news-item">
                <a class="content-wrapper" href='<?php echo ($wztp_uri)?$wztp_uri:'#';  ?>'>
                    <span class="time"><?php the_time(__('d/m/Y', 'beneteau')); ?></span>
                    <span class="title"><?php the_title(); ?></span>
                </a>
                <figure class='img-wrapper'>
                    <img src="<?php echo $img_uri; ?>" alt="<?php echo htmlspecialchars(esc_attr(get_the_title()), ENT_QUOTES); ?>" />
                </figure>
            </li>
        <?php
            endwhile;
            wp_reset_query();
        ?>
        </ul>
    <?php endif; ?>
    <div class='home-news-link isAnimated'>
        <?php if( $link && $btn ) : ?>
            <a href='<?php echo $link['url'] ?>' class='a-btn'><?php echo $btn; ?></a>
        <?php endif; ?>
    </div>
</div>
