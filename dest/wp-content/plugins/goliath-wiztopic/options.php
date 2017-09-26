<?php

function GWZTP_plugin_menu_func()
{
    add_submenu_page(
       'options-general.php',
       'Api Wiztopic',
       'Api Wiztopic',
       'manage_options',
       'gwztp',
       'gwztp_plugin_options'
    );
}
add_action( 'admin_menu', 'gwztp_plugin_menu_func' );

function gwztp_plugin_options()
{
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'Vous n\'avez pas les droits suffisants pour accéder à cette page.' ) );
    }
?>

<form method='post' action='<?php echo admin_url( 'admin-post.php'); ?>'>

   <input type='hidden' name='action' value='gwztp_oauth_submit' />

   <h3><?php _e('Apis Wiztopic Oauth 2.0', 'gwztp'); ?></h3>

    <?php
        if ( isset($_GET['status']) && $_GET['status']=='success') :
    ?>
        <div id="message" class="updated notice is-dismissible">
            <p><?php _e("Settings updated!", "gwztp"); ?></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php _e("Dismiss this notice.", "gwztp"); ?></span>
            </button>
        </div>
    <?php
        endif;
    ?>

    <?php
        if ( isset($_GET['status']) && $_GET['status']=='error') :
    ?>
        <div id="message" class="updated error is-dismissible">
            <p><?php _e("Settings updated (errors)!", "gwztp"); ?></p>
            <p><?php echo $_GET['message']; ?></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php _e("Dismiss this notice.", "gwztp"); ?></span>
            </button>
        </div>
    <?php
        endif;
    ?>

   <p>
      <label><?php _e('Client ID:', 'gwztp'); ?></label>
      <input class='' type='text' name='gwztp_oauth_client_id' value='<?php echo get_option('gwztp_oauth_client_id')?>' />
   </p>

   <p>
      <label><?php _e('Client Secret:', 'gwztp'); ?></label>
      <input class='' type='password' name='gwztp_oauth_client_secret' value='<?php echo get_option('gwztp_oauth_client_secret')?>' />
   </p>

   <p>
      <label><?php _e('User:', 'gwztp'); ?></label>
      <input class='' type='text' name='gwztp_oauth_username' value='<?php echo get_option('gwztp_oauth_username')?>' />
   </p>

   <p>
      <label><?php _e('Password:', 'gwztp'); ?></label>
      <input class='' type='password' name='gwztp_oauth_password' value='<?php echo get_option('gwztp_oauth_password')?>' />
   </p>

   <hr>

   <p>
      <label><?php _e('Base Url:', 'gwztp'); ?></label>
      <input class='' type='text' name='gwztp_base_url' value='<?php echo get_option('gwztp_base_url')?>' />
   </p>

   <p>
      <label><?php _e('Base Media Url:', 'gwztp'); ?></label>
      <input class='' type='text' name='gwztp_base_media_url' value='<?php echo get_option('gwztp_base_media_url')?>' />
   </p>

   <input class='button button-primary' type='submit' value='<?php _e('Update and Authorize', 'gwztp'); ?>' />

</form>

<?php
}

function gwztp_oauth_submit_handle()
{
    if (isset($_POST['action']) && $_POST['action']=='gwztp_oauth_submit') {
        update_option('gwztp_oauth_client_id',      $_POST['gwztp_oauth_client_id'], TRUE);
        update_option('gwztp_oauth_client_secret',  $_POST['gwztp_oauth_client_secret'], TRUE);
        update_option('gwztp_oauth_username',       $_POST['gwztp_oauth_username'], TRUE);
        update_option('gwztp_oauth_password',       $_POST['gwztp_oauth_password'], TRUE);
        update_option('gwztp_base_url',             untrailingslashit($_POST['gwztp_base_url']), TRUE);
        update_option('gwztp_base_media_url',       untrailingslashit($_POST['gwztp_base_media_url']), TRUE);
    }

    $result = WiztopicSync::getOauthToken();

    switch($result['result']) {
        case 'ERROR':
            $redirect_url = get_bloginfo("url") . "/wp-admin/options-general.php?page=gwztp&status=error&message=".urlencode($result['message']);
            break;
        case 'OK':
            $redirect_url = get_bloginfo("url") . "/wp-admin/options-general.php?page=gwztp&status=success";
            break;
    }

    header("Location: ".$redirect_url);

    exit;
}
add_action( "admin_post_gwztp_oauth_submit", "gwztp_oauth_submit_handle" );
