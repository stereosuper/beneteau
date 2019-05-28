<div class="home-brands-services container">
    <?php
    if ($title = get_sub_field('title')): ?>
        <h2 class="home-brands-services-title"><?php echo $title ?></h2>
    <?php endif; ?>
    <?php if( have_rows('brands_and_services') ): ?>
    <ul class="home-brands-services-list">
        <?php while ( have_rows('brands_and_services') ) : the_row(); ?>
        <li>
            <?php if ($link = get_sub_field('link')): ?>
                <a class="home-brands-services-link" href="<?php echo $link['url']; ?>" title="<?php echo htmlspecialchars(strip_tags($link['title']), ENT_QUOTES); ?>" target="<?php echo $link['target']; ?>" <?php echo $link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                    <figure>
                        <?php 
                        if ($image = get_sub_field('image')) {
                            echo wp_get_attachment_image($image['ID'], 'full');
                        }
                        ?>
                    </figure>
                    <p class="a-btn"><?php echo $link['title'] ?></p>
                </a>
            <?php endif; ?>
        </li>
        <?php endwhile; ?>
    </ul>
    <?php endif; ?>
</div>
