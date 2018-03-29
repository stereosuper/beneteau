<?php
/*
 * Script de synchro des articles Wiztopic
 *
 * @package    leongrosse.fr
 * @subpackage wordpress theme
 * @author     Alain Diart pour Studio-Goliath
 *
 */

class WiztopicSync extends SGBaseImporter
{
    private $token = false;

    /**
     * Lance l'import
     */
    public function start()
    {
        $this->logImporterStarted();

        // Récupère un token oauth
        $this->token = false;
        $result = $this->getOauthToken();
        if ($result['result'] == 'OK') {
            $this->token = $result['token'];
        }

        // Synchronise les news et les cours de bourse
        $this->treatNews();
        $this->treatStockQuote();

        $this->logImporterEnded();
    }

    public function getLastNews($oauth_token)
    {
        if ($oauth_token != false) {

            $url = GWZTP_BASE_API_URI . '/api/2.0/web-publications?sort=-published&distributed=true&per_page=2&fields=title,cover_image,highlights,uri&embed=cover_image,uri';
            $args = array(
                'headers' => array(
                    'Authorization' => 'Bearer '.$oauth_token->getToken(),
                )
            );

            $request = wp_remote_get($url, $args);

            if(is_wp_error($request)) {
                $this->logWpError('Erreur API', $request, SG_LOG_INFO);
                return false;
            }

            $body = wp_remote_retrieve_body($request);
            $data = json_decode( $body );

            return $data;
        }
    }

    public static function getStockQuote($oauth_token)
    {
        $url = 'https://www.wiztopic.com/get-investor-data?key=59e10cfa2a5d6';
        $args = array();
        $request = wp_remote_get($url, $args);

        if(is_wp_error($request)) {
            return false;
        }

        $body = wp_remote_retrieve_body($request);
        $data = json_decode( $body );

        return $data;
    }

    public static function getOauthToken()
    {
        $client_id      = get_option('gwztp_oauth_client_id');
        $client_secret  = get_option('gwztp_oauth_client_secret');
        $username       = get_option('gwztp_oauth_username');
        $password       = get_option('gwztp_oauth_password');

        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                  => $client_id,
            'clientSecret'              => $client_secret,
            //'redirectUri'               => admin_url('options-general.php?page=gwztp'), // Inutilisé car on est en grant_type password
            'urlAuthorize'              => GWZTP_AUTHORIZE_URI,
            'urlAccessToken'            => GWZTP_ACCESSTOKEN_URI,
            'urlResourceOwnerDetails'   => GWZTP_OWNERDETAILS_URI,
        ]);

        try {

            // Try to get an access token using the resource owner password credentials grant.
            $accessToken = $provider->getAccessToken('password', [
                'username' => $username,
                'password' => $password
            ]);

            update_option('gwztp_token', $accessToken, TRUE);

        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

            // Failed to get the access token
            return array(
                'result' => 'ERROR',
                'message' => $e->getMessage(),
            );
        }

        return array(
            'result' => 'OK',
            'token' => $accessToken,
        );
    }

    public static function getPermalink($post_id)
    {
        return get_option('gwztp_base_url').'/'.get_post_meta($post_id, 'wztp_uri_slug', true).'.html';
    }

    public static function getMediaPermalink($post_id, $size='large')
    {
        $cover_media_id = get_post_meta($post_id, 'wztp_cover_media_id', true);
        if (!empty($cover_media_id)) {

            switch ($size) {
                case 'medium':
                    $size_path = '/media/cache/resolve/api_medium_grid_fs/';
                    break;
                case 'tiny':
                    $size_path = '/media/cache/resolve/api_tiny_grid_fs/';
                    break;
                default:
                    $size_path = '/media/cache/resolve/api_large_grid_fs/';
                    break;
            }

            return get_option('gwztp_base_media_url').$size_path.get_post_meta($post_id, 'wztp_cover_media_id', true);
        }
        return false;
    }

    protected function treatNews()
    {
        $news_list = $this->getLastNews($this->token);
        if (is_array($news_list)) {
            foreach($news_list as $news) {
                $importId = $news->id;
                $postType = 'post';
                $title = $news->title;
                $status = 'publish';
                $args = array(
                    'post_content' => $news->highlights,
                );
                $slug = $news->uri->slug;
                $cover_image_id = false;
                if (isset($news->cover_image) && isset($news->cover_image->id)) {
                    $cover_image_id = $news->cover_image->id;
                }

                $post_id = $this->wpSavePost($importId, $postType, $title, $status, $args);

                $this->wpAddPostMeta('wztp_uri_slug', $slug, $post_id, 'post');
                $this->wpAddPostMeta('wztp_cover_media_id', $cover_image_id, $post_id, 'post');
            }
        }
    }

    protected function treatStockQuote()
    {
        $option_name = 'wiztopic_stockquote' ;
        $stock_quote = $this->getStockQuote($this->token);
        $new_value = json_encode($stock_quote);

        if (get_option($option_name) !== false) {
            update_option($option_name, $new_value, 'yes');
        } else {
            $deprecated = null;
            $autoload = 'yes';
            add_option($option_name, $new_value, $deprecated, $autoload);
        }
    }
}
