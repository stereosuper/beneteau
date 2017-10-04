<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');

?>
    <div class='isAnimated'>
        <?php if (!empty($title)){ ?>
            <h2 id='<?php echo $anchor; ?>' class="align<?php echo $title_align; ?>"><?php echo $title; ?></h2>
        <?php }else if(!empty($anchor)){ ?>
            <div id='<?php echo $anchor; ?>'></div>
        <?php } ?>

        <?php if (have_rows('blocks')) : ?>
            <div class='grid grid-brands'>
                <?php while (have_rows('blocks')) :
                    the_row();

                    $title = get_sub_field('title');
                    $image = get_sub_field('image');
                    $image_url = '';
                    if(is_array($image) && isset($image['ID'])) {
                        list($image_url, $w, $h) = wp_get_attachment_image_src($image['ID'], 'medium');
                    }
                    $link = get_sub_field('link');
                    $content = get_sub_field('content');
                ?>

                    <div class='col-3'>
                        <h3><?php echo $title; ?></h3>
                        <?php if (!empty($content)) : ?><?php echo $content; ?><?php endif; ?>
                        <a href='<?php echo $link; ?>' class='btn-invert-block'><?php _e('Contacter', 'beneteau'); ?></a>
                    </div>
                <?php endwhile ?>
            </div>
        <?php endif; ?>
    </div>
