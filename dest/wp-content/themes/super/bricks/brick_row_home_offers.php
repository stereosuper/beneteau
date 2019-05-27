<div class="home-offers container">
    <?php
    if ($image_offers = get_sub_field('image_offers')): ?>
        <h2 class="home-offers-image">
            <?php echo wp_get_attachment_image($image_offers['ID'], 'full'); ?>
        </h2>
    <?php endif; ?>
    <?php if ($content_offers = get_sub_field('content_offers')): ?>
        <p class="home-offers-description"><?php echo $content_offers ?></p>
    <?php endif; ?>
    <?php 
    $title_offers_first_part = get_sub_field('title_offers_first_part');
    $title_offers_second_part = get_sub_field('title_offers_second_part');
    $title_offers_third_part = get_sub_field('title_offers_third_part');
    if ($title_offers_first_part && $title_offers_second_part && $title_offers_third_part):
    ?>
        <h3 class="home-offers-title">
            <?php echo $title_offers_first_part ?>
            <span><?php echo $title_offers_second_part ?></span>
            <?php echo $title_offers_third_part ?>
        </h3>
    <?php endif; ?>
    <?php if ($link_offers = get_sub_field('link_offers')): ?>
        <p>
            <a class="home-offers-link" href="<?php echo $link_offers['url']; ?>" title="<?php echo htmlspecialchars(strip_tags($link_offers['title']), ENT_QUOTES); ?>" target="<?php echo $link_offers['target']; ?>" <?php echo $link_offers['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
            <?php echo $link_offers['title'] ?>
            </a>
        </p>
    <?php endif; ?>
</div>
<div class="home-professions">
    <?php if ($video_professions_id = get_sub_field('video_professions_id')): ?>
        <div class="js-video" data-id="<?php echo $video_professions_id ?>">
            <div class="iframe"></div>
            <figure class="overlay">
                <?php if ($video_professions_cover = get_sub_field('video_professions_cover')): ?>
                <?php echo wp_get_attachment_image($video_professions_cover['ID'], 'full'); ?>
                <?php endif; ?>
            </figure>
            <?php if ($video_professions_url = get_sub_field('video_professions_url')): ?>
                <div class="video-alt">
                    <a href="<?php echo $video_professions_url ?>"><?php _e('Accéder à la vidéo avec transcript', 'precogs') ?></a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if ($title_professions = get_sub_field('title_professions')): ?>
        <h3 class="home-professions-title"><?php echo $title_professions ?></h3>
    <?php endif; ?>
    <?php if ($content_professions = get_sub_field('content_professions')): ?>
        <p class="home-professions-description"><?php echo $content_professions ?></p>
    <?php endif; ?>
    <?php if ($link_professions = get_sub_field('link_professions')): ?>
        <p>
            <a class="home-professions-link" href="<?php echo $link_professions['url']; ?>" title="<?php echo htmlspecialchars(strip_tags($link_professions['title']), ENT_QUOTES); ?>" target="<?php echo $link_professions['target']; ?>" <?php echo $link_professions['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
            <?php echo $link_professions['title'] ?>
            </a>
        </p>
    <?php endif; ?>
</div>

