<?php get_header(); ?>

    <?php if ( have_posts() ) : ?>

        <?php while ( have_posts() ) : the_post(); ?>

            <aside class='sidebar-brands'>
                <?php
                    // Markup compatible flexslider
                    $images = super_get_field('gallery');
                    if( $images ):
                ?>
                    <div id="carousel" class="flexslider">
                        <ul class="slides">
                            <?php foreach( $images as $image ): ?>
                                <li>
                                    <img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </aside>

            <article class='container clearfix'>

                <div class='content-half-right content-brands'>
                    <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div class="breadcrumbs">','</div>'); } ?>

                    <?php
                        $back_url = goliath_get_page_url_by_template('tpl-brands.php');
                        if (!empty($back_url)) :
                    ?>
                    <a href="<?php echo $back_url; ?>" class="close"><?php _e('Fermer', 'beneteau'); ?></a>
                    <?php
                        endif;
                    ?>

                    <h1>
                        <?php
                            $logo = super_get_field('logo');
                            if (!empty($logo)) :
                                echo $logo;
                        ?>
                        <span class="visually-hidden"><?php the_title(); ?></span>
                        <?php
                            else :
                        ?>
                            <?php the_title(); ?>
                        <?php
                            endif;
                        ?>
                    </h1>

                    <div class="baseline"><?php super_the_field('baseline'); ?></div>

                    <?php the_content(); ?>

                    <?php
                        $website = super_get_field('website');
                        if (!empty($website)) :
                            $parsed_website = parse_url($website);
                    ?>
                    <a href="<?php echo $website; ?>" target="_blank" class="shame dont-tell-arnaudban"><?php _e('Visitez ', 'beneteau'); echo (isset($parsed_website['host']))?$parsed_website['host']:$website; ?></a>
                    <?php
                        endif;
                    ?>

                    <?php previous_post_link( '<strong>%link</strong>' ); ?>
                    <?php next_post_link( '<strong>%link</strong>' ); ?>
                </div>
            </article>

        <?php endwhile; ?>


    <?php else : ?>

        <div class='container'>
            <h1>404</h1>
        </div>

    <?php endif; ?>

<?php get_footer(); ?>
