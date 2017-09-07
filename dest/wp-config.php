<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'wp_beneteau');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'wp_beneteau');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'wp_beneteau');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'K_Phi@a1kgd_wMh39%Z?3;(f!P[5f5y4 ^*<i<KaY)Ic2@hH5;D(`e.QdQOKs1*V');
define('SECURE_AUTH_KEY',  'Xi|fyz&kOy*r^#_3R*)w}o![ke{^H-:U>v[&1IK>r:4r*skO-|+rC.Jj~Z>}*Pkv');
define('LOGGED_IN_KEY',    '8^x0$4BdtMY?]S8$UkH#`$0jw}!GHiBc|(Xg;13rLP>|d~3HGU YgtX@8_Tx>1Q?');
define('NONCE_KEY',        '|L7rl[1g?X*x1@( %%.&p8OACH%i6y6:zZ^,G,p-q6+H^gJ7N muY)~c]E[H=3HK');
define('AUTH_SALT',        'UrX/mES~lmu)/bgY&jUJZ6|Io4WS[9Q-bf=TO<.[|^3tT,KTA@U?U7}L7 (|/;S-');
define('SECURE_AUTH_SALT', 'A[p/{=}Wm!U7{rf99_>|~rK7zV47w|G%0H6SuGc5U6VXfeln&d=y%Y}b;AILqQGM');
define('LOGGED_IN_SALT',   '0=j}KnWRC^B[*_-y##h12Lz9MAXVUwj{Rl>=0iU3e9Ip_^Z}-{RS,vgx70Jo1{a,');
define('NONCE_SALT',       'DU9a+p)4WaCI<e5[^+1sgFnhz!E9emz^;]j@B1]cT&1,z)I:vv~QuX1p2~:Ldsr1');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Multisite */
define('WP_ALLOW_MULTISITE', true);
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'beneteau.dev');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* C’est tout, ne touchez pas à ce qui suit ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
