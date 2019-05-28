<?php 
$image = get_sub_field('image');
?>
<div class="home-group">
    <div class="home-group-content container">
        <?php if ($title = get_sub_field('title')): ?>
            <h2 class="home-group-title"><?php echo $title ?></h2>
        <?php endif; ?>
        <?php if ($content = get_sub_field('content')): ?>
            <p class="home-group-description"><?php echo $content ?></p>
        <?php endif; ?>
        <?php if ($link = get_sub_field('link')): ?>
            <p>
                <a class="a-btn a-btn-light home-group-link" href="<?php echo $link['url']; ?>" title="<?php echo htmlspecialchars(strip_tags($link['title']), ENT_QUOTES); ?>" target="<?php echo $link['target']; ?>" <?php echo $link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                <?php echo $link['title'] ?>
                </a>
            </p>
        <?php endif; ?>
        <div class="home-group-content-background" style="background-image: url('<?php echo $image['url'] ?>')"></div>
    </div>
    <?php if(have_rows('numbers')):?>
    <div class="home-group-numbers container">
        <ul>
            <?php while ( have_rows('numbers') ) : the_row(); ?>
            <li class="home-group-number">
                <div class="number-wrapper">
                    <?php if ($number = get_sub_field('number')): ?>
                    <span class="number"><?php echo $number ?></span>
                    <?php endif; ?>
                    <?php if ($text = get_sub_field('text')): ?>
                    <span class="text"><?php echo $text ?></span>
                    <?php endif; ?>
                </div>
            </li>
            <?php endwhile; ?>
        </ul>
        <div class="home-group-numbers-background" style="background-image: url('<?php echo $image['url'] ?>')"></div>
    </div>
    <?php endif; ?>
    <div class="home-group-background" style="background-image: url('<?php echo $image['url'] ?>')"></div>
</div>
