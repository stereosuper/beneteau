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
            <div class='grid grid-brands js-brands'>
                <?php while (have_rows('blocks')) :
                    the_row();

                    $title = get_sub_field('title');
                    $image = get_sub_field('img');
                    $link = get_sub_field('link');
                ?>

                    <div class='col-3 brand'>
                        <a href='<?php echo $link; ?>'>
                            <div class='img'><?php echo wp_get_attachment_image( $image, 'full' ); ?></div>
                            <h3><?php echo $title; ?></h3>
                        </a>
                    </div>
                <?php endwhile ?>
            </div>
        <?php endif; ?>
    </div>
