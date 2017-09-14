<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$blocks = get_sub_field('blocks');
$title = get_sub_field('title');
$content = get_sub_field('content');

?>


<?php if (!empty($anchor)) : ?><a name="<?php echo $anchor; ?>"></a><?php endif; ?>
<div class='highlighted'>
    <?php if (!empty($title)) : ?><span class='title'><?php echo $title; ?></span><?php endif; ?>
    <?php if (!empty($content)) : ?><strong><?php echo $content; ?></strong><?php endif; ?>
</div>
