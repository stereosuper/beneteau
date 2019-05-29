<?php

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');

?>

<div class='push-wrapper'>
    <div class='push-banner'></div>
    <?php if (!empty($title)){ ?>
        <h2 id='<?php echo $anchor; ?>' class="align<?php echo $title_align; ?>"><?php echo $title; ?></h2>
    <?php }else if(!empty($anchor)){ ?>
        <div id='<?php echo $anchor; ?>'></div>
    <?php } ?>

    <?php if (super_have_rows('blocks')) : ?>
        <div class='push-container'>
            <?php while (super_have_rows('blocks')) :
                the_row();

                $title = get_sub_field('title');
                $image = get_sub_field('image');
                $image_url = '';
                if(is_array($image) && isset($image['ID'])) {
                    list($image_url, $w, $h) = wp_get_attachment_image_src($image['ID'], 'large');
                }
                $link = get_sub_field('link');
                $link_label = get_sub_field('link_label');
            ?>

                <a href='<?php echo $link; ?>'>
                    <div class='img-wrapper'>
                        <div class='img'>
                            <div class='inner'>
                                <?php if (empty($image_url)) { ?>
                                    <div class='bg'></div>
                                <?php }else{ ?>
                                    <img src='<?php echo $image_url; ?>' alt=''
                                ><?php } ?>
                            </div>
                        </div>
                    </div>

                    <!--<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink= "http://www.w3.org/1999/xlink">
                        <defs>
                            <clipPath id='clipImg' clipPathUnits="objectBoundingBox">
                                <path d='M0.1744899,0.1969967C0.0224832,0.2373544,0.0005927,0.2689686,0,0.4032062L0.00419,1 c0.0111598-0.0764089,0.0497083-0.1031455,0.1698911-0.1350859L1,0.6678032V0L0.1744899,0.1969967z'/>
                            </clipPath>
                        </defs>
                        <image xlink:href="<?php echo $image_url; ?>" clip-path="url(#clipImg)"/>
                    </svg>-->
                    <strong><?php echo $title; ?></strong>
                    <span class='link'><?php echo $link_label; ?></span>
                </a>
            <?php endwhile ?>
        </div>
    <?php endif; ?>
</div>
