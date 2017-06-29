<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'feed');

/** MySQL database username */
define('DB_USER', 'feed');

/** MySQL database password */
define('DB_PASSWORD', 'feed');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'gb*!@jd$9G(.W=Kv}W2_R53YRP-#iFUge_==yIV*hm~p`.Hd6Pi+4n_T/1}&Iwp^');
define('SECURE_AUTH_KEY',  'r32uVvcw/V#Fu/IyGy:Ceqx<$3EAbn3d)K5GNF*:f6y*#geiB[`]S_4V}if&lk3`');
define('LOGGED_IN_KEY',    '3^(NEUn,:!iB=C6k}@tM3;e[C6L{%WD0X{*Lued)G8w9sxQ=`=F<q*5A<N%}fsfU');
define('NONCE_KEY',        '19bA(l$:LEH$$s[(zJXBC%;n%NRdy1tdN(9hnA %e}p94?O,%ABB 48dG.mMi!OI');
define('AUTH_SALT',        ',[Z&NaqH7]O}S+l$lk%9$B?xyX,I?`sl*8<u6!|7UMl: l/#Mo)JnAE@m5-XGj+&');
define('SECURE_AUTH_SALT', 'r<yj/t<KgH;m^impjWn}An8ltwp%nH(K7N@p.Tl]=-%m6S|-M&_2Q%Ompw65 I?R');
define('LOGGED_IN_SALT',   'GxYj(w2d%HI*(]$E+(tz}/lE_!<PWO $nKvEiuB_Y/;xKV3]E pZ6kbc.zceezI9');
define('NONCE_SALT',       'Sj2o6qHXIJ6@N:VecC~CcF>2cl;((#-F*jn_2BBCeOVjblJR>>gUd*lN?Z]U9&zO');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
// define('WP_DEBUG', true);
@ini_set( 'log_errors', 'On' );
@ini_set( 'display_errors', 'On' );
@ini_set( 'error_log', 'php_error.log' );
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
