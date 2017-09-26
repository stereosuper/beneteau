<?php
/*
 * Script d'import de base
 *
 * @package    studio-goliath.com
 * @subpackage wordpress theme
 * @author     Alain Diart pour Studio-Goliath
 *
 */

DEFINE('SG_LOG_DEFAULT',        0);
DEFINE('SG_LOG_LIGHT',          1);
DEFINE('SG_LOG_INFO',           2);
DEFINE('SG_LOG_SUCCESS',        3);
DEFINE('SG_LOG_NOTICE',         4);
DEFINE('SG_LOG_ERROR',          5);

class SGBaseImporter
{
    private $startTime, $endTime;
    protected $postsCreated, $postsUpdated;
    protected $logsDefaultCount, $logsLightCount, $logsInfoCount, $logsSuccessCount, $logsNoticeCount, $logsErrorCount;
    protected $notices, $errors;
    protected $options;

    public function __construct($options = null)
    {
        $defaultOptions = array(
            'colorful' => false,
        );

        $this->postsCreated = 0;
        $this->postsUpdated = 0;
        $this->postsDeleted = 0;
        $this->logsDefaultCount = 0;
        $this->logsLightCount = 0;
        $this->logsInfoCount = 0;
        $this->logsSuccessCount = 0;
        $this->logsNoticeCount = 0;
        $this->logsErrorCount = 0;

        $this->notices = [];
        $this->errors = [];

        $this->options = wp_parse_args($options, $defaultOptions);
    }

    /**
     * Ajoute dans les logs un marqueur temporel de démarrage du script
     */
    protected function logImporterStarted()
    {
        $this->startTime = microtime(true);
        $this->logLine('Traitement débuté', SG_LOG_INFO);
    }

    /**
     * Ajoute dans les logs un marqueur temporel de fin du script
     */
    protected function logImporterEnded()
    {
        $this->endTime = microtime(true);

        $this->logLine(sprintf('Articles créés : %1$s ; Articles modifiés : %2$s ; Articles supprimés : %3$s', $this->postsCreated, $this->postsUpdated, $this->postsDeleted), SG_LOG_INFO);

        if ($this->options['colorful']) {
            $this->logLine(sprintf('Logs Informations : %1$s ; '."\033[0;32m".'Succès : %2$s '."\033[0;34m".'; '."\033[1;33m".'Notices : %3$s '."\033[0;34m".'; '."\033[0;31m".'Erreurs : %4$s', $this->logsInfoCount, $this->logsSuccessCount, $this->logsNoticeCount, $this->logsErrorCount), SG_LOG_INFO);
        } else {
            $this->logLine(sprintf('Logs Informations : %1$s ; Succès : %2$s ; Notices : %3$s ; Erreurs : %4$s', $this->logsInfoCount, $this->logsSuccessCount, $this->logsNoticeCount, $this->logsErrorCount), SG_LOG_INFO);
        }

        if ($this->logsNoticeCount>0) {
            $this->logLine('Récapitulatif des notices', SG_LOG_INFO);
            foreach($this->notices as $log) {
                $this->logLine($log['str'], SG_LOG_NOTICE, $log['date']);
            }
        }

        if ($this->logsErrorCount>0) {
            $this->logLine('Récapitulatif des erreurs', SG_LOG_INFO);
            foreach($this->errors as $log) {
                $this->logLine($log['str'], SG_LOG_ERROR, $log['date']);
            }
        }
        $this->logLine(sprintf('Traitement achevé (%1$s sec)', $this->endTime - $this->startTime), SG_LOG_INFO);
    }

    /**
     * Utilitaire qui convertit une constante de type de log (SG_LOG_SUCCESS, SG_LOG_INFO, ...) et une chaîne
     *
     * @param type $logType
     * @return string
     */
    private function logConstToStr($logType)
    {
        switch ($logType) {
            case SG_LOG_DEFAULT:
                return 'default';
            case SG_LOG_LIGHT:
                return 'note';
            case SG_LOG_INFO:
                return 'info';
            case SG_LOG_SUCCESS:
                return 'success';
            case SG_LOG_NOTICE:
                return 'notice';
            case SG_LOG_ERROR:
                return 'error';
        }
    }

