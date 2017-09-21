<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$image = get_sub_field('image');
$image_url = '';
if(is_array($image) && isset($image['ID'])) {
    list($image_url, $w, $h) = wp_get_attachment_image_src($image['ID'], 'large');
}
$title = get_sub_field('title');
$content = get_sub_field('content');
$link = get_sub_field('link');
$link_label = get_sub_field('link_label');

?>

<div class='home-talent'>
    <?php if (!empty($anchor)) : ?><a name="<?php echo $anchor; ?>"></a><?php endif; ?>

    <?php if (!empty($image_url)) : ?><img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr($title); ?>"/><?php endif; ?>
    <div class="text">
        <?php if (!empty($title)) : ?><h2><?php echo $title; ?></h2><?php endif; ?>
        <p><?php echo $content; ?></p>
        <?php if (!empty($link)) : ?><a href='<?php echo $link; ?>' class='btn-invert'><?php echo $link_label; ?></a><?php endif; ?>
    </div>
</div>
