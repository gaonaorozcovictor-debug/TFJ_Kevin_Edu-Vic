<?php
/**
 * =============================================================================
 * CONTROLADOR CAMBIO DE CONTRASEÑA (ADMIN) - controladores/Controlador_cambiarPassword.php
 * =============================================================================
 * Permite al administrador cambiar su propia contraseña de acceso.
 * Solo puede ser invocado por usuarios con rol 'admin'.
 *
 * Validaciones que realiza:
 *  1. Todos los campos son obligatorios.
 *  2. La nueva contraseña y su confirmación deben coincidir.
 *  3. La nueva contraseña debe tener al menos 4 caracteres.
 *  4. La contraseña actual introducida debe ser correcta (verificación bcrypt).
 *
 * En caso de éxito, la nueva contraseña se cifra con bcrypt (password_hash)
 * antes de guardarse en la base de datos.
 */
session_start();

if (!defined('BASE_URL')) define('BASE_URL', '/asignaciones');

// Solo administradores autenticados pueden cambiar contraseña desde este controlador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ' . BASE_URL . '/');
    exit();
}

require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_usuarios.php';

// Recogemos los valores del formulario (con valor por defecto '' si no existen)
$passwordActual = $_POST['password_actual']  ?? '';
$passwordNueva  = $_POST['password_nueva']   ?? '';
$passwordRepeat = $_POST['password_repetir'] ?? '';

// ── Validaciones de formulario ────────────────────────────────────────────────

if (empty($passwordActual) || empty($passwordNueva) || empty($passwordRepeat)) {
    $_SESSION['error_pass'] = 'Todos los campos son obligatorios.';
    header('Location: ' . BASE_URL . '/?vista=admin');
    exit();
}

// Verificamos que la nueva contraseña y su confirmación sean idénticas
if ($passwordNueva !== $passwordRepeat) {
    $_SESSION['error_pass'] = 'La nueva contraseña y su confirmación no coinciden.';
    header('Location: ' . BASE_URL . '/?vista=admin');
    exit();
}

// Mínimo de 4 caracteres para la nueva contraseña
if (strlen($passwordNueva) < 4) {
    $_SESSION['error_pass'] = 'La nueva contraseña debe tener al menos 4 caracteres.';
    header('Location: ' . BASE_URL . '/?vista=admin');
    exit();
}

try {
    $modelo = new Modelo_usuarios();
    // Recuperamos el registro completo del admin para obtener el hash actual
    $user   = $modelo->obtenerPorUsuario($_SESSION['usuario']);

    // Verificamos que la contraseña actual introducida coincide con el hash en BD
    if (!$user || !password_verify($passwordActual, $user['password'])) {
        $_SESSION['error_pass'] = 'La contraseña actual no es correcta.';
        header('Location: ' . BASE_URL . '/?vista=admin');
        exit();
    }

    // Ciframos la nueva contraseña con bcrypt (algoritmo por defecto de PHP)
    // PASSWORD_DEFAULT usa bcrypt con coste 10, que es el estándar recomendado
    $nuevoHash = password_hash($passwordNueva, PASSWORD_DEFAULT);
    $modelo->actualizarPassword($_SESSION['usuario'], $nuevoHash);

    $_SESSION['mensaje'] = '✅ Contraseña actualizada correctamente.';
    header('Location: ' . BASE_URL . '/?vista=admin');

} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['error_pass'] = 'Error interno al cambiar la contraseña.';
    header('Location: ' . BASE_URL . '/?vista=admin');
}
exit();
