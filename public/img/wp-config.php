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
define('DB_NAME', 'evolutio_blog');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'evolutio_blog');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'exitO@007');

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
define('AUTH_KEY', '=)-5vQp8c:9#vj(DQtnev6;,~PFOp#c3<gCcz$HNG,3+XSBVA!Dah,RW>brs@Q?_');
define('SECURE_AUTH_KEY', 'Pgp::hGAIWILIB6I@Ca*ROPH,T= TS)]P){fK%6,OBHk9HIkK]/39AXzy7C$eCF3');
define('LOGGED_IN_KEY', 'O3$dIB>!3&rZm4m%F  :Rcl2a<,l0wJTobG_HG3JH,^*`6Q_InXn3+-@MG^uY#+L');
define('NONCE_KEY', '$O%HQI4Z%w(%8H5zqn@7GTUna aBB XrO5<( #P6ZO%+iB3[PwwYg.>+@{MV(_(N');
define('AUTH_SALT', 'W,60553XxPa.NK+kI5s,6oaOe#Zm.c6QT?9- wyaZAruoUZ R_]zsV2KuC{W)8$A');
define('SECURE_AUTH_SALT', '?yh$i`xp_#ZPsL=/y ;IQpA2QFmyZ@I=9a/&5a<A4`J&9k}JZ[Ekf~?x@NE~F+[c');
define('LOGGED_IN_SALT', '8m|2Aq^c_{9I}:YJD+vx4uQK%9&>Y78>ORk3355UF%b-(-=&HQKhU2M:>E+wW!3!');
define('NONCE_SALT', '0G26<_$`|12;Ao,rFKHq=`PrzqV9Bl,[u-xt<b`2*@ K$V$DQ.VrAOc@5Ahfnc4n');

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

