<?php
session_start();

require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_usuarios.php';
require_once __DIR__ . '/../modelos/Modelo_profesores.php';

if (!defined('BASE_URL')) define('BASE_URL', '/asignaciones');

// Login administrador
if (isset($_POST['login_admin'])) {
    $usuario  = trim($_POST['usuario']  ?? '');
    $password = trim($_POST['password'] ?? '');

    $modelo = new Modelo_usuarios();
    $user   = $modelo->obtenerPorUsuario($usuario);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['rol']     = $user['rol'];
        $_SESSION['nombre']  = 'Administrador';
        header('Location: ' . BASE_URL . '/?vista=admin');
    } else {
        $_SESSION['error'] = 'Credenciales incorrectas.';
        header('Location: ' . BASE_URL . '/');
    }
    exit();
}

// Login profesor
if (isset($_POST['login_profesor'])) {
    $profesor_id = (int)($_POST['profesor_id'] ?? 0);

    if ($profesor_id <= 0) {
        $_SESSION['error'] = 'Selecciona un profesor.';
        header('Location: ' . BASE_URL . '/');
        exit();
    }

    $modelo   = new Modelo_profesores();
    $profesor = $modelo->obtenerProfesorPorId($profesor_id);

    if (!$profesor) {
        $_SESSION['error'] = 'Profesor no encontrado.';
        header('Location: ' . BASE_URL . '/');
        exit();
    }

    $_SESSION['usuario']     = $profesor_id;
    $_SESSION['rol']         = 'profesor';
    $_SESSION['profesor_id'] = $profesor_id;
    $_SESSION['nombre']      = $profesor['nombre'];
    $_SESSION['categoria']   = $profesor['categoria'] ?? '';

    header('Location: ' . BASE_URL . '/?vista=profesor');
    exit();
}

// Acceso directo sin POST → redirigir al inicio
header('Location: ' . BASE_URL . '/');
exit();