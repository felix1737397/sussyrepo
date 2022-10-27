<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress2021_michaudfelix' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'mysql' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         '.v{Rv)=_5Oe{.`U*|mloh%cj{6?G5z*7Tf8i;m`rC},1zo,E~Q<T/O5T/s`ptaO&' );
define( 'SECURE_AUTH_KEY',  '|.y*DqYxp)uSU{PoRY.!;Amc`d>G.^[G+>n ok*+k^o~00a.@BM0[a}:?U)P,nMV' );
define( 'LOGGED_IN_KEY',    'bXpM=Q*X?4t<r43Mf[_5:.w~yQ2<HeGb:4Cx?r@0nkZb7]9ahWwi=Z%!=nc=.+81' );
define( 'NONCE_KEY',        'ZF?=VI?8$[gtBd<yaeuV|b`ki*v0S2Z3y,@twQOdXRJb1}GW+2rc;4vbNYN}?Uf*' );
define( 'AUTH_SALT',        'Ka[(EU{~C]xrCp(@Ii7s<$rs(Qs4EmB$:9+(/[(gWSfZKQk+E$^TdtUtzC6|gCZ%' );
define( 'SECURE_AUTH_SALT', 'ckyZi#;X9-#9z40$09q<]1u79$f[ad$Kd]dAYM`zYH>I];RR/fw*eCrjy8r?,W!G' );
define( 'LOGGED_IN_SALT',   ':[BlUfX10Zht|Sy(<VERK|/Pj/IfG-S[uV2mr8|)o({wHJ~` _&?##Ri]&W(^Ecl' );
define( 'NONCE_SALT',       'T<.|Z.F;rx&CwS=uMwZw[Z~KjS)eTmdhVHeY(]v1HdA%S9boN:_kCYHN_F_1fazg' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'fyhla8_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
// Active (true) ou désactive (false) le mode débogage
define( 'WP_DEBUG', false );

// Pendant le débogage, lorsqu'une erreur est rencontrée, n'affiche pas de message d'erreur.
// Plutôt, WordPress enverra un codes d'état HTTP 500 au navigateur.
define( 'WP_DEBUG_DISPLAY', true );

// Pendant le débogage, WordPress enregistrera les messages d'erreurs dans le fichier wp-content\debug.log.
define( 'WP_DEBUG_LOG', true );

// En production (lorsque WP_DEBUG est à false), n'affiche pas les erreurs à l'écran.
@ini_set( 'display_errors', '0' );


/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';



/**
 *definition de l'adresse courriel d'envoie
 *
 */
define( 'SMTP_HOST', 'mail.hackezmoipassvp.com' );
define( 'SMTP_AUTH', true );
define( 'SMTP_PORT', '587' );  // ou 465
define( 'SMTP_SECURE', 'tls' );   // ou ssl
define( 'SMTP_USERNAME', 'homeassistant@hackezmoipassvp.com' );
define( 'SMTP_PASSWORD', 'jaimeleweb123' );
define( 'SMTP_FROM', 'homeassistant@hackezmoipassvp.com' );
define( 'SMTP_FROMNAME', 'Mon site Web' );