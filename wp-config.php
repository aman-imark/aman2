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
define('DB_NAME', 'campsite');

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
define('AUTH_KEY',         'eSl0ukL{_5p <>j5zz_2l8vrMtj) #?NQ.yMx[XTY]7eT5Swjc!+;E;eg4%f9VIS');
define('SECURE_AUTH_KEY',  'FiXXXRh4X%Z5?}JevQ_zJdq0L!~^&HRS4N#).]L^2t6`)!GT+89 #G=C~1s/Qc-l');
define('LOGGED_IN_KEY',    '<AF~=gs6r</|w*Hv-/-1&7BJ!F-GtL!p@5G/Mh/*A~!`Dxd>}yLq|oYW,S.m4D1V');
define('NONCE_KEY',        'V/mTqYRryqZv+ZGlb~g>;mVr:>@%Y8nn1Lu%mo>D?~=i/c9#b1v08Uzte}1M^:.E');
define('AUTH_SALT',        'JR^pp7qDHkrx~QmHUw}cOm]SFhMl:!t<#(V/h&*3Ihfm4<Bt$M8,|t]^.:z4%|!s');
define('SECURE_AUTH_SALT', '8_Bs&;arImQ(K4i]>LIT#8!:qFrBu@zO5HY5gxTcbXP]B/f@Zy[4IMFIO!*Z^(Ss');
define('LOGGED_IN_SALT',   'e;Bf$(e-8iy?/gFC`!xZQ:-Zq2r6*>R9x2VOnN_~_9L]+LNaVO)/_S4vBuJ+IEAJ');
define('NONCE_SALT',       '({;5{=Goc.gwr)7z9faso1,>?X)ci:Hl]!)3/+^${!ad}Gspl! E3!3#m$Qy7#^?');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
