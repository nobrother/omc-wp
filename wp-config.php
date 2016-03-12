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
define('DB_NAME', 'web');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'qTS8C.|3~(g=(x$96kgIQ8lF.xxIuY[<wjVEzp1sS<f+7q<{1.RVn7h.Y:=6pS7d');
define('SECURE_AUTH_KEY',  '_CNf#ALX31Iw8o:BdQ/>ZpQsI<RLUgRfn]`Q9;lv=lS!b?Ey&pohSc7`kJN`jtqu');
define('LOGGED_IN_KEY',    'oj]vKM7sQ+hG8LCeY!iQvG{km5=S90-WO!v{By!k>Wm9 !G^d,M0>[.d`#e$!N-3');
define('NONCE_KEY',        't9Ov^m9Toqi:*[|F}2-W5m60@-2?gZ`-c]%TH+ScV,uv5L]>lak$<%C5imw{$0LQ');
define('AUTH_SALT',        'ib }]6z-,6^&V8nu&wM%=H&,dp9V(M>pAGusQ^8c-?-f,lPP&Z:L~o<M~Js bU=|');
define('SECURE_AUTH_SALT', 's h6Hj=8eR.H..WN25Ju|+&eVoD.-J:KgJ_OQCn-n@R@iPOiF|O}/xN]*gs%|HM(');
define('LOGGED_IN_SALT',   'D0o@f`~<8MSM`am+z`=>kZD1$WvFjBcR#RvEaX~7aW9^M@BHgG@OzC}KVr f,S9{');
define('NONCE_SALT',       'J}Yj}3%(DqRyb|5P2#_d(-T}9/Wu?CKz0|pu/q+yBB|-@2^,f2--Mb+rO9S`/7u[');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'web_';

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
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
