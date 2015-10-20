<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'paellaby_wor1194');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY', 'HahZn=ZJxvu;oSY)QzZ[uX!+wZ+M_WT*tohE{/ylw!Nj}qOK[i^Wl|ClW[%tcLvSo-+]Wy/UEt=XMYwDsY[IjSss;{}jRkqUuR%-rKS-p-SZpldeJ-aARAePVBkp+yY)');
define('SECURE_AUTH_KEY', 'VmZNQN|+zz/h/<B<@;!$)]a*y}cZgY{hE>JPIZ_xi&[pju(=;KjQXRQm/&-dCNa$@ngX*>xDL&gfe][ft;krd;-lsGysOu|oNe&ysSnJOG(U??J+?fSB?lyn|H?O?sMJ');
define('LOGGED_IN_KEY', 'XMj;Z{?HD}cA/?]X}%w&%Fl=-oDE<NJb?cUerT)EoHJEWtBWKH}i@B!HV]eoeaG[P{(a]A/ts<y;d?KhrI|dibM^hQ|or^<SKDZrUdubAxc(iOSpY{Ggjc/>Wyovz&t^');
define('NONCE_KEY', '(c/Z$oFe[xqd<oF&U)zwTYAhHR&Zw&HbQ]kF(+B$oObY@E_Z@>j(Z?SpOT%]bGw!X)sZt?OmTDTzWZ=y[YOE)fT-pMKog)YdufR^!VUE-@|n/dlI_Nqt{Yj%*n|M&*rO');
define('AUTH_SALT', 'JBgpyDyRpbm=[tyO;[[-+p}LWt@xFmylMuXmvBt$CUlDv/TTgKfEUq*(]eDwr+R)@mrv<Zrm|kV@Yg=<tecU$bCCS=sNcZQo!GsQDKbJQo)OE<?wrivKUXN!x)aPmN@>');
define('SECURE_AUTH_SALT', 'D/>CIg=%ANsWx>!R)(ebDKN{lEqnMG_>Eth]iLV|wdyq+]*x]OBk}aHdL%C_RXF@pgrfngP&|!gcS]F*?jU!yPjagl<ln]bzaEUx/mIO{&U$ObtOZR-*L(DmDatVeFe]');
define('LOGGED_IN_SALT', 'H+$T)-I-|zjW*f{HP=A<$frUaw{(z$BrGnm{aepK(tom^D[D^%Ewe%+xxmxaOM%@^S*j_AUKt&{jPxk!q<nX/<GVVI=yS&n}WDb$H{LMi)a-dZ$L>oToGJLtqBc}C]ZL');
define('NONCE_SALT', 'kf+}W{!|T_<>D;XWiMa?QKtml$nFI>LMI_GE;<FP]QOLkS<ZqkZmTlwGSx/|*O[%(|vSNwbeBeyrfI_uFYZN+qz_VZClD}l@*SJN%G=nn{W};ed]ooAi*caSrgttdE)b');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_vqnu_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

/**
 * Include tweaks requested by hosting providers.  You can safely
 * remove either the file or comment out the lines below to get
 * to a vanilla state.
 */
if (file_exists(ABSPATH . 'hosting_provider_filters.php')) {
	include('hosting_provider_filters.php');
}
