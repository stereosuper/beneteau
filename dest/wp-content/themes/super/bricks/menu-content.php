<?php if( get_row_layout() == 'text' ) : ?>
    <p class='text'><?php the_sub_field('text', 'options'); ?></p>
<?php endif; ?>

<?php if( get_row_layout() == 'menu_with_submenu' ) : ?>
    <div>
        <span class="js-accordion-button accordion-button" role="button" aria-expanded="false" tabindex="0"><?php the_sub_field('title', 'options'); ?></span>
        <?php if( have_rows('links', 'options') ) : ?>
            <div class="sub-menu-wrapper js-sub-menu-wrapper">
                <ul class="sub-menu js-sub-menu">
                    <?php while( have_rows('links', 'options') ) : the_row(); ?>
                        <?php $link = get_sub_field('link', 'options'); ?>
                        <li class="third-level">
                            <a href="<?php echo $link['url']; ?>">
                                <?php echo $link['title']; ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if( get_row_layout() == 'menu' ) : ?>
    <?php if( have_rows('links', 'options') ) : ?>
        <ul class="sub-menu js-sub-menu">
            <?php while( have_rows('links', 'options') ) : the_row(); ?>
                <?php $link = get_sub_field('link', 'options'); ?>
                <li class="third-level">
                    <a href="<?php echo $link['url']; ?>">
                        <?php echo $link['title']; ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
<?php endif; ?>

<?php if( get_row_layout() == 'img_links' ) : ?>
    <div class='menu-img-link' style='background-image:url(<?php echo wp_get_attachment_image_url(get_sub_field('img', 'options'), 'large'); ?>)'>
        <h2><?php echo wp_get_attachment_image(get_sub_field('title', 'options'), 'medium'); ?></h2>
        <?php $link = get_sub_field('link_pdf', 'options'); if( $link ){ ?>
            <div>
                <svg class="icon" aria-hidden="true" focusable="false"><use href="#icon-doc" /></svg>
                <a href="<?php echo $link['url']; ?>">
                    <?php echo $link['title']; ?>
                </a>
            </div>
        <?php } ?>
        <?php $video = get_sub_field('link_video', 'options'); if( $video ){ ?>
            <div>
                <svg class="icon icon-video" aria-hidden="true" focusable="false"><use href="#icon-video2" /></svg>
                <a href="<?php echo $video['url']; ?>">
                    <?php echo $video['title']; ?>
                </a>
            </div>
        <?php } ?>
    </div>
<?php endif; ?>