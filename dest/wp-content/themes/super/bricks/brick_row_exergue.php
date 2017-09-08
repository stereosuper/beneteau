<?php

// Champs possibles pour le row
$blocks = get_sub_field('blocks');
$content = get_sub_field('content');

?>

    <div class="brick-row brick-row-exergue">
        <?php if (!empty($title)) : ?><h2><?php echo $title; ?></h2><?php endif; ?>
        <?php if (!empty($content)) : ?><?php echo $content; ?><?php endif; ?>
    </div>
