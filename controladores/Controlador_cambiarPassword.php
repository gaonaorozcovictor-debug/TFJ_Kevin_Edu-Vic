<?php
session_start();

if (!defined('BASE_URL')) define('BASE_URL', '/asignaciones');

// Solo el admin puede cambiar la contraseña
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ' . BASE_URL . '/');
    exit();
}

require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_usuarios.php';

$passwordActual = $_POST['password_actual']  ?? '';
$passwordNueva  = $_POST['password_nueva']   ?? '';
$passwordRepeat = $_POST['password_repetir'] ?? '';

// Validaciones
if (empty($passwordActual) || empty($passwordNueva) || empty($passwordRepeat)) {
    $_SESSION['error_pass'] = 'Todos los campos son obligatorios.';
    header('Location: ' . BASE_URL . '/?vista=admin');
    exit();
}

if ($passwordNueva !== $passwordRepeat) {
    $_SESSION['error_pass'] = 'La nueva contraseña y su confirmación no coinciden.';
    header('Location: ' . BASE_URL . '/?vista=admin');
    exit();
}

if (strlen($passwordNueva) < 4) {
    $_SESSION['error_pass'] = 'La nueva contraseña debe tener al menos 4 caracteres.';
    header('Location: ' . BASE_URL . '/?vista=admin');
    exit();
}

try {
    $modelo = new Modelo_usuarios();
    $user   = $modelo->obtenerPorUsuario($_SESSION['usuario']);

    if (!$user || !password_verify($passwordActual, $user['password'])) {
        $_SESSION['error_pass'] = 'La contraseña actual no es correcta.';
        header('Location: ' . BASE_URL . '/?vista=admin');
        exit();
    }

    // Cifrar la nueva contraseña y guardarla
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
