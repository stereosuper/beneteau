<?php get_header(); ?>

    <?php if ( have_posts() ) : ?>

        <?php while ( have_posts() ) : the_post(); ?>

            <aside class='sidebar-brands'>
                <?php
                    $images = super_get_field('gallery');
                    if( $images ): $count = 0;
                ?>
                    <div id="sliderBrand" class="brand-slider">
                        <?php foreach( $images as $image ): ?>
                            <?php if($count === 0){
                                echo wp_get_attachment_image( $image['id'], 'full', '', array('class' => 'on') );
                            }else{
                                echo wp_get_attachment_image( $image['id'], 'full' );
                            } ?>
                        <?php $count ++; endforeach; ?>
                    </div>
                <?php endif; ?>
            </aside>

            <article class='container clearfix'>

                <div class='content-half-right content-brand'>
                    <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div class="breadcrumbs">','</div>'); } ?>

                    <h1 class='exergue'>
                        <?php
                            $logo = super_get_field('logo');
                            if (!empty($logo)) :
                        ?><img src="<?php echo $logo; ?>" alt="<?php echo esc_attr(get_the_title()); ?>" /><?php endif; ?>
                        <span class="visually-hidden"><?php the_title(); ?></span>
                        <?php
                            if (empty($logo)) :
                        ?>
                            <?php the_title(); ?>
                        <?php
                            endif;
                        ?>
                    </h1>
                    
                    <?php
                        $baseline = super_get_field('baseline');
                        if( $baseline ) :
                    ?>
                        <p class="baseline"><?php echo $baseline; ?></p>
                    <?php endif; ?>

                    <div class='text isAnimated'><?php the_content(); ?></div>

                    <?php
                        $website = super_get_field('website');
                        if (!empty($website)) :
                            $brand_slug = str_replace(' ', '-', strtolower(get_the_title()));
                    ?>
                    <a href="<?php echo $website; ?>" target="_blank" class="btn isAnimated" onclick="ga('send', 'event', 'lien-sortant', 'LS-site-marque', 'clic-LS-MB-<?php echo $brand_slug; ?>')"><?php _e('Visit ', 'beneteau'); echo (super_get_field('websiteDisplay'))?super_get_field('websiteDisplay'):$website; ?></a>
                    <?php
                        endif;
                    ?>

                    <?php
                        $back_url = goliath_get_page_url_by_template('tpl-brands.php');
                        if (!empty($back_url)) :
                    ?>
                        <a href="<?php echo $back_url; ?>" class="btn-close"><?php _e('Close', 'beneteau'); ?></a>
                    <?php endif; ?>

                    <div class='clearfix isAnimated nav-next-prev'>
                        <?php previous_post_link( '%link', '%title', true, ' ', 'branch' ); ?>
                        <?php next_post_link( '%link', '%title', true, ' ', 'branch' ); ?>
                    </div>
                </div>
            </article>

        <?php endwhile; ?>


    <?php else : ?>

        <div class='container'>
            <h1>404</h1>
        </div>

    <?php endif; ?>

<?php get_footer(); ?>
