<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');
$content = get_sub_field('content');

?>

<div class='clearfix'>
    <?php if (!empty($title)){ ?>
        <h2 id='<?php echo $anchor; ?>' class="align<?php echo $title_align; ?>"><?php echo $title; ?></h2>
    <?php }else if(!empty($anchor)){ ?>
        <div id='<?php echo $anchor; ?>'></div>
    <?php } ?>
    <?php if (!empty($content)) : ?><?php echo $content; ?><?php endif; ?>
</div>
