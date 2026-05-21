<?php
/**
 * =============================================================================
 * CIERRE DE SESIÓN - core/cerrar_sesion.php
 * =============================================================================
 * Script alternativo de logout accesible directamente por URL.
 *
 * Nota: el cierre de sesión principal se gestiona desde index.php con
 * ?vista=logout. Este archivo es un acceso directo adicional que realiza
 * la misma operación de forma independiente.
 *
 * Flujo:
 *  1. Inicia la sesión (necesario para poder destruirla).
 *  2. Elimina todas las variables de sesión con session_unset().
 *  3. Destruye la sesión del servidor con session_destroy().
 *  4. Redirige al inicio (login).
 */
session_start();
session_unset();    // Limpia todas las variables de sesión ($_SESSION)
session_destroy();  // Elimina el fichero de sesión en el servidor
header('Location: /asignaciones/');
exit();
?>
