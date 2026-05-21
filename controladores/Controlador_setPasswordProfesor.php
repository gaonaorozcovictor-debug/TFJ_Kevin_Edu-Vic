<?php
/**
 * =============================================================================
 * CONTROLADOR ESTABLECER/CAMBIAR CONTRASEÑA (PROFESOR)
 * controladores/Controlador_setPasswordProfesor.php
 * =============================================================================
 * Permite a un profesor establecer su contraseña por primera vez o cambiarla
 * si ya la tenía configurada. Se accede desde el panel del profesor.
 *
 * Validaciones que realiza:
 *  1. Los campos 'password_nueva' y 'password_repetir' son obligatorios.
 *  2. Ambas contraseñas deben coincidir.
 *  3. La contraseña debe tener al menos 4 caracteres.
 *  4. Si el profesor YA tenía contraseña, debe introducir la contraseña actual
 *     correctamente para poder cambiarla (campo 'password_actual').
 *
 * Acceso: Solo profesores autenticados (rol 'profesor').
 * Si la validación falla, guarda el error en sesión y activa un flag
 * para que la vista vuelva a abrir el modal del formulario automáticamente.
 */
session_start();

if (!defined('BASE_URL')) define('BASE_URL', '/asignaciones');

// Solo profesores autenticados pueden acceder a esta acción
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    header('Location: ' . BASE_URL . '/');
    exit();
}

require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_usuarios.php';

// Obtenemos el ID del profesor desde la sesión (no del formulario, por seguridad)
$profesorId     = (int)$_SESSION['profesor_id'];
$passwordNueva  = $_POST['password_nueva']   ?? '';
$passwordRepeat = $_POST['password_repetir'] ?? '';

// ── Validaciones ──────────────────────────────────────────────────────────────

if (empty($passwordNueva) || empty($passwordRepeat)) {
    $_SESSION['error_pass'] = 'Los dos campos de contraseña son obligatorios.';
    $_SESSION['_open_modal_password'] = true; // Flag para reabrir el modal en la vista
    header('Location: ' . BASE_URL . '/?vista=profesor');
    exit();
}

if ($passwordNueva !== $passwordRepeat) {
    $_SESSION['error_pass'] = 'Las contraseñas no coinciden.';
    $_SESSION['_open_modal_password'] = true;
    header('Location: ' . BASE_URL . '/?vista=profesor');
    exit();
}

if (strlen($passwordNueva) < 4) {
    $_SESSION['error_pass'] = 'La contraseña debe tener al menos 4 caracteres.';
    $_SESSION['_open_modal_password'] = true;
    header('Location: ' . BASE_URL . '/?vista=profesor');
    exit();
}

$modelo     = new Modelo_usuarios();
$userActual = $modelo->obtenerPorProfesorId($profesorId);

// ── Verificación de contraseña actual (si ya la tenía) ────────────────────────
// Si el profesor ya tenía contraseña configurada, exigimos que introduzca
// la actual antes de poder cambiarla (previene cambios no autorizados)
if ($userActual && !empty($userActual['password'])) {
    $passwordActual = $_POST['password_actual'] ?? '';
    if (empty($passwordActual) || !password_verify($passwordActual, $userActual['password'])) {
        $_SESSION['error_pass'] = 'La contraseña actual introducida no es correcta.';
        $_SESSION['_open_modal_password'] = true;
        header('Location: ' . BASE_URL . '/?vista=profesor');
        exit();
    }
}

// ── Guardado de la nueva contraseña ──────────────────────────────────────────
try {
    // Ciframos con bcrypt antes de guardar
    $hash = password_hash($passwordNueva, PASSWORD_DEFAULT);
    $ok   = $modelo->establecerPasswordProfesor($profesorId, $hash);

    if ($ok) {
        $_SESSION['mensaje'] = '✅ Contraseña guardada correctamente. Se te pedirá la próxima vez que accedas.';
    } else {
        // Caso raro: el usuario no tenía fila en la tabla 'usuarios'
        $_SESSION['error_pass'] = 'No se pudo guardar la contraseña. Contacta con el administrador.';
        $_SESSION['_open_modal_password'] = true;
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['error_pass'] = 'Error interno al guardar la contraseña.';
    $_SESSION['_open_modal_password'] = true;
}

header('Location: ' . BASE_URL . '/?vista=profesor');
exit();
