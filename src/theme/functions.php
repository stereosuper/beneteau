<?php

define( 'BENETEAU_VERSION', '1.50' );

/*-----------------------------------------------------------------------------------*/
/* General
/*-----------------------------------------------------------------------------------*/

// Fix SQL request always called ?
if( isset($_GET['doing_wp_cron']) ){
	remove_action('do_pings', 'do_all_pings');
	wp_clear_scheduled_hook('do_pings');
}

add_action( 'after_setup_theme', 'beneteau_theme_setup' );
function beneteau_theme_setup() {
    load_theme_textdomain('beneteau', get_template_directory() .'/languages');
    load_theme_textdomain('beneteau', get_stylesheet_directory() .'/languages');
}

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
function mpe_rmove_login_errors( $a ){
    return null;
}
add_filter( 'login_errors', 'mpe_rmove_login_errors' );


/*-----------------------------------------------------------------------------------*/
/* Admin
/*-----------------------------------------------------------------------------------*/
// Remove some useless admin stuff
function beneteau_remove_submenus() {
    if (strpos($_SERVER['SERVER_NAME'], '.dev')<0) {
        remove_menu_page( 'edit.php' );
    }
    remove_submenu_page( 'themes.php', 'themes.php' );
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
            if( isset($num_posts->publish) ){
                $num = number_format_i18n($num_posts->publish);
                $text = _n($post_type->labels->name, $post_type->labels->name , intval($num_posts->publish));
                echo '<li class="'. $cpt_name .'-count"><tr><a class="'.$cpt_name.'" href="edit.php?post_type='.$cpt_name.'"><td></td>' . $num . ' <td>' . $text . '</td></a></tr></li>';
            }
        }
    }
}
add_action( 'dashboard_glance_items', 'beneteau_right_now_custom_post' );

