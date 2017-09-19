<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');

?>

<?php if (!empty($anchor)) : ?><a name="<?php echo $anchor; ?>"></a><?php endif; ?>

    <?php if (!empty($title)) : ?>
        <h2 class="align<?php echo $title_align; ?>"><?php echo $title; ?></h2>
    <?php endif; ?>

    <?php if (have_rows('blocks')) : ?>
        <div class='grid grid-contact'>
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

                <div class='col-3'>
                    <div class='img'><?php if (!empty($image_url)) : ?><img src='<?php echo $image_url; ?>' alt='<?php echo $title; ?>'><?php endif; ?></div>
                    <h2><?php echo $title; ?></h2>
                    <?php if (!empty($content)) : ?><?php echo $content; ?><?php endif; ?>
                    <a href='<?php echo $link; ?>' class='btn-invert-block'><?php echo $link_label; ?></a>
                </div>
            <?php endwhile ?>
        </div>
    <?php endif; ?>
