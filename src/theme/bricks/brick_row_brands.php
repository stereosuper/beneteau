<?php
global $post;

// Champs possibles pour le row
$anchor = get_sub_field('anchor');
$title = get_sub_field('title');
$title_align = get_sub_field('title_align');

?>

<?php if (!empty($anchor)) : ?><a name="<?php echo $anchor; ?>"></a><?php endif; ?>
<div class='push-wrapper'>
    <?php if (!empty($title)) : ?>
        <h2 class="align<?php echo $title_align; ?>"><?php echo $title; ?></h2>
    <?php endif; ?>

    <div class='push-container'>

        <?php
            // Cf. http://codex.wordpress.org/Class_Reference/WP_Query
            $query_args = array(
                // Type Parameters
                'post_type' => 'brand',                // (string / array) - use post types. Retrieves posts by Post Types, default value is 'post'; 'post' - a post. 'page' - a page. 'revision' - a revision. 'attachment' - an attachment. The default WP_Query sets 'post_status'=>'publish', but attachments default to 'post_status'=>'inherit' so you'll need to set the status to 'inherit' or 'any'. 'any' - retrieves any type except revisions and types with 'exclude_from_search' set to true. Custom Post Types (e.g. movies)
                // Status Parameters
                'post_status' => 'publish',              // (string / array) - use post status. Retrieves posts by Post Status. Default value is 'publish', but if the user is logged in, 'private' is added. And if the query is run in an admin context (administration area or AJAX call), protected statuses are added too. By default protected statuses are 'future', 'draft' and 'pending'. 'publish' - a published post or page. 'pending' - post is pending review. 'draft' - a post in draft status. 'auto-draft' - a newly created post, with no content. 'future' - a post to publish in the future. 'private' - not visible to users who are not logged in. 'inherit' - a revision. see get_children. 'trash' - post is in trashbin (available with Version 2.9). 'any' - retrieves any status except those from post types with 'exclude_from_search' set to true.
                // Pagination Parameters
                'posts_per_page' => -1,           // (int) - number of post to show per page (available with Version 2.1, replaced showposts parameter). Use 'posts_per_page'=>-1 to show all posts. Set the 'paged' parameter if pagination is off after using this parameter. Note: if the query is in a feed, wordpress overwrites this parameter with the stored 'posts_per_rss' option. To reimpose the limit, try using the 'post_limits' filter, or filter 'pre_option_posts_per_rss' and return -1
            );

            $brand_query = new WP_Query($query_args);
            if ( $brand_query->have_posts() ) :
                while ( $brand_query->have_posts() ) :
                    $brand_query->the_post();

                    $logo = super_get_field('logo');
                    $website = super_get_field('website');
                    $contact_type = super_get_field('contact_type');
                    if ($contact_type=='url') {
                        $link_url = super_get_field('contact_url');
                    } else {
                        $link_url = 'mailto:'.super_get_field('contact_email');
                    }
                    $excerpt = super_get_field('contact_excerpt');
            ?>
                    <a href='<?php echo $link_url; ?>'>
                        <div class="logo">
                            <?php if (!empty($logo)) : ?><img src="<?php echo $logo; ?>" alt="<?php echo esc_attr(get_the_title()); ?>" /><?php endif; ?>
                            <?php if (empty($logo)) : ?><?php the_title(); ?><?php endif; ?>
                        </div>
                        <?php if (!empty($excerpt)) : ?><p><?php echo $excerpt; ?></p><?php endif; ?>
                        <span class='link'><?php _e('Contacter', 'beneteau'); ?></span>
                    </a>
            <?php
                endwhile; // while ( $brand_query->have_posts() ) :
            endif; // if ( $brand_query->have_posts() ) :
        ?>

    </div>
</div>
