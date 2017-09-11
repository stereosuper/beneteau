<?php

define( 'BENETEAU_VERSION', 1.0 );


/*-----------------------------------------------------------------------------------*/
/* General
/*-----------------------------------------------------------------------------------*/
// Plugins updates
add_filter( 'auto_update_plugin', '__return_true' );

// Theme support
add_theme_support( 'html5', array(
    'comment-list',
    'comment-form',
    'search-form',
    'gallery',
    'caption',
    'widgets'
) );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );

// Admin bar
show_admin_bar(false);

// Disable Tags
function beneteau_unregister_tags(){
    unregister_taxonomy_for_object_type('post_tag', 'post');
}
add_action( 'init', 'beneteau_unregister_tags' );


/*-----------------------------------------------------------------------------------*/
/* Clean WordPress head and remove some stuff for security
/*-----------------------------------------------------------------------------------*/
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
add_filter( 'emoji_svg_url', '__return_false' );

// remove api rest links
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

// remove comment author class
function beneteau_remove_comment_author_class( $classes ){
	foreach( $classes as $key => $class ){
		if(strstr($class, 'comment-author-')) unset( $classes[$key] );
	}
	return $classes;
}
add_filter( 'comment_class' , 'beneteau_remove_comment_author_class' );

// remove login errors
add_filter( 'login_errors', create_function('$a', "return null;") );


/*-----------------------------------------------------------------------------------*/
/* Admin
/*-----------------------------------------------------------------------------------*/
// Remove some useless admin stuff
function beneteau_remove_submenus() {
  $page = remove_submenu_page( 'themes.php', 'themes.php' );
}
add_action( 'admin_menu', 'beneteau_remove_submenus', 999 );
function beneteau_remove_top_menus( $wp_admin_bar ){
    $wp_admin_bar->remove_node( 'wp-logo' );
}
add_action( 'admin_bar_menu', 'beneteau_remove_top_menus', 999 );

// Enlever le lien par défaut autour des images
function beneteau_imagelink_setup(){
	if(get_option( 'image_default_link_type' ) !== 'none') update_option('image_default_link_type', 'none');
}
add_action( 'admin_init', 'beneteau_imagelink_setup' );

// Enlever les <p> autour des images
function beneteau_remove_p_around_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter( 'the_content', 'beneteau_remove_p_around_images' );

// Allow svg in media library
function beneteau_mime_types($mimes){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'beneteau_mime_types' );

// Custom posts in the dashboard
function beneteau_right_now_custom_post() {
    $post_types = get_post_types(array( '_builtin' => false ) , 'objects' , 'and');
    foreach($post_types as $post_type){
        $cpt_name = $post_type->name;
        if($cpt_name !== 'acf-field-group' && $cpt_name !== 'acf-field'){
            $num_posts = wp_count_posts($post_type->name);
            $num = number_format_i18n($num_posts->publish);
            $text = _n($post_type->labels->name, $post_type->labels->name , intval($num_posts->publish));
            echo '<li class="'. $cpt_name .'-count"><tr><a class="'.$cpt_name.'" href="edit.php?post_type='.$cpt_name.'"><td></td>' . $num . ' <td>' . $text . '</td></a></tr></li>';
        }
    }
}
add_action( 'dashboard_glance_items', 'beneteau_right_now_custom_post' );

// Customize a bit the wysiwyg editor
function beneteau_mce_before_init( $styles ){
    // Remove h1 and code
    $styles['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6';
    // Let only the colors you want
    $styles['textcolor_map'] = '[' . "'000000', 'Noir', '565656', 'Texte'" . ']';
    return $styles;
}
add_filter( 'tiny_mce_before_init', 'beneteau_mce_before_init' );


/*-----------------------------------------------------------------------------------*/
/* Markup gallery
/*-----------------------------------------------------------------------------------*/
function beneteau_gallery( $output, $attr){
    global $post, $wp_locale;
    static $instance = 0;
    $instance++;

    if( isset($attr['orderby']) ){
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if( !$attr['orderby'] ) unset( $attr['orderby'] );
    }

    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post->ID,
        'itemtag'    => '',
        'icontag'    => '',
        'captiontag' => '',
        'columns'    => 3,
        'size'       => 'medium',
        'include'    => '',
        'exclude'    => ''
    ), $attr));

    $id = intval($id);
    if( 'RAND' == $order ) $orderby = 'none';

    if( !empty($include) ){
        $include = preg_replace( '/[^0-9,]+/', '', $include );
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

        $attachments = array();
        foreach( $_attachments as $key => $val ){
            $attachments[$val->ID] = $_attachments[$key];
        }
    }elseif ( !empty($exclude) ){
        $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }else{
        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }

    if( empty($attachments) ) return '';

    $selector = "gallery-{$instance}";
    $output = "<ul class='gallery' id='$selector'>";

    foreach( $attachments as $id => $attachment ){
        $output .= '<li><a href="' . wp_get_attachment_image_url($id, 'full') . '">' . wp_get_attachment_image($id, $size) . '</a></li>';
    }

    $output .= "</ul>";
    return $output;
}
add_filter( 'post_gallery', 'beneteau_gallery' );