    /**
     * Utilitaire qui convertit une constante de type de log (SG_LOG_SUCCESS, SG_LOG_INFO, ...) en couleur bash
     *
     * @param type $logType
     * @return string
     */
    private function logConstToColor($logType)
    {
        switch ($logType) {
            case SG_LOG_DEFAULT:
                return '0;30';
            case SG_LOG_LIGHT:
                return '0;37';
            case SG_LOG_INFO:
                return '0;34';
            case SG_LOG_SUCCESS:
                return '0;32';
            case SG_LOG_NOTICE:
                return '1;33';
            case SG_LOG_ERROR:
                return '0;31';
        }
    }

    /**
     * Ajoute une ligne dans les logs.
     *
     * Prend en compte la couleur, le timestamp et le type de log
     *
     * @param type $str
     * @param type $logType
     */
    protected function logLine($str, $logType=SG_LOG_DEFAULT, $log_date=false)
    {
        if (!$log_date) {
            $log_date = date('y-m-d H:i:s');
        }

        switch ($logType) {
            case SG_LOG_DEFAULT :
                $this->logsDefaultCount++;
                break;
            case SG_LOG_LIGHT :
                $this->logsLightCount++;
                break;
            case SG_LOG_INFO :
                $this->logsInfoCount++;
                break;
            case SG_LOG_SUCCESS :
                $this->logsSuccessCount++;
                break;
            case SG_LOG_NOTICE :
                $this->logsNoticeCount++;
                $this->notices[] = array('date' => $log_date, 'str' => $str);
                break;
            case SG_LOG_ERROR :
                $this->logsErrorCount++;
                $this->errors[] = array('date' => $log_date, 'str' => $str);
                break;
        }

        if (!empty($str)) {
            $logTypeStr = $this->logConstToStr($logType);
            $defaultColor = $this->logConstToColor(SG_LOG_DEFAULT);
            $lightColor = $this->logConstToColor(SG_LOG_LIGHT);
            $logTypeColor = $this->logConstToColor($logType);

            if ($this->options['colorful']) {
                echo "\033[" . $lightColor . "m" . $log_date . "\t" . $logTypeStr . "\t" . "\033[" . $logTypeColor . 'm' . $str . "\n";
                echo "\033[" . $defaultColor . "m";
            } else {
                echo date('y-m-d H:i:s') . "\t" . $logTypeStr . "\t" . $str . "\n";
            }
        }
    }

    protected function logWpError($str, $wp_error, $log_date=false)
    {
        $this->logLine($str, SG_LOG_ERROR, $log_date);
        if (is_wp_error($wp_error)) {
            foreach($wp_error->errors as $code => $message) {
                $this->logLine(sprintf('WP_Error:%1$s => %2$s', $code, implode("\n", $message)), SG_LOG_ERROR, $log_date);
            }
            foreach($wp_error->error_data as $code => $message) {
                $this->logLine(sprintf('WP_Error(data):%1$s => %2$s', $code, implode("\n", $message)), SG_LOG_ERROR, $log_date);
            }
        }
    }

    /**
     * Ouvre un fichier XML. Affiche les logs correspondantes.
     * @param type $path
     * @return boolean
     */
    protected function xmlOpenFileLogged($path)
    {
        if (!empty($path)) {
            $file = simplexml_load_file($path);
            if (!$file) {
                $this->logLine(sprintf('Impossible d\'ouvrir le fichier "%1$s"', $path), SG_LOG_ERROR);
            }
            return $file;
        } else {
            $this->logLine('Le chemin ne peut pas être vide', SG_LOG_ERROR);
            return false;
        }
    }

    /**
     * Retourne la valeur d'un élément XML de manière sécurisée.
     *
     * @param type $parent
     * @param type $elementName
     * @param type $defaultValue
     * @return type
     */
    protected function xmlGetElementValue($parent, $elementName, $defaultValue=null)
    {
        if (isset($parent) && isset($parent->$elementName)) {
            return $parent->$elementName->__toString();
        }
        return $defaultValue;
    }

