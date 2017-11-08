<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');

?>

<div>
    <?php if (!empty($title)){ ?>
        <h2 id='<?php echo $anchor; ?>' class="align<?php echo $title_align; ?>"><?php echo $title; ?></h2>
    <?php }else if(!empty($anchor)){ ?>
        <div id='<?php echo $anchor; ?>'></div>
    <?php } ?>

    <?php if (super_have_rows('dates')) : ?>
        <ul class='history'>
            <?php while (super_have_rows('dates')) :
                the_row();

                $date = get_sub_field('date');
                $cat = get_sub_field('cat');
                $text = get_sub_field('text');
                $link = get_sub_field('link');
            ?>

                <li class='isAnimated'>
                    <time datetime='<?php echo $date; ?>'><?php echo $date; ?></time>
                    <?php if( !empty($cat) ) : ?>
                        <span class='cat'><?php echo $cat; ?></span>
                    <?php endif; ?>
                    <?php echo wp_get_attachment_image( get_sub_field('img'), 'full' ); ?>
                    <?php if( !empty($text) ) : echo $text; endif; ?>
                    <?php if( !empty($link) ) : ?>
                        <a href='<?php echo $link; ?>' class='link'><?php echo get_sub_field('linkText') ? get_sub_field('linkText') : $link; ?></a>
                    <?php endif; ?>
                </li>
            <?php endwhile ?>
        </ul>
    <?php endif; ?>
</div>