/*-----------------------------------------------------------------------------------*/
/* Menus
/*-----------------------------------------------------------------------------------*/
register_nav_menus( array(
		'primary' => 'Primary Menu',
) );

// Cleanup WP Menu html
function beneteau_css_attributes_filter($var){
    return is_array($var) ? array_intersect($var, array('current-menu-item', 'current_page_parent')) : '';
}
add_filter( 'nav_menu_css_class', 'beneteau_css_attributes_filter' );


// /*-----------------------------------------------------------------------------------*/
// /* Sidebar & Widgets
// /*-----------------------------------------------------------------------------------*/
// function super_register_sidebars(){
// 	register_sidebar(array(
// 		'id' => 'sidebar',
// 		'name' => 'Sidebar',
// 		'description' => 'Take it on the side...',
// 		'before_widget' => '',
// 		'after_widget' => '',
// 		'before_title' => '',
// 		'after_title' => '',
// 		'empty_title'=> ''
// 	));
// }
// add_action( 'widgets_init', 'super_register_sidebars' );

// // Deregister default widgets
// function super_unregister_default_widgets(){
//     unregister_widget('WP_Widget_Pages');
//     unregister_widget('WP_Widget_Calendar');
//     unregister_widget('WP_Widget_Archives');
//     unregister_widget('WP_Widget_Links');
//     unregister_widget('WP_Widget_Meta');
//     unregister_widget('WP_Widget_Search');
//     unregister_widget('WP_Widget_Text');
//     unregister_widget('WP_Widget_Categories');
//     unregister_widget('WP_Widget_Recent_Posts');
//     unregister_widget('WP_Widget_Recent_Comments');
//     unregister_widget('WP_Widget_RSS');
//     unregister_widget('WP_Widget_Tag_Cloud');
//     unregister_widget('WP_Nav_Menu_Widget');
// }
// add_action( 'widgets_init', 'super_unregister_default_widgets' );


/*-----------------------------------------------------------------------------------*/
/* Enqueue Styles and Scripts
/*-----------------------------------------------------------------------------------*/
function beneteau_scripts(){
    // header
	wp_enqueue_style( 'beneteau-style', get_template_directory_uri() . '/css/main.css', array(), BENETEAU_VERSION );

	// footer
	wp_deregister_script('jquery');
	wp_enqueue_script( 'beneteau-scripts', get_template_directory_uri() . '/js/main.js', array(), BENETEAU_VERSION, true );

    wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_enqueue_scripts', 'beneteau_scripts' );

/*-----------------------------------------------------------------------------------*/
/* Post types
/*-----------------------------------------------------------------------------------*/
function beneteau_post_type(){
    register_post_type( 'brand', array(
        'label' => 'Marques',
        'singular_label' => 'Marque',
        'public' => true,
        'menu_icon' => 'dashicons-store',
        'supports' => array('title', 'editor', 'revisions'),
    ) );
}
add_action( 'init', 'beneteau_post_type' );

function beneteau_taxonomies(){
    register_taxonomy( 'branch', array('brand'), array(
        'label' => 'Secteur',
        'singular_label' => 'Secteur',
        'hierarchical' => true,
        'show_admin_column' => true
    ) );
}
add_action( 'init', 'beneteau_taxonomies' );

/*-----------------------------------------------------------------------------------*/
/* Helpers
/*-----------------------------------------------------------------------------------*/

function super_get_field($selector, $post_id=false, $format_value=true, $default='')
{
    if (function_exists('the_field')) {
        $value = get_field($selector, $post_id, $format_value);
        if ($value) {
            return $value;
        }
    }
    return $default;
}


function super_the_field($selector, $post_id=false, $format_value=true)
{
    $field_value = super_get_field($selector, $post_id, $format_value);
    if ($field_value) {
        echo $field_value;
    }
}
