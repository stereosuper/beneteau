<?php
/**
 * Plugin Name: Goliath RGPD pour Beneteau
 * Description: RGPD is so much fun <3
 * Version: 1.0
 * Author: Studio Goliath
 * Author URI: http://www.studio-goliath.com/
 */

if (!class_exists('GoliathRGPD')) {

    define('GOLIATH_RGPD_ACTIVE', true);

    class GoliathRGPD {

        public static function load_plugin_textdomain() {
            load_plugin_textdomain('goliath-rgpd', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
        }

        public static function apply_rgpd_preferences()
        {
            if(isset($_COOKIE['goliath-rgpd-prefs'])) {
                $cookie = json_decode(stripslashes($_COOKIE['goliath-rgpd-prefs']));
                if (isset($cookie->ga) && $cookie->ga == 'refuse') {
                    $unwanted_cookies = ['_ga', '_gat', '_gid'];

                    // Saque tous les cookies non voulus
                    foreach ($_COOKIE as $key => $maybe_unwanted_cookie) {
                        if (in_array($key, $unwanted_cookies)) {
                            $domain = $_SERVER['HTTP_HOST'];

                            unset($_COOKIE[$key]);
                            setcookie($key, '', time() - 1, '/', $domain);
                        }
                    }
                }
            }
        }

        public static function set_rgpd_preferences()
        {
            $nonce = $_POST['rgpd_user_prefs_nonce'];
            if (!empty($nonce) && wp_verify_nonce($nonce, 'rgpd_user_prefs_nonce')) {

                $accept_global_cookies = false;
                if (isset($_COOKIE['beneteau-cookies'])) {
                    $accept_global_cookies = $_COOKIE['beneteau-cookies'];
                }

                $domain = $_SERVER['HTTP_HOST'];

                $value = array();
                $value['ga'] = ($accept_global_cookies=='true')?'accept':'refuse';
                if (isset($_POST['ga-rgpd'])) {
                    $value['ga'] = $_POST['ga-rgpd'];
                }

                // Dans ce cas, on passe beneteau-cookies à true (accepte les cookies requis par le système)
                setcookie('beneteau-cookies', 'true', time() + 360 * 24 * 60 * 60, '/', $domain);

                setcookie('goliath-rgpd-prefs', json_encode($value), time() + 360 * 24 * 60 * 60, '/', $domain);
            }

            wp_redirect($_SERVER['HTTP_REFERER']);
        }

        public static function do_rgpd_form_shortcode ($atts, $content=null) {
            $nonce = wp_create_nonce('rgpd_user_prefs_nonce');

            $accept_global_cookies = false;
            if (isset($_COOKIE['beneteau-cookies'])) {
                $accept_global_cookies = $_COOKIE['beneteau-cookies'];
            }

            $ga = ($accept_global_cookies=='true')?'accept':'refuse';

            if(isset($_COOKIE['goliath-rgpd-prefs'])) {
                $cookie = json_decode(stripslashes($_COOKIE['goliath-rgpd-prefs']));
                if (isset($cookie->ga)) {
                    $ga = $cookie->ga;
                }
            }

            $output = '
<form action="'.esc_url(admin_url('admin-post.php')).'" method="POST">

<input type="hidden" name="action" value="rgpd_user_prefs">
<input type="hidden" name="rgpd_user_prefs_nonce" value="'.$nonce.'" />

<p><strong>'.__('Cookies requis pour le fonctionnement du site.', 'goliath-rgpd').'</strong></p>
<div class="inline-form-group">
    <div>
        <input type="radio" id="required-valid" name="required-rgpd" value="accept" checked="checked" disabled="disabled"><label for="required-valid">'.__('J’accepte', 'goliath-rgpd').'</label>
    </div>
</div>

<p><strong>'.__('Google Analytics : Analyse et mesure d’audience.', 'goliath-rgpd').'</strong></p>
<div class="inline-form-group">
    <div>
        <input type="radio" id="ga-valid" name="ga-rgpd" value="accept"'.(($ga == 'accept')?' checked="checked"':'').'><label for="ga-valid">'.__('J’accepte', 'goliath-rgpd').'</label>
    </div>
    <div>
        <input type="radio" id="ga-invalid" name="ga-rgpd" value="refuse"'.(($ga == 'refuse')?' checked="checked"':'').'><label for="ga-invalid">'.__('Je refuse', 'goliath-rgpd').'</label>
    </div>
</div>

<p><button type="submit" class="btn-invert">'.__('Valider mon choix', 'goliath-rgpd').'</button><br></p>

</form>';

            return $output;
        }
    }

    add_action( 'admin_post_nopriv_rgpd_user_prefs', array('GoliathRGPD', 'set_rgpd_preferences'));
    add_action( 'admin_post_rgpd_user_prefs', array('GoliathRGPD', 'set_rgpd_preferences'));

    add_action('init', array('GoliathRGPD', 'apply_rgpd_preferences'));

    add_shortcode('rgpd-form', array('GoliathRGPD', 'do_rgpd_form_shortcode'));

    add_action('plugins_loaded', array('GoliathRGPD', 'load_plugin_textdomain'));
}
