<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');
$address = get_sub_field('address');
$phone = get_sub_field('phone');

?>

<?php if (!empty($anchor)) : ?><a name="<?php echo $anchor; ?>"></a><?php endif; ?>
<?php if (!empty($title)) : ?><h2 class="align<?php echo $title_align; ?>"><?php echo $title; ?></h2><?php endif; ?>

<div class='grid'>
    <?php if (!empty($address)) : ?><div class="col-2"><?php echo $address; ?></div><?php endif; ?>
    <?php if (!empty($phone)) : ?><div class="col-2 phone"><?php echo $phone; ?></div><?php endif; ?>
</div>