    protected function assumePath($path)
    {
        $sep = '/';
        $segments = explode($sep, $path);

        $rebuilt_path = '';
        foreach($segments as $segment) {
            $rebuilt_path .= $sep . $segment;
            $this->assumeDirectory($rebuilt_path);
        }
    }

    protected function assumeDirectory($directory)
    {
        if(!file_exists($directory)) {
            $this->logLine(sprintf('Création du dossier : %1$s.', $directory), SG_LOG_INFO);
            mkdir($directory);
        }

        return $directory;
    }

    /**
     * Sauvegarde un term dans WordPress. Affiche les logs correspondantes.
     *
     * @param type $tax
     * @param type $value
     * @param type $importId
     * @return type
     */
    protected function wpSaveTerm($tax, $value, $importId)
    {
        $this->logLine(sprintf('Traitement du terme "%2$s" dans la taxonomie "%1$s" avec l\'id d\'import "%3$s"', $tax, $value, $importId), SG_LOG_INFO);

        $terms = $this->wpFindTermByImportId($tax, $importId);

        if (count($terms) <= 0) {
            $term_id = $this->wpInsertTerm($value, $tax, $importId);
        } else {
            $term_id = $this->wpUpdateTerm($terms, $value, $tax, $importId);
        }

        return $term_id;
    }

    /**
     * Insère un nouveau terme. Affiche les logs correspondantes.
     *
     * @param type $value
     * @param type $tax
     * @param type $importId
     * @return type
     */
    private function wpInsertTerm($value, $tax, $importId)
    {
        $existing_term = term_exists($value, $tax);
        if ($existing_term) {

            $term_id = $this->wpTermArrayToTermId($existing_term);
            $this->logLine(sprintf('Un terme "%2$s" existe déjà sans qu\'il ne soit rattaché à l\'identifiant d\'import', $tax, $value, $importId, $term_id), SG_LOG_NOTICE);

        } else {

            $term = wp_insert_term($value, $tax);

            $term_id = $this->wpTermArrayToTermId($term);

            if ($term_id != 0) {
                $this->logLine(sprintf('Terme "%2$s"créé avec succès sous l\'identifiant "%4$s"', $tax, $value, $importId, $term_id), SG_LOG_SUCCESS);
            } else {
                $this->logLine(sprintf('Erreur lors de la création du terme "%2$s"', $tax, $value, $importId), SG_LOG_ERROR);
            }
        }

        if ($term_id) {
            $this->wpAddTermMeta($term_id, 'import_id', $importId, true);
        }

        return $term_id;
    }

    /**
     * Modifie un terme existant. Affiche les logs correspondantes.
     *
     * @param type $terms
     * @param type $value
     * @param type $tax
     * @param type $importId
     * @return type
     */
    private function wpUpdateTerm($terms, $value, $tax, $importId)
    {
        foreach($terms as $term) {
            $args = array(
                'name' => $value,
            );
            $term = wp_update_term($term->term_id, $tax, $args);

            $term_id = $this->wpTermArrayToTermId($term);

            if ($term_id != 0) {
                $this->logLine(sprintf('Terme "%2$s" modifié avec succès sous l\'identifiant "%4$s"', $tax, $value, $importId, $term_id), SG_LOG_SUCCESS);
            } else {
                $this->logLine(sprintf('Erreur lors de la modification du terme "%2$s"', $tax, $value, $importId), SG_LOG_ERROR);
            }
        }

        return $term_id;
    }

    /**
     * Utilitaire : convertit le tableau contenant l'identifiant d'un term obtenu par wp_insert_term en un entier contenant l'identifiant du term.
     *
     * @param type $term
     * @return type
     */
    private function wpTermArrayToTermId($term)
    {
        if (array_key_exists('term_id', $term)) {
            return $term['term_id'];
        }

        return $term;
    }

