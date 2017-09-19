<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');

?>

<?php if (!empty($anchor)) : ?><a name="<?php echo $anchor; ?>"></a><?php endif; ?>
<div class='push-wrapper'>
    <?php if (!empty($title)) : ?>
        <h2 class="align<?php echo $title_align; ?>"><?php echo $title; ?></h2>
    <?php endif; ?>

    <?php if (have_rows('blocks')) : ?>
        <div class='push-container'>
            <?php while (have_rows('blocks')) :
                the_row();

                $title = get_sub_field('title');
                $image = get_sub_field('image');
                $image_url = '';
                if(is_array($image) && isset($image['ID'])) {
                    list($image_url, $w, $h) = wp_get_attachment_image_src($image['ID'], 'medium');
                }
                $link = get_sub_field('link');
                $link_label = get_sub_field('link_label');
                $content = get_sub_field('content');
            ?>

                <a href='<?php echo $link; ?>'>
                    <div class='img'>
                        <?php if (!empty($image_url)) : ?><img src='<?php echo $image_url; ?>' alt='<?php echo $title; ?>'><?php endif; ?>
                    </div>
                    <strong><?php echo $title; ?></strong>
                    <?php if (!empty($content)) : ?><p><?php echo $content; ?></p><?php endif; ?>
                    <span class='link'><?php echo $link_label; ?></span>
                </a>
            <?php endwhile ?>
        </div>
    <?php endif; ?>
</div>
