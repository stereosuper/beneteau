<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');
$address = get_sub_field('address');
$phone = get_sub_field('phone');

?>

<div class='contact-address isAnimated'>
<?php if (!empty($title)){ ?>
        <h2 id='<?php echo $anchor; ?>' class="align<?php echo $title_align; ?>"><?php echo $title; ?></h2>
    <?php }else if(!empty($anchor)){ ?>
        <div id='<?php echo $anchor; ?>'></div>
    <?php } ?>

    <?php if (!empty($address)) : ?><div class='address'><?php echo $address; ?></div><?php endif; ?><?php if (!empty($phone)) : ?><div class='tel'><p><svg class='icon'><use xlink:href='#icon-tel'></use></svg><?php echo $phone; ?></p></div><?php endif; ?>
</div>
