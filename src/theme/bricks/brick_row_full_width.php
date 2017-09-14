<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');
$content = get_sub_field('content');

?>

    <?php if (!empty($anchor)) : ?><a name="<?php echo $anchor; ?>"></a><?php endif; ?>
    <div class='clearfix'>
        <?php if (!empty($title)) : ?><h2 class="align<?php echo $title_align; ?>"><?php echo $title; ?></h2><?php endif; ?>
        <?php if (!empty($content)) : ?><?php echo $content; ?><?php endif; ?>
    </div>
