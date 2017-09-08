<?php

// Champs possibles pour le row
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');
$left_col_content = get_sub_field('left_col_content');
$right_col_content = get_sub_field('right_col_content');

?>

<div class='container'>
    <?php if (!empty($title)) : ?><h2 class="align<?php echo $title_align; ?>"><?php echo $title; ?></h2><?php endif; ?>
    <div class='grid'>
        <?php if (!empty($left_col_content)) : ?><div class="col-2"><?php echo $left_col_content; ?></div><?php endif; ?>
        <?php if (!empty($right_col_content)) : ?><div class="col-2"><?php echo $right_col_content; ?></div><?php endif; ?>
    </div>
</div>
