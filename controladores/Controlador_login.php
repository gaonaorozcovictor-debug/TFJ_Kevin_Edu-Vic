<?php
session_start();

require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_usuarios.php';
require_once __DIR__ . '/../modelos/Modelo_profesores.php';

if (!defined('BASE_URL')) define('BASE_URL', '/asignaciones');

// ── Login administrador ───────────────────────────────────────────────────────
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

// ── Login profesor ────────────────────────────────────────────────────────────
if (isset($_POST['login_profesor'])) {
    $profesor_id = (int)($_POST['profesor_id'] ?? 0);
    $password    = $_POST['password_profesor'] ?? '';

    if ($profesor_id <= 0) {
        $_SESSION['error'] = 'Selecciona un profesor.';
        header('Location: ' . BASE_URL . '/');
        exit();
    }

    $modeloProf = new Modelo_profesores();
    $profesor   = $modeloProf->obtenerProfesorPorId($profesor_id);

    if (!$profesor) {
        $_SESSION['error'] = 'Profesor no encontrado.';
        header('Location: ' . BASE_URL . '/');
        exit();
    }

    $modeloUsuarios = new Modelo_usuarios();

    // Asegurarse de que existe fila en usuarios para este profesor
    // (puede no existir si nunca ha establecido contraseña)
    $userProfesor = $modeloUsuarios->obtenerPorProfesorId($profesor_id);

    if ($userProfesor && !empty($userProfesor['password'])) {
        // Este profesor ya tiene contraseña → verificar
        if (empty($password) || !password_verify($password, $userProfesor['password'])) {
            $_SESSION['error'] = 'Contraseña incorrecta.';
            header('Location: ' . BASE_URL . '/');
            exit();
        }
    } else {
        // Sin contraseña establecida → acceso libre, pero creamos fila si no existe
        if (!$userProfesor) {
            // Generar nombre de usuario único basado en el nombre del profesor
            $nombreUsuario = 'prof_' . $profesor_id;
            $modeloUsuarios->crearUsuarioProfesor($profesor_id, $nombreUsuario);
        }
    }

    $_SESSION['usuario']     = $profesor_id;
    $_SESSION['rol']         = 'profesor';
    $_SESSION['profesor_id'] = $profesor_id;
    $_SESSION['nombre']      = $profesor['nombre'];
    $_SESSION['categoria']   = $profesor['categoria'] ?? '';

    header('Location: ' . BASE_URL . '/?vista=profesor');
    exit();
}

// ── Acceso directo sin POST → redirigir al inicio ─────────────────────────────
header('Location: ' . BASE_URL . '/');
exit();
