<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$blocks = get_sub_field('blocks');
$title = get_sub_field('title');
$content = get_sub_field('content');
$image = get_sub_field('image');

?>

<?php if (!empty($image)) : ?>
    <style>.highlighted:before{ background-image: url(<?php echo $image; ?>); }</style>
<?php endif; ?>

<?php if (!empty($anchor)) : ?><a name="<?php echo $anchor; ?>"></a><?php endif; ?>
<div class='highlighted <?php if (empty($image)) : ?> no-img <?php endif; ?>'>
    <?php if (!empty($title)) : ?><span class='title'><?php echo $title; ?></span><?php endif; ?>
    <?php if (!empty($content)) : ?><strong><?php echo $content; ?></strong><?php endif; ?>
</div>
