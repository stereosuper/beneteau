<?php if( have_rows('menus', 'options') ) : ?>
    <ul id='menu-main' class='menu-main'>
        <?php while( have_rows('menus', 'options') ) : the_row(); ?>
            <li class='first-level'>
                <?php if( get_sub_field('page', 'options') ){ ?>
                    <a href='<?php the_sub_field('page', 'options'); ?>'><?php the_sub_field('title', 'options'); ?></a>
                <?php }else if( get_sub_field('link', 'options') ){ ?>
                    <a href='<?php the_sub_field('link', 'options'); ?>'><?php the_sub_field('title', 'options'); ?></a>
                <?php }else{ ?>
                    <button role='button' type='button' aria-expanded='false' class='js-menu-btn'><?php the_sub_field('title', 'options'); ?></button>
                <?php } ?>

                <hr>

                <?php if( have_rows('content1', 'options') ) : ?>
                    <div class='menu-content'>
                        <div class='menu-container'>
                            <div>
                                <?php while( have_rows('content1', 'options') ) : the_row();
                                    get_template_part('bricks/menu-content');
                                endwhile; ?>
                            </div>

                            <?php if( have_rows('content2', 'options') ) : ?>
                                <div>
                                    <?php while( have_rows('content2', 'options') ) : the_row();
                                        get_template_part('bricks/menu-content');
                                    endwhile; ?>
                                </div>
                            <?php endif; ?>

                            <?php if( have_rows('content3', 'options') ) : ?>
                                <div>
                                    <?php while( have_rows('content3', 'options') ) : the_row();
                                        get_template_part('bricks/menu-content');
                                    endwhile; ?>
                                </div>
                            <?php endif; ?>

                            <?php if( have_rows('content4', 'options') ) : ?>
                                <div>
                                    <?php while( have_rows('content4', 'options') ) : the_row();
                                        get_template_part('bricks/menu-content');
                                    endwhile; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </li>
        <?php endwhile; ?>
    </ul>
<?php endif; ?>