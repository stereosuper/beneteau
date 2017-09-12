<?php

// Champs possibles pour le row
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');
$content = get_sub_field('content');

?>

    <div class='clearfix'>
        <?php if (!empty($title)) : ?><h2 class="align<?php echo $title_align; ?>"><?php echo $title; ?></h2><?php endif; ?>
        <?php if (!empty($content)) : ?><?php echo $content; ?><?php endif; ?>
    </div>
