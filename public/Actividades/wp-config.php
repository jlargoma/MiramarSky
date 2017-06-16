<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'miramarski_blog');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', '');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'pm;j+~k>dY$$:Mt5FJ7[[TWR_+X9.x3[`5dx,:*]+?#}G=&G$IAk[U8Oe`%:Wqf{');
define('SECURE_AUTH_KEY', 'dBn$y!f?AxoLm~c0bOhv,~7?6QW-c<jV$4E&hws vidu[UVV2O,r;|JT.3Tw/RAR');
define('LOGGED_IN_KEY', '-&!Ye~JwNBi&k[6IX+O8]dc^*`sWGcH1*v325#t5Y<qpt*Ptp9OTa6 (j{?4w G}');
define('NONCE_KEY', '09s>,kw@G0:zbAckxfsO(vGD~GZnI?+{aKT?)C(xExR*{m-sMTvIKyRqL9U2Mqkp');
define('AUTH_SALT', 'y<<lJ_iA6N}:}i|P:[q)K=J&@y#II<3>@DW+$qem]Xi#47Urh~+J_x5Sb{t=v|Pv');
define('SECURE_AUTH_SALT', '] yKWRz%}L$WGOIUh:dM h&XGY 0wIMaJ/f; 6s{!.o>*UYV)B$K>nbut2ps/?@b');
define('LOGGED_IN_SALT', 'w5N6??CT!dGBuF5x~{tNV3fHuWp(7L `{}W[/g o8_ ZHNf0R(T|qRVR;m,Yb;};');
define('NONCE_SALT', '?y31/fVf234){Bx=_;[WK@mS61,q>],wlJbxax)+HWm/?Qj3ktMN54M*DtQThe4L');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