    /**
     * Ajoute une méta au term. Affiche les logs correspondantes.
     *
     * @param type $term_id
     * @param type $meta_name
     * @param type $meta_value
     * @param type $unique
     */
    protected function wpAddTermMeta($term_id, $meta_name, $meta_value, $unique)
    {
        $this->logLine(sprintf('Traitement de la méta de term "%2$s" avec la valeur "%3$s" au terme "%1$s"', $term_id, $meta_name, $meta_value, $unique), SG_LOG_INFO);
        $term_meta = update_term_meta($term_id, $meta_name, $meta_value);

        if ($term_meta != 0) {
            $this->logLine(sprintf('Terme meta "%2$s" ajoutée avec succès sous l\'identifiant "%5$s"', $term_id, $meta_name, $meta_value, $unique, $term_meta), SG_LOG_SUCCESS);
        } else {
            $old_value = get_metadata('term', $term_id, $meta_name);
            if (count($old_value) == 1) {
                if ($old_value[0] === $meta_value) {
                    $this->logLine(sprintf('Meta de terme "%2$s" inchangée, non modifiée pour le terme %1$s', $term_id, $meta_name, $meta_value, $unique), SG_LOG_SUCCESS);
                } else {
                    $this->logLine(sprintf('Erreur lors de la modification du meta de terme "%2$s"', $term_id, $meta_name, $meta_value, $unique), SG_LOG_ERROR);
                }
            } else {
                $this->logLine(sprintf('Erreur lors de la modification du meta de terme "%2$s"', $term_id, $meta_name, $meta_value, $unique), SG_LOG_ERROR);
            }
        }
    }

    /**
     * Sauvegarde un post. Affiche les logs correspondantes.
     *
     * Gère également l'ajout du meta import_id qui garde le lien avec l'identifiant distant.
     *
     * @param type $importId
     * @param type $postType
     * @param type $title
     * @param type $status
     * @return type
     */
    protected function wpSavePost($importId, $postType, $title, $status, $post_args = array())
    {
        $post_id = false;

        $this->logLine(sprintf('Traitement de l\'article (%3$s) "%2$s" avec l\'id d\'import "%1$s"', $importId, $title, $postType), SG_LOG_INFO);

        $posts = $this->wpFindPostByImportId($postType, $importId);
        if (count($posts) <= 0) {

            $post_id = $this->wpInsertPost($postType, $title, $status, false, $post_args);

            if ($post_id != 0) {
                $this->postsCreated++;
                $this->logLine(sprintf('Article "%2$s" créé avec succès sous l\'identifiant "%4$s"', $importId, $title, $postType, $post_id), SG_LOG_SUCCESS);
            } else {
                $this->logLine(sprintf('Erreur lors de la création de l\'article "%2$s"', $importId, $title, $postType, $post_id), SG_LOG_ERROR);
            }

            update_field('import_id', $importId, $post_id);

        } else {

            foreach($posts as $post) {

                $post_id = $this->wpInsertPost($postType, $title, $status, $post->ID, $post_args);

                if ($post_id != 0) {
                    $this->logLine(sprintf('Article "%2$s" modifié avec succès sous l\'identifiant "%4$s"', $importId, $title, $postType, $post_id), SG_LOG_SUCCESS);
                } else {
                    $this->logLine(sprintf('Erreur lors de la modification de l\'article "%2$s"', $importId, $title, $postType, $post_id), SG_LOG_ERROR);
                }
            }
        }

        return $post_id;
    }

    /**
     * Sauvegarde définitivement un post. Affiche les logs correspondantes.
     *
     * @param type $importId
     * @param type $postType
     * @param type $title
     */
    protected function wpDeletePost($importId, $postType, $title)
    {
        $this->logLine(sprintf('Article (%3$s) "%2$s" avec l\'id d\'import "%1$s" à supprimer', $importId, $title, $postType), SG_LOG_INFO);

        $posts = $this->wpFindPostByImportId($postType, $importId);
        if (count($posts) <= 0) {

            $this->logLine(sprintf('Article "%1$s" inexistant', $importId, $title, $postType), SG_LOG_SUCCESS);

        } else {

            foreach($posts as $post) {
                if (wp_delete_post($post->ID, true)) {
                    $this->postsDeleted++;
                    $this->logLine(sprintf('Article "%4$s" supprimé avec succès', $importId, $title, $postType, $post->ID), SG_LOG_SUCCESS);
                } else {
                    $this->logLine(sprintf('Erreur lors de la suppression de l\'article "%4$s"', $importId, $title, $postType, $post->ID), SG_LOG_ERROR);
                }
            }

        }
    }

