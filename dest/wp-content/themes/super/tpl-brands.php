<?php
/*
Template Name: Marques
*/

get_header(); ?>

    <div class='container'>

    <?php if ( have_posts() ) : the_post(); ?>

        <?php
            // Ici on passe sur la deuxième colonne
            if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb('<div class="breadcrumbs">','</div>');
            }
        ?>
        <h1><?php the_title(); ?></h1>

        <?php
            // Récupère les branches
            // http://codex.wordpress.org/Function_Reference/get_terms
            $taxonomies = array('branch');

            $terms_args = array(
                'hide_empty' => true, // (boolean) Whether to return empty $terms : 1 (true) - Default (i.e. Do not show empty terms), 0 (false)
                'fields' => 'all', // (string) : all - returns an array of term objects - Default, ids - returns an array of integers, names - returns an array of strings, count - (3.2+) returns the number of terms found, id=>parent - returns an associative array where the key is the term id and the value is the parent term id if present or 0
            );

            $terms = get_terms($taxonomies, $terms_args);

            if ($terms) :
        ?>

            <ul class="branch-illustrations">
            <?php
                // Fait une première boucle sur les secteurs pour afficher les images dans le diaporama
                $is_active = true;
                foreach ($terms as $term) :
                    $term_image = super_get_field('term_image', $term);
                    if (is_array($term_image) && isset($term_image['ID'])) :
                        list($image_url, $w, $h) = wp_get_attachment_image_src($term_image['ID'], 'large');
            ?>
                <li class="<?php echo $term->slug, (($is_active)?' active':''); ?>">
                    <img src="<?php echo $image_url; ?>" alt="<?php echo $term->name ?>" />
                </li>
            <?php
                        $is_active = false;
                    endif; // if ($term_image) :
                endforeach; // foreach ($terms as $term) :
            ?>
            </ul>

            <?php
                // Fait une seconde boucle sur les secteurs pour afficher les marques qui en dépendent
                foreach ($terms as $term) :
            ?>

                <h2><?php echo $term->name; ?></h2>

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
                    );

                    $brand_per_branch_query = new WP_Query($query_args);
                    if ( $brand_per_branch_query->have_posts() ) :
                ?>

                    <?php
                        while ( $brand_per_branch_query->have_posts() ) :
                            $brand_per_branch_query->the_post();
                    ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php
                                $logo = super_get_field('logo');
                                if (!empty($logo)) :
                                    echo $logo;
                                else :
                                    // Sans doute besoin d'un placeholder ici ?...
                                endif;
                            ?>
                            <h3 class="on-hover"><?php the_title(); ?></h3>
                        </a>

                    <?php
                        endwhile; // while ( $brand_per_branch_query->have_posts() ) :
                    ?>

                <?php
                    endif; // if ( $brand_per_branch_query->have_posts() ) :
                ?>

            <?php
                endforeach; // foreach ($terms as $term) :
            ?>

        <?php
            endif; // if ($terms) :
        ?>

    <?php
        else : // if ( have_posts() ) :
    ?>

        <h1>404</h1>

    <?php
        endif; // if ( have_posts() ) :
    ?>

    </div>

<?php get_footer(); ?>
