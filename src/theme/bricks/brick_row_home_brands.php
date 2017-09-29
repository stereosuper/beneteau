<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$content = get_sub_field('content');
$link = get_sub_field('link');
$link_label = get_sub_field('link_label');

?>

<?php if (!empty($anchor)) : ?><a name="<?php echo $anchor; ?>"></a><?php endif; ?>

<div class='home-brands isAnimated'>
    <div class='text'>
        <h2><?php echo $title; ?></h2>
        <p><?php echo $content; ?></p>
        <?php if (!empty($link)) : ?><a href='<?php echo $link; ?>' class='btn-invert'><?php echo $link_label; ?></a><?php endif; ?>
    </div>
    <div class='home-carousel' id='brandsHome'>
        <ul>
            <?php
                // Cf. http://codex.wordpress.org/Class_Reference/WP_Query
                $query_args = array(
                    // Type Parameters
                    'post_type' => 'brand',
                    // Status Parameters
                    'post_status' => 'publish',
                    // Pagination Parameters
                    'posts_per_page' => -1
                );

                $brands_query = new WP_Query($query_args);
                if ($brands_query->have_posts()) : $count = 0;
                    while ($brands_query->have_posts()) :
                        $brands_query->the_post();
                        $logo = super_get_field('logo');
                        if (!empty($logo)) :
            ?>
                <li <?php if( $count > 8 ){ echo 'class="hidden"'; } ?>><img src="<?php echo $logo; ?>" alt="<?php echo esc_attr(get_the_title()); ?>" /></li>
            <?php 
                        endif; // if (!empty($logo)) :
                    $count ++; endwhile; // while ($brands_query->have_posts())
                    wp_reset_query();
                endif; // if ($brands_query->have_posts()) :
            ?>
        </ul>
    </div>
</div>
