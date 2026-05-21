<?php
/**
 * =============================================================================
 * UTILIDAD: GENERADOR DE HASH - hash.php
 * =============================================================================
 * Script auxiliar de desarrollo/mantenimiento.
 * Genera un hash bcrypt para la contraseña '1234' y lo muestra en pantalla.
 *
 * ¿Para qué sirve?
 * Si necesitas restablecer manualmente la contraseña del admin en la BD,
 * puedes ejecutar este script, copiar el hash generado e insertarlo
 * directamente en la tabla 'usuarios' con una consulta SQL:
 *   UPDATE usuarios SET password = '<hash>' WHERE usuario = 'admin';
 *
 * ⚠️ AVISO DE SEGURIDAD: Este archivo NO debería estar accesible en producción.
 *    Úsalo solo durante desarrollo y elimínalo o protégelo después.
 *
 * Nota: Cada ejecución genera un hash diferente (por el salt aleatorio de bcrypt),
 * pero todos son válidos para verificar '1234' con password_verify().
 */
$hash = password_hash('1234', PASSWORD_DEFAULT);
echo $hash;
echo '<br>Length: ' . strlen($hash);
?>
