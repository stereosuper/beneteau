<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');

?>

<div class='push-wrapper'>
    <?php if (!empty($title)){ ?>
        <h2 id='<?php echo $anchor; ?>' class="align<?php echo $title_align; ?>" tabindex='0'><?php echo $title; ?></h2>
    <?php }else if(!empty($anchor)){ ?>
        <div id='<?php echo $anchor; ?>' tabindex='0'></div>
    <?php } ?>

    <?php if (super_have_rows('blocks')) : ?>
        <ul class="push-links-list">
            <?php while (super_have_rows('blocks')) :
                the_row();

                $title = get_sub_field('title');
                $image = get_sub_field('image');
                $image_url = '';
                if(is_array($image) && isset($image['ID'])) {
                    list($image_url, $w, $h) = wp_get_attachment_image_src($image['ID'], 'large');
                }
                $link = get_sub_field('link');
                $link_label = get_sub_field('link_label');
            ?>
            <li class="push-links-item">
                <p class="content-wrapper">
                    <a href='<?php echo $link; ?>'>
                        <!--<span class="time"><?php //the_time(__('d/m/Y', 'beneteau')); ?></span>-->
                        <span class="title"><?php echo $title; ?></span>
                        <span class="read"><?php echo $link_label; ?></span>
                    </a>
                </p>
                <figure class='img-wrapper'>
                    <img src="<?php echo $image_url; ?>" alt="" />
                </figure>
            </li>
            <?php endwhile ?>
        </ul>
    <?php endif; ?>
</div>
