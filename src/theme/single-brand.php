<?php get_header(); ?>

    <?php if ( have_posts() ) : ?>

        <?php while ( have_posts() ) : the_post(); ?>

            <article>

                <?php
                    // Markup compatible flexslider
                    $images = super_get_field('gallery');
                    if( $images ):
                ?>
                    <div id="slider" class="flexslider">
                        <ul class="slides">
                            <?php foreach( $images as $image ): ?>
                                <li>
                                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                    <p><?php echo $image['caption']; ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
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

                <?php
                    if ( function_exists('yoast_breadcrumb') ) {
                        yoast_breadcrumb('<p id="breadcrumbs">','</p>');
                    }
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
                <a href="<?php echo $website; ?>" target="_blank" class="shame dont-tell-arnaudban"><?php echo (isset($parsed_website['host']))?$parsed_website['host']:$website; ?></a>
                <?php
                    endif;
                ?>

                <?php previous_post_link( '<strong>%link</strong>' ); ?>
                <?php next_post_link( '<strong>%link</strong>' ); ?>

            </article>

        <?php endwhile; ?>


    <?php else : ?>

        <article>
            <h1>404</h1>
        </article>

    <?php endif; ?>

<?php get_footer(); ?>
