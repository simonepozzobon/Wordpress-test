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
define('DB_NAME', 'wp');

/** MySQL database username */
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', 'password');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

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
define('AUTH_KEY',         '(=z?wx/5x*Jto38/!{q3bvjK&|UogQdh|d(zg`MD1hI$#O9bzRvRSMdXy[,Twb(I');
define('SECURE_AUTH_KEY',  '=N<DMAeQ6k.Kq?Vi).IUN qR,NBh63q#Va[Ti*2T{jM`SB^%C 5afGTuLTSNz;bb');
define('LOGGED_IN_KEY',    'Bt7G-owT4G[nxrD=)}6:YA#cJ(|iKJXZe#y~#R~!^$an:h*kA^R+5<=(m^3d?5]}');
define('NONCE_KEY',        'p{C9CHl%urKaLAR-dt[b$12bEQ>(;)vH6[HHDw{>t[&6MJ+i2PEd~rQ!{9Tf%uuM');
define('AUTH_SALT',        'Y$I5|Y>,*{]jp/6fr.a(%>MtpsQr~)*)wFK`H$}OX:o#$L`JY$L%;^<G}o8i6Dy]');
define('SECURE_AUTH_SALT', 'TKy6pCO/hNM.)6c)g.b[.a2]9hZW/Ud-fx/NOe*ihcb{6&qsO<kXq?t7jJ`Q>x--');
define('LOGGED_IN_SALT',   'l=9)BD$6<W;Ym,}HR*c&8zv(35Rpi=#Rn1%3vGjFUxrB$ui~|#J?(Bg wPf]JAX`');
define('NONCE_SALT',       'O^X<frit-_P4S] jxRM5(M> ./%.xP@|L:%:-1:!sfCC8Z?(9[FQ+:Z4jxhiVR3y');

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
