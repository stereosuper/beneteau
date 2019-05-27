<div class="home-offers container">
    <?php
    if ($image = get_sub_field('image')): ?>
        <figure class="home-offers-image">
            <?php echo wp_get_attachment_image($image['ID'], 'full'); ?>
            <?php endif; ?>
        </figure>
    <?php if ($content = get_sub_field('content')): ?>
        <p class="home-offers-description"><?php echo $content ?></p>
    <?php endif; ?>
    <?php 
    $title_first_part = get_sub_field('title_first_part');
    $title_second_part = get_sub_field('title_second_part');
    $title_third_part = get_sub_field('title_third_part');
    if ($title_first_part && $title_second_part && $title_third_part):
    ?>
        <h2 class="home-offers-title">
            <?php echo $title_first_part ?>
            <span><?php echo $title_second_part ?></span>
            <?php echo $title_third_part ?>
        </h2>
    <?php endif; ?>
    <?php if ($link = get_sub_field('link')): ?>
        <a class="home-offers-link" href="<?php echo $link['url']; ?>" title="<?php echo htmlspecialchars(strip_tags($link['title']), ENT_QUOTES); ?>" target="<?php echo $link['target']; ?>" <?php echo $link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
        <?php echo $link['title'] ?>
        </a>
    <?php endif; ?>
</div>
