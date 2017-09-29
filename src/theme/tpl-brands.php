<?php
/*
Template Name: Marques
*/

// Récupère les branches
// http://codex.wordpress.org/Function_Reference/get_terms
$taxonomies = array('branch');

$terms_args = array(
    'hide_empty' => true, // (boolean) Whether to return empty $terms : 1 (true) - Default (i.e. Do not show empty terms), 0 (false)
    'fields' => 'all', // (string) : all - returns an array of term objects - Default, ids - returns an array of integers, names - returns an array of strings, count - (3.2+) returns the number of terms found, id=>parent - returns an associative array where the key is the term id and the value is the parent term id if present or 0
);

$terms = get_terms($taxonomies, $terms_args);

get_header(); ?>


    <?php if ( have_posts() ) : the_post(); ?>

        <aside class='sidebar-brands'>

            <ul class="sidebar-menu-brands">
                <?php if ($terms) : ?>

                    <ul class="brands-slider" id='submenu'>
                        <?php foreach ($terms as $term) : ?>
                            <li><a href="#section-<?php echo $term->slug; ?>"><?php echo $term->name; ?></a></li>
                        <?php endforeach; ?>
                    </ul>

                <?php endif; // if ($terms) : ?>
            </ul>

            <?php if ($terms) : $count = 0; ?>

                <ul class="brand-slider" id='brandsImg'>
                <?php
                    foreach ($terms as $term) :
                        $term_image = super_get_field('term_image', $term);
                        if (is_array($term_image) && isset($term_image['id'])) :
                ?>
                    <?php if($count === 0){
                        echo wp_get_attachment_image( $term_image['id'], 'full', '', array('class' => 'on') );
                    }else{
                        echo wp_get_attachment_image( $term_image['id'], 'full' );
                    } ?>
                <?php
                        endif; // if ($term_image) :
                    $count ++; endforeach; // foreach ($terms as $term) :
                ?>
                </ul>

            <?php
                endif; // if ($terms) :
            ?>
        </aside>

        <div class='container clearfix'>

            <div class='content-half-right content-brands'>

                <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div class="breadcrumbs">','</div>'); } ?>

                <h1 class='isAnimated'><?php the_title(); ?></h1>

                <?php
                    // Fait une seconde boucle sur les secteurs pour afficher les marques qui en dépendent
                    if ($terms) :
                        foreach ($terms as $term) :
                ?>

                    <h2 class='isAnimated' id="section-<?php echo $term->slug; ?>"><?php echo $term->name; ?></h2>

                    <?php
                        // Cf. http://codex.wordpress.org/Class_Reference/WP_Query
                        $query_args = array(
                            // Taxonomy Parameters
                            'tax_query' => array(               // (array) - use taxonomy parameters (available with Version 3.1).
                                'relation' => 'AND',            // Accepted arguments are 'AND', 'OR'.
                                array(
                                    'taxonomy' => 'branch',         // (string) - Taxonomy.
                                    'field' => 'id',            // (string) - Select taxonomy term by ('id' or 'slug')
                                    'terms' => $term,            // (int/string/array) - Taxonomy term(s).
                                ),
                            ),
                            // Type Parameters
                            'post_type' => 'brand',                // (string / array) - use post types. Retrieves posts by Post Types, default value is 'post'; 'post' - a post. 'page' - a page. 'revision' - a revision. 'attachment' - an attachment. The default WP_Query sets 'post_status'=>'publish', but attachments default to 'post_status'=>'inherit' so you'll need to set the status to 'inherit' or 'any'. 'any' - retrieves any type except revisions and types with 'exclude_from_search' set to true. Custom Post Types (e.g. movies)
                            // Status Parameters
                            'post_status' => 'publish',              // (string / array) - use post status. Retrieves posts by Post Status. Default value is 'publish', but if the user is logged in, 'private' is added. And if the query is run in an admin context (administration area or AJAX call), protected statuses are added too. By default protected statuses are 'future', 'draft' and 'pending'. 'publish' - a published post or page. 'pending' - post is pending review. 'draft' - a post in draft status. 'auto-draft' - a newly created post, with no content. 'future' - a post to publish in the future. 'private' - not visible to users who are not logged in. 'inherit' - a revision. see get_children. 'trash' - post is in trashbin (available with Version 2.9). 'any' - retrieves any status except those from post types with 'exclude_from_search' set to true.
                            // Pagination Parameters
                            'posts_per_page' => -1,           // (int) - number of post to show per page (available with Version 2.1, replaced showposts parameter). Use 'posts_per_page'=>-1 to show all posts. Set the 'paged' parameter if pagination is off after using this parameter. Note: if the query is in a feed, wordpress overwrites this parameter with the stored 'posts_per_rss' option. To reimpose the limit, try using the 'post_limits' filter, or filter 'pre_option_posts_per_rss' and return -1
                            'order' => 'ASC'
                        );

                        $brand_per_branch_query = new WP_Query($query_args);
                        if ( $brand_per_branch_query->have_posts() ) :
                    ?>

                        <ul class='list-brands'>
                        <?php
                            while ( $brand_per_branch_query->have_posts() ) :
                                $brand_per_branch_query->the_post();

                                $logo = super_get_field('logo');
                        ?>
                            <li>
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (!empty($logo)) : ?><img src="<?php echo $logo; ?>" alt="<?php echo esc_attr(get_the_title()); ?>" /><?php endif; ?>
                                    <h3><?php the_title(); ?></h3>
                                </a>
                            </li>

                        <?php
                            endwhile; // while ( $brand_per_branch_query->have_posts() ) :
                        ?>
                        </ul>

                    <?php
                        endif; // if ( $brand_per_branch_query->have_posts() ) :
                    ?>

                <?php
                    endforeach; // foreach ($terms as $term) :
                endif; // if ($terms) :
                ?>

            </div>

        </div>

    <?php
        else : // if ( have_posts() ) :
    ?>

        <div class='container'>
            <h1>404</h1>
        </div>

    <?php
        endif; // if ( have_posts() ) :
    ?>

<?php get_footer(); ?>
