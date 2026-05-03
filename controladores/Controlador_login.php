<?php
session_start();

require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_profesores.php';

define('BASE_URL', '/asignaciones');

// Login administrador
if (isset($_POST['login_admin'])) {
    $usuario  = trim($_POST['usuario']  ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($usuario === 'admin' && $password === '1234') {
        $_SESSION['usuario'] = 'admin';
        $_SESSION['rol']     = 'admin';
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

    $_SESSION['usuario']    = $profesor_id;
    $_SESSION['rol']        = 'profesor';
    $_SESSION['profesor_id']= $profesor_id;
    $_SESSION['nombre']     = $profesor['nombre'];

    header('Location: ' . BASE_URL . '/?vista=profesor');
    exit();
}

// Acceso directo sin POST → redirigir al inicio
header('Location: ' . BASE_URL . '/');
exit();
