<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title2 = get_sub_field('title2');

$count = 0;

?>

<div class='contact-address isAnimated'>
    <div>
        <?php if (!empty($title)){ ?>
            <h2 id='<?php echo $anchor; ?>' tabindex='0'><?php echo $title; ?></h2>
        <?php }else if(!empty($anchor)){ ?>
            <div id='<?php echo $anchor; ?>' tabindex='0'></div>
        <?php } ?>
        
        <?php if( have_rows('colonnes') ): ?>
            <?php while ( have_rows('colonnes') ) : the_row(); ?>
                <?php if($count < 1) : ?>
                    <?php if( get_sub_field('img') ) : ?><div class="img"><?php echo wp_get_attachment_image( get_sub_field('img'), 'full' ); ?></div><?php endif; ?>
                    <?php if( get_sub_field('title') ) : ?><h3><?php the_sub_field('title'); ?></h3><?php endif; ?>
                    <?php if( get_sub_field('address') ) : ?><div class='address'><?php the_sub_field('address'); ?></div><?php endif; ?>
                    <?php if( get_sub_field('phone') ) : ?><div class='tel'><p><svg class='icon'><use xlink:href='#icon-tel'></use></svg><?php the_sub_field('phone'); ?></p></div><?php endif; ?>
                <?php endif; ?>
            <?php $count ++; endwhile; ?>
        <?php endif; $count = 0; ?>
    </div>
    <div>
        <?php if (!empty($title2)){ ?>
            <h2><?php echo $title2; ?></h2>
        <?php } ?>
        <div class="grid grid-contact">
            <?php if( have_rows('colonnes') ): ?>
                <?php while ( have_rows('colonnes') ) : the_row(); ?>
                    <?php if($count > 0) : ?>
                        <div class="col-2 isAnimated">
                            <?php if( get_sub_field('img') ) : ?><div class="img"><?php echo wp_get_attachment_image( get_sub_field('img'), 'full' ); ?></div><?php endif; ?>
                            <?php if( get_sub_field('title') ) : ?><h3><?php the_sub_field('title'); ?></h3><?php endif; ?>
                            <?php if( get_sub_field('address') ) : ?><div class='address'><?php the_sub_field('address'); ?></div><?php endif; ?>
                            <?php if( get_sub_field('phone') ) : ?><div class='tel'><p><svg class='icon'><use xlink:href='#icon-tel'></use></svg><?php the_sub_field('phone'); ?></p></div><?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php $count ++; endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
