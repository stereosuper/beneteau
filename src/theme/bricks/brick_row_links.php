<?php

// Champs possibles pour le row
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');

?>

<div class='push-wrapper'>
	<div class='container'>
        <?php if (!empty($title)) : ?><h2 class="align<?php echo $title_align; ?>"><?php echo $title; ?></h2><?php endif; ?>
    </div>
    <?php
        if (have_rows('blocks')) :
    ?>
    <div class='container push-container'>
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
            <a href='<?php echo $link; ?>' class='push'>
                <div class='img'>
                <?php if (!empty($image_url)) : ?><img src='<?php echo $image_url; ?>' alt='<?php echo $title; ?>'><?php endif; ?>
				</div>
                <strong><?php echo $title; ?></strong>
				<span class='link'><?php echo $link_label; ?></span>
                
            </a>
        <?php
                endwhile
        ?>
    </div>
    <?php
        endif;
    ?>
    </div>
</div>