    /**
     * Ajoute un post.
     *
     * @param type $postType
     * @param type $title
     * @param type $status
     * @param type $id
     * @return type
     */
    protected function wpInsertPost($postType, $title, $status, $id=false, $additionnal_post_args = array())
    {
        $defaults = array(
            'post_type' => $postType,
            'post_status' => $status,
            'post_title' => $title,
        );

        $post_args = wp_parse_args($additionnal_post_args, $defaults);

        if ($id) {
            $post_args['ID'] = $id;
            $post_id = wp_update_post($post_args);
        } else {
            $post_id = wp_insert_post($post_args);
        }


        if ($id) {
            if ($post_id != 0) {
                $this->postsUpdated++;
            }
        } else {
            if ($post_id != 0) {
                $this->postsCreated++;
            }
        }

        return $post_id;
    }

    /**
     * Ajoute une méta au post. Affiche les logs correspondantes.
     *
     * @param type $meta_name
     * @param type $meta_value
     * @param type $post_id
     * @param type $post_type
     * @return type
     */
    protected function wpAddPostMeta($meta_name, $meta_value, $post_id, $post_type)
    {
        $this->logLine(sprintf('Traitement de la meta "%1$s" pour le post "%3$s"', $meta_name, $meta_value, $post_id), SG_LOG_INFO);

        $existing_meta = get_post_meta($post_id, $meta_name);

        if ($existing_meta) {
            if (count($existing_meta) > 0) {
                if ($existing_meta[0] == $meta_value) {
                    $this->logLine(sprintf('Meta "%1$s" inchangée, non modifiée pour le post "%3$s"', $meta_name, $meta_value, $post_id), SG_LOG_SUCCESS);
                    return;
                }
            }
        }

        $meta_id = update_post_meta($post_id, $meta_name, $meta_value);

        if ($meta_id) {
            $this->logLine(sprintf('Meta "%1$s" créé avec succès sous l\'identifiant "%4$s"', $meta_name, $meta_value, $post_id, $meta_id), SG_LOG_SUCCESS);
        } else {
            $this->logLine(sprintf('Erreur lors de la création de la meta "%1$s" (code de retour update_post_meta: %4$s)', $meta_name, $meta_value, $post_id, $meta_id), SG_LOG_ERROR);
        }
    }

    protected function wpAddAcfField($meta_name, $meta_value, $post_id, $post_type)
    {
        $this->logLine(sprintf('Traitement du field "%1$s" pour le post "%3$s"', $meta_name, $meta_value, $post_id), SG_LOG_INFO);

        $existing_meta = get_post_meta($post_id, $meta_name);

        if ($existing_meta) {
            delete_field($meta_name, $post_id);
        }

        $meta_id = update_field($meta_name, $meta_value, $post_id);

        if ($meta_id) {
            $this->logLine(sprintf('Field "%1$s" créé avec succès sous l\'identifiant "%4$s"', $meta_name, $meta_value, $post_id, $meta_id), SG_LOG_SUCCESS);
        } else {
            $this->logLine(sprintf('Erreur lors de la création du field "%1$s" (code de retour update_field: %4$s)', $meta_name, $meta_value, $post_id, $meta_id), SG_LOG_ERROR);
        }
    }

    /**
     * Sauvegarde un terme de taxonomie pour un post
     *
     * @param type $term_value
     * @param type $term_name
     * @param type $post_id
     */
    protected function wpAddPostTerm($term_value, $term_name, $post_id)
    {
        if (!empty($term_value)) {
            $this->logLine(sprintf('Traitement du terme "%1$s" dans la taxonomie "%2$s" pour l\'article "%3$s"', $term_value, $term_name, $post_id), SG_LOG_INFO);

            $term_id = term_exists($term_value, $term_name);
            if (!$term_id) {
                $this->logLine(sprintf('Ajoute le terme "%1$s"', $term_value, $term_name, $post_id), SG_LOG_INFO);
                $term = wp_insert_term($term_value, $term_name);
                $term_id = term_exists($term_value, $term_name);
            }

            if ($term_id) {
                $this->logLine(sprintf('Terme "%1$s" ajouté avec succès', $term_value, $term_name, $post_id), SG_LOG_SUCCESS);
                wp_set_object_terms($post_id, (int)$term_id['term_id'], $term_name, true);
            } else {
                $this->logLine(sprintf('Erreur lors de l\'ajout du terme "%1$s" dans la taxonomie "%2$s"', $term_value, $term_name, $post_id), SG_LOG_ERROR);
            }
        }
    }


