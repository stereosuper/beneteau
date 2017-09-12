<?php

// Champs possibles pour le row
$blocks = get_sub_field('blocks');
$title = get_sub_field('title');
$content = get_sub_field('content');

?>

<div class='highlighted'>
    <?php if (!empty($title)) : ?><span class='title'><?php echo $title; ?></span><?php endif; ?>
    <?php if (!empty($content)) : ?><strong><?php echo $content; ?></strong><?php endif; ?>
</div>
