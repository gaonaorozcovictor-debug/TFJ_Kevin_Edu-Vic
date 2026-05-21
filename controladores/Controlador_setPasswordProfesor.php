<?php
session_start();

if (!defined('BASE_URL')) define('BASE_URL', '/asignaciones');

// Solo profesores autenticados
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    header('Location: ' . BASE_URL . '/');
    exit();
}

require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_usuarios.php';

$profesorId     = (int)$_SESSION['profesor_id'];
$passwordNueva  = $_POST['password_nueva']   ?? '';
$passwordRepeat = $_POST['password_repetir'] ?? '';

// Validaciones
if (empty($passwordNueva) || empty($passwordRepeat)) {
    $_SESSION['error_pass'] = 'Los dos campos de contraseña son obligatorios.';
$_SESSION['_open_modal_password'] = true;
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

// Si ya tenía contraseña, exigir la actual
if ($userActual && !empty($userActual['password'])) {
    $passwordActual = $_POST['password_actual'] ?? '';
    if (empty($passwordActual) || !password_verify($passwordActual, $userActual['password'])) {
        $_SESSION['error_pass'] = 'La contraseña actual introducida no es correcta.';
    $_SESSION['_open_modal_password'] = true;
        header('Location: ' . BASE_URL . '/?vista=profesor');
        exit();
    }
}

try {
    $hash = password_hash($passwordNueva, PASSWORD_DEFAULT);
    $ok   = $modelo->establecerPasswordProfesor($profesorId, $hash);

    if ($ok) {
        $_SESSION['mensaje'] = '✅ Contraseña guardada correctamente. Se te pedirá la próxima vez que accedas.';
    } else {
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