    /**
     * Retourne un post en connaissant son identifiant d'import (l'id distant).
     *
     * @param type $postType
     * @param type $importId
     * @return type
     */
    protected function wpFindPostByImportId($postType, $importId)
    {
        $posts = get_posts(array(
            'numberposts'   => -1,
            'post_type'     => $postType,
            'post_status'   => array('publish', 'draft', 'trash'),
            'meta_query'    => array(
                'relation' => 'AND',
                array(
                    'key'   => 'import_id',
                    'value' => $importId,
                ),
            ),
            'orderby'       => 'ID',
            'order'       => 'ASC',
            'suppress_filters' => 0,
        ));

        return $posts;
    }

    /**
     * Retourne un term en connaissant son identifiant d'import.
     *
     * @param type $tax
     * @param type $importId
     * @return type
     */
    protected function wpFindTermByImportId($tax, $importId)
    {
        $terms = get_terms($tax, array(
            'hide_empty' => false,
            'meta_query' => array(
                array(
                   'key'       => 'import_id',
                   'value'     => $importId,
                )
            )
        ));

        return $terms;
    }

    /**
     * Source : https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
     */
    protected function findAttachmentByUrl($url)
    {
        global $wpdb;

        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url ));

        if (is_array($attachment) && count($attachment)>0) {
            return $attachment[0];
        }

        return false;
    }

    /**
     * Source : https://wordpress.stackexchange.com/questions/216913/how-to-convert-the-file-path-to-a-url-of-the-same-file
     */
    protected function pathToUrl($path)
    {
        $url = str_replace(
            wp_normalize_path(untrailingslashit(ABSPATH)),
            site_url(),
            wp_normalize_path($path)
        );
        return esc_url_raw($url);
    }

    /**
     * Sauvegarde un média en recherchant si son url existe déjà
     */
    protected function wpSaveAttachment($filename, $post_id, $additionnal_post_args)
    {
        $file_url = $this->pathToUrl($filename);
        $attach_id = $this->findAttachmentByUrl($file_url);

        $this->logLine(sprintf('Recherche de l\'attachment d\'url "%1$s".', $file_url), SG_LOG_INFO);

        if ($attach_id) {
            $this->logLine(sprintf('Attachment d\'url "%1$s" trouvé sous l\'identifiant "%2$s".', $file_url, $attach_id), SG_LOG_SUCCESS);
            return $attach_id;
        }

        $this->logLine(sprintf('Attachment d\'url "%1$s" non trouvé.', $file_url), SG_LOG_INFO);
        return $this->wpInsertAttachment($filename, $post_id, $additionnal_post_args);
    }

    /**
     * Insère inconditionnellement un média en base
     */
    protected function wpInsertAttachment($filename, $post_id, $additionnal_post_args)
    {
        // Crée le média en base
        $wp_filetype = wp_check_filetype($filename, null );
        $wp_upload_dir = wp_upload_dir();

        $defaults = array(
            'guid' => untrailingslashit($wp_upload_dir['url']). '/' . basename($filename),
            'post_status' => 'inherit',
            'post_title' => preg_replace( '/\.[^.]+$/', '', basename($filename)),
            'post_name' => sanitize_title_with_dashes(str_replace("_", "-", $filename)),
            'post_parent' => $post_id,
            'post_type' => 'attachment',
            'post_mime_type' => $wp_filetype['type'],
        );
        $post_args = wp_parse_args($additionnal_post_args, $defaults);

        $this->logLine(sprintf('Insertion de l\'attachment "%1$s" aux médias.', basename($filename)), SG_LOG_INFO);
        $attach_id = wp_insert_attachment($post_args, $filename, $post_id);

        if ($attach_id) {
            $this->logLine(sprintf('Insertion de l\'attachment "%1$s" aux médias réalisée avec succès sous l\'identifiant "%2$s".', basename($filename), $attach_id), SG_LOG_SUCCESS);

            // Génère les metadatas
            $this->logLine(sprintf('Génération des metadatas du fichier "%1$s".', basename($filename)), SG_LOG_INFO);
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            try {
                if ($attach_data = wp_generate_attachment_metadata($attach_id, $filename)) {
                    $this->logLine(sprintf('Génération des metadatas du fichier "%1$s" réalisée avec succès.', basename($filename)), SG_LOG_SUCCESS);
                    wp_update_attachment_metadata($attach_id, $attach_data);
                }
            } catch (Exception $e) {
                $this->logLine(sprintf('Erreur lors de la génération des metadatas du fichier "%1$s" : %2$s.', basename($filename), $e->getMessage()), SG_LOG_ERROR);
            }
        } else {
            $this->logLine(sprintf('Erreur lors de l\'insertion de l\'attachment "%1$s" aux médias.', basename($filename)), SG_LOG_ERROR);
        }

        return $attach_id;
    }

    protected function setPostThumbnail($post_id, $attach_id)
    {
        $this->logLine(sprintf('Fait de l\'attachment "%1$s" l\'image à la une pour "%2$s".', $attach_id, $post_id), SG_LOG_INFO);
        set_post_thumbnail($post_id, $attach_id);
    }

    protected function p2pClearConnections($connection_type, $from_id)
    {
        $this->logLine(sprintf('Supprime toutes les connexions %1$s de %2$s', $connection_type, $from_id), SG_LOG_INFO);

        $disconnected_ids = array();
        $connection = p2p_type($connection_type);
        if ($connection) {
            $connected = get_posts(
                array(
                    'post_status' => array('publish', 'trash', 'future', 'pending'),
                    'connected_type' => $connection_type,
                    'connected_items' => $from_id,
                    'nopaging' => true,
                    'suppress_filters' => false
                )
            );
            foreach($connected as $member) {
                $to_id = $member->ID;
                $success_or_wp_error = $connection->disconnect($from_id, $to_id);
                if (is_wp_error($success_or_wp_error)) {
                    $this->logWpError(sprintf('La connexion %1$s de %2$s vers %3$s n\'a pas pu être ajoutée.', $connection_type, $from_id, $to_id), $success_or_wp_error);
                } else {
                    $disconnected_ids[] = $to_id;
                }
            }
            $this->logLine(sprintf('Les connexions %1$s de %2$s vers %3$s ont été supprimées.', $connection_type, $from_id, implode(', ', $disconnected_ids)), SG_LOG_SUCCESS);
        } else {
            $this->logLine(sprintf('La connexion %1$s posts2posts n\'existe pas.', $connection_type), SG_LOG_ERROR);
        }

    }

    protected function p2pConnect($connection_type, $from_id, $to_id)
    {
        $this->logLine(sprintf('Ajoute la connexion %1$s de %2$s vers %3$s', $connection_type, $from_id, $to_id), SG_LOG_INFO);

        $connection = p2p_type($connection_type);
        if ($connection) {
            $success_or_wp_error = $connection->connect(
                $from_id,
                $to_id,
                array(
                    'date' => current_time('mysql')
                )
            );
            if (is_wp_error($success_or_wp_error)) {
                $this->logWpError(sprintf('La connexion %1$s de %2$s vers %3$s n\'a pas pu être ajoutée.', $connection_type, $from_id, $to_id), $success_or_wp_error);
            } else {
                $this->logLine(sprintf('La connexion %1$s de %2$s vers %3$s a été ajoutée avec succès.', $connection_type, $from_id, $to_id), SG_LOG_SUCCESS);
            }
        } else {
            $this->logLine(sprintf('La connexion %1$s posts2posts n\'existe pas.', $connection_type), SG_LOG_ERROR);
        }
    }
}
