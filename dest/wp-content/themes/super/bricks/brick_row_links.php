<?php

// Champs possibles pour le row
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');

?>

    <div class="brick-row brick-row-links">
        <?php if (!empty($title)) : ?><h2 class="align<?php echo $title_align; ?>"><?php echo $title; ?></h2><?php endif; ?>
        <?php
            if (have_rows('blocks')) :
        ?>
        <ul>
        <?php
                while (have_rows('blocks')) :
                    the_row();

                    $title = get_sub_field('title');
                    $image = get_sub_field('image');
                    $image_url = '';
                    if(is_array($image) && isset($image['ID'])) {
                        list($image_url, $w, $h) = wp_get_attachment_image_src($image['ID'], 'medium');
                    }
                    $link = get_sub_field('link');
                    $link_label = get_sub_field('link_label');
        ?>
            <li>
                <?php if (!empty($image_url)) : ?><img src="<?php echo $image_url; ?>" alt="<?php echo $title; ?>" /><?php endif; ?>
                <?php if (!empty($link)) : ?><a href="<?php echo $link; ?>"><?php echo (!empty($title))?$title:$link; ?></a><?php endif; ?>
            </li>
        <?php
                endwhile
        ?>
        </ul>
        <?php
            endif;
        ?>
    </div>
