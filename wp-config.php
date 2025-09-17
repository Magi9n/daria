<?php
define( 'WP_HOME', 'https://daria.mate4kids.com' );
define( 'WP_SITEURL', 'https://daria.mate4kids.com' );
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'admin_80944' );

/** Database username */
define( 'DB_USER', 'admin_80944' );

/** Database password */
define( 'DB_PASSWORD', 'dab6cbce26d4f8a74680' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'WItWbzpIpVb{pQ0JWShGQ8vM9y0)@i+vV4+xcD y.[G;>]ix&EBP:yCN]?P+f<OJ' );
define( 'SECURE_AUTH_KEY',  '`A6<<I+cWD4[yt60Uc ,7BZzA/6nN`GIjnGt`F)F{|T.8lR}a>}Lejh|X31>hPUj' );
define( 'LOGGED_IN_KEY',    ' beafzgWCS~mTvLx]#s^{20^a{YZ&yd4KzfmT*8.,2$c6]iLhQY5^tUx;dfHcN[F' );
define( 'NONCE_KEY',        'h!r|,.[N6g1M:yQyJfjhWc|-dq9`EYbzes%_s3)C_PX#9},UX<EW,TD[R9!i}P1!' );
define( 'AUTH_SALT',        'C`}b3rv`iOtXsf95k(M(A<o!qz%7@y8^k{^buNk-P=I145{9h612bDn>wet$Q6#y' );
define( 'SECURE_AUTH_SALT', '@.KBC|T;B+ET^4)NS4nzS. >u1wAZj;C/g7_#?1>Qn1VdaNyK]f1a5zaiQNT@$w&' );
define( 'LOGGED_IN_SALT',   '#z9OBE$I@+]0R8,6{@h<9g0s-bV>Q&m?+53@xgPKer1cdH-Z=vk8$Ab^ydO!Nku(' );
define( 'NONCE_SALT',       '9FV{`w=bczIL@X:fLny~21/3T@1nv?q9Z8Re^dvVl9?2MHc]Z!zPxn.MgzM`%`$t' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';


/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
// Habilitar el modo de depuración
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );

// Asegurarse de que los errores se registren
error_reporting( E_ALL );
ini_set( 'display_errors', 0 );
ini_set( 'log_errors', 1 );
ini_set( 'error_log', __DIR__ . '/wp-content/debug.log' );

// Desactivar caché de objetos
define( 'WP_CACHE', false );

// Aumentar el límite de memoria
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );

// Desactivar revisiones de WordPress
define( 'WP_POST_REVISIONS', false );


/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