// News styles in wysiwyg
function beneteau_wysiwyg_styleselect( $buttons ){
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
add_filter( 'mce_buttons_2', 'beneteau_wysiwyg_styleselect' );

// Customize a bit the wysiwyg editor
function beneteau_mce_before_init( $styles ){
    $style_formats = array(
        array(
            'title' => 'Lien',
            'selector' => 'a',
            'classes' => 'link'
        ),
        array(
            'title' => 'Lien doc',
            'selector' => 'a',
            'classes' => 'link-doc'
        ),
        array(
            'title' => 'Bouton clair',
            'selector' => 'a',
            'classes' => 'btn-invert'
        ),
        array(
            'title' => 'Bouton foncé',
            'selector' => 'a',
            'classes' => 'btn'
        ),
        array(
            'title' => 'Introduction',
            'block' => 'p',
            'classes' => 'intro'
        ),
        array(
            'title' => 'Catégorie histoire',
            'block' => 'p',
            'classes' => 'cat'
        ),
    );
    $styles['style_formats'] = json_encode( $style_formats );
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
function beneteau_gallery($output, $attr=array()){
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
        'icontag'    => 'figure',
        'captiontag' => 'figcaption',
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
    $output = "<ul class='gallery' id='$selector' data-featherlight-gallery
    data-featherlight-filter='a'>";
    $count = 0;

    foreach( $attachments as $id => $attachment ){
        $count ++;
        $output .= '<li>';
        $output .= '<a href="#image' . $count . '" data-url="' . wp_get_attachment_image_url($id, 'full') . '">' . wp_get_attachment_image($id, $size) . '</a>';
        $output .= '<div id="image' . $count . '">' . wp_get_attachment_image($id, 'full');
        $output .= trim($attachment->post_excerpt) ? '<p>' . wptexturize($attachment->post_excerpt) . '</p>' : '';
        $output .= '</div>';
        $output .= '</li>';
    }

    $output .= "</ul>";
    return $output;
}
add_filter( 'post_gallery', 'beneteau_gallery' );


/*-----------------------------------------------------------------------------------*/
/* Menus
/*-----------------------------------------------------------------------------------*/
register_nav_menus( array(
		'primary' => 'Menu Principal',
		'legals' => 'Menu des mentions légales',
        'footer' => 'Menu de pied de page',
        'job' => 'Menu emploi',
) );

// Cleanup WP Menu html
function beneteau_css_attributes_filter($var){
    return is_array($var) ? array_intersect($var, array('current-menu-item', 'current_page_parent', 'hidden', 'menu-item-has-children')) : '';
}
add_filter( 'nav_menu_css_class', 'beneteau_css_attributes_filter' );

require_once('custom-walkers/custom-walker-nav-only-a.php');
require_once('custom-walkers/custom-walker-nav-sub-menu.php');
require_once('custom-walkers/custom-walker-nav-wrap-submenu.php');


/*-----------------------------------------------------------------------------------*/
/* Nav links
/*-----------------------------------------------------------------------------------*/
function beneteau_next_class( $output ){
    $output = str_replace('</a>', '<svg class="icon"><use xlink:href="#icon-right"></use></svg></a>', $output);
    return str_replace('<a href=', '<a class="link-next" href=', $output);
}
function beneteau_prev_class( $output ){
    $output = str_replace('</a>', '<svg class="icon"><use xlink:href="#icon-left"></use></svg></a>', $output);
    return str_replace('<a href=', '<a class="link-prev" href=', $output);
}
add_filter('next_post_link', 'beneteau_next_class');
add_filter('previous_post_link', 'beneteau_prev_class');


/*-----------------------------------------------------------------------------------*/
/* Sidebar & Widgets
/*-----------------------------------------------------------------------------------*/
function super_register_sidebars(){
	register_sidebar(array(
		'id' => 'job',
		'name' => 'Emploi',
		'description' => "Apparait sur la page d'accueil Emploi",
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
		'empty_title'=> ''
    ));
    // register_sidebar(array(
    //     'id' => 'job-menu',
    //     'name' => 'Menu Emploi',
    //     'description' => "Apparait sur les pages Emploi",
    //     'before_widget' => '',
    //     'after_widget' => '',
    //     'before_title' => '',
    //     'after_title' => '',
    //     'empty_title'=> ''
    // ));
}
add_action( 'widgets_init', 'super_register_sidebars' );

// Deregister default widgets
function super_unregister_default_widgets(){
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
}
add_action( 'widgets_init', 'super_unregister_default_widgets' );


/*-----------------------------------------------------------------------------------*/
/* Option page
/*-----------------------------------------------------------------------------------*/
function beneteau_add_options_page() {
    if( function_exists('acf_add_options_page') ){
        acf_add_options_page( array(
            'position'   => 2,
            'page_title' => 'Paramètres du thème',
            'menu_title' => 'Paramètres',
            'redirect'   => false
        ) );
    }
}
add_action('acf/init', 'beneteau_add_options_page');


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
        'supports' => array('title', 'editor', 'excerpt', 'revisions'),
        'rewrite' => array(
            'slug' => __('brand', 'beneteau'), // string Customize the permastruct slug. Defaults to the $post_type value. Should be translatable.
            //'with_front' => false, // bool Should the permastruct be prepended with the front base. (example: if your permalink structure is /blog/, then your links will be: false->/news/, true->/blog/news/). Defaults to true
            'pages' => true, // bool Should the permastruct provide for pagination. Defaults to true
            'feeds' => true, // bool Should a feed permastruct be built for this post type. Defaults to has_archive value.
        ),
    ) );
    register_post_type( 'career', array(
        'label' => 'Métiers',
        'singular_label' => 'Métier',
        'public' => true,
        'menu_icon' => 'dashicons-store',
        'supports' => array('title', 'editor', 'revisions'),
        // 'rewrite' => array(
        //     'slug' => __('job', 'beneteau'), // string Customize the permastruct slug. Defaults to the $post_type value. Should be translatable.
        //     //'with_front' => false, // bool Should the permastruct be prepended with the front base. (example: if your permalink structure is /blog/, then your links will be: false->/news/, true->/blog/news/). Defaults to true
        //     'pages' => true, // bool Should the permastruct provide for pagination. Defaults to true
        //     'feeds' => true, // bool Should a feed permastruct be built for this post type. Defaults to has_archive value.
        // ),
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

function super_have_rows( $selector, $post_id = false )
{
    if (function_exists('have_rows')) {
        return have_rows( $selector, $post_id );
    }
    return false;
}


/**
 * Retourne la première page utilisant le template donné
 *
 * @param type $template_name
 * @return type
 */
function goliath_get_page_by_template($template_name) {
  $posts_args = array(
    'numberposts'     => 1,
    'meta_key'        => '_wp_page_template',
    'meta_value'      => $template_name,
    'post_type'       => 'page',
  );
  $posts = get_posts($posts_args);
  if (is_array($posts) && isset($posts[0])) {
    return $posts[0];
  }
}


/**
 * Retourne le permalien de la première page utilisant le template donné
 *
 * @param type $template_name
 * @return type
 */
function goliath_get_page_url_by_template($template_name) {
  $page = goliath_get_page_by_template($template_name);
  return get_permalink($page->ID);
}

function beneteau_mlp_navigation()
{
    $api = apply_filters( 'mlp_language_api', NULL );
    if( ! is_a( $api, 'Mlp_Language_Api_Interface' ) ){
        return '';
    }

    $translations_args = array(
        'strict'       => FALSE,
        'include_base' => TRUE,
    );

    $translations = $api->get_translations( $translations_args );
    if( empty( $translations ) ){
        return '';
    }

    $items = array();

    foreach( $translations as $site_id => $translation ){
        $url = $translation->get_remote_url();
        if( empty( $url ) ){
            continue;
        }

        $language = $translation->get_language();

        $items[ $site_id ] = array(
            'url'      => $url,
            'http'     => $language->get_name( 'http' ),
            'name'     => $language->get_name( 'text' ),
            'priority' => $language->get_priority(),
            'icon'     => (string) $translation->get_icon_url(),
        );
    }

    ksort( $items );

    $before = '<p class="lang" id="lang">';
    $after = '</p>';

    $langItems = array();

    foreach( $items as $site_id => $item ){
        $text = $item[ 'name' ];

        $img = '';

        if( get_current_blog_id() === $site_id ){
            $langItems[] = sprintf(
                '<a hreflang="%1$s" title="%1$s" lang="%3$s%4$s" href="%2$s" class="current" aria-current="true">%3$s%4$s</a>',
                esc_attr( $item['http'] ),
                esc_url( $item[ 'url' ] ),
                $img,
                esc_html( $text )
            );
        }else{
            $langItems[] = sprintf(
                '<a rel="alternate" hreflang="%1$s" title="%1$s" lang="%3$s%4$s" href="%2$s">%3$s%4$s</a>',
                esc_attr( $item['http'] ),
                esc_url( $item[ 'url' ] ),
                $img,
                esc_html( $text )
            );
        }
    }

    return $before . join( '', $langItems ) . $after;
}


/*-----------------------------------------------------------------------------------*/
/* WP Rocket
/*-----------------------------------------------------------------------------------*/

function beneteau_cookies($cookies){
    $cookies[] = 'beneteau-cookies';
    return $cookies;
}
add_filter( 'rocket_cache_dynamic_cookies', 'beneteau_cookies' );


/*-----------------------------------------------------------------------------------*/
/* Eolia
/*-----------------------------------------------------------------------------------*/
add_filter( 'eolia_filter_mail_to',
function ( $mail, $job ) {
    /** @var \Eolia\Controllers\JobController $job */
    if( $job ){
        $override = filter_var( $job->get_additionnal_field( 'saisie3' ), FILTER_SANITIZE_EMAIL );
        if ( $override && $override !== '' ) {
            $mail = 'fr-beneteau2@redirection-eolia.com';
        }
    }

    return $mail;
}, 10, 2 );

add_action( 'eolia_action_mail',
function ( $form_fields, $job, $content, $attachments ) {
    /** @var \Eolia\Controllers\JobController $job */
    if( $job ){
        if($mailTo = filter_var($job->get_additionnal_field('saisie3'), FILTER_SANITIZE_EMAIL)){
            $mailContent = $content;
            $mailHeaders = 'Content-Type: text/html; charset=UTF-8';
            $mailAttachments = $attachments;
            if( ! wp_mail( $mailTo, 'Beneteau - Offre '.$job->get_ref(), $mailContent, $mailHeaders, $mailAttachments ) ) {
                wp_die( __('Une erreur s\'est produite lors de l\'envoi de votre candidature...') );
            }
        }
    }
}, 10, 4 );


/*-----------------------------------------------------------------------------------*/
/* TGMPA
/*-----------------------------------------------------------------------------------*/

function beneteau_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
        array(
            'name'        => 'Advanced Custom Fields PRO',
            'slug'        => 'advanced-custom-fields-pro',
            'source'     => get_template_directory_uri() . '/plugins/advanced-custom-fields-pro.zip',
            'required'    => true,
            'force_activation' => false
        ),
        array(
            'name'        => 'WP Rocket',
            'slug'        => 'wp-rocket',
            'source'     => get_template_directory_uri() . '/plugins/wp-rocket_2.10.9.zip',
            'required'    => false,
            'force_activation' => false
        ),
		array(
			'name'        => 'WordPress SEO by Yoast',
			'slug'        => 'wordpress-seo',
            'required'    => false,
            'force_activation' => false
		),
        array(
            'name'        => 'MultilingualPress',
            'slug'        => 'multilingual-press',
            'required'    => false,
            'force_activation' => false
        ),
        array(
            'name'        => 'WP REST API Menus',
            'slug'        => 'wp-api-menus',
            'required'    => true,
            'force_activation' => false
        ),
        array(
            'name'        => 'Loco Translate',
            'slug'        => 'loco-translate',
            'required'    => false,
            'force_activation' => false
        ),
        array(
            'name'        => 'SecuPress Free — Sécurité WordPress 1.3.3',
            'slug'        => 'secupress',
            'required'    => false,
            'force_activation' => false
        ),
        array(
            'name'        => 'Espace Candidats Wordpress par Eolia Software',
            'slug'        => 'eolia-app',
            'source'     => get_template_directory_uri() . '/plugins/eolia-app.zip',
            'required'    => true,
            'force_activation' => false
        ),
	);

	$config = array(
		'id'           => 'beneteau',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'beneteau_register_required_plugins' );
