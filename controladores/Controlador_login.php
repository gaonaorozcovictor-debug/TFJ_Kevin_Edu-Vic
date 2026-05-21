<?php
/**
 * =============================================================================
 * CONTROLADOR DE LOGIN - controladores/Controlador_login.php
 * =============================================================================
 * Gestiona la autenticación de dos tipos de usuarios:
 *
 *  1. Administrador: se autentica con usuario + contraseña (tabla 'usuarios').
 *  2. Profesor: se autentica seleccionando su nombre del desplegable y
 *     opcionalmente introduciendo contraseña (si ya la tiene configurada).
 *
 * Este controlador es invocado directamente (no como clase) desde el formulario
 * de login situado en vistas/login.php.
 */
session_start();

require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_usuarios.php';
require_once __DIR__ . '/../modelos/Modelo_profesores.php';

// Definimos BASE_URL si aún no está definida (por si se accede directamente)
if (!defined('BASE_URL')) define('BASE_URL', '/asignaciones');

// ── Login administrador ───────────────────────────────────────────────────────
// El formulario de admin envía el campo oculto 'login_admin'
if (isset($_POST['login_admin'])) {
    $usuario  = trim($_POST['usuario']  ?? '');
    $password = trim($_POST['password'] ?? '');

    $modelo = new Modelo_usuarios();
    $user   = $modelo->obtenerPorUsuario($usuario);

    // password_verify() compara el texto plano con el hash bcrypt almacenado en BD.
    // Nunca almacenamos contraseñas en texto plano.
    if ($user && password_verify($password, $user['password'])) {
        // Credenciales correctas: guardamos los datos del usuario en sesión
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['rol']     = $user['rol'];        // 'admin'
        $_SESSION['nombre']  = 'Administrador';
        header('Location: ' . BASE_URL . '/?vista=admin');
    } else {
        // Credenciales incorrectas: guardamos el error en sesión para mostrarlo en el login
        $_SESSION['error'] = 'Credenciales incorrectas.';
        header('Location: ' . BASE_URL . '/');
    }
    exit();
}

// ── Login profesor ────────────────────────────────────────────────────────────
// El formulario de profesor envía el campo oculto 'login_profesor'
if (isset($_POST['login_profesor'])) {
    $profesor_id = (int)($_POST['profesor_id'] ?? 0);
    $password    = $_POST['password_profesor'] ?? '';

    // Validación básica: debe seleccionarse un profesor del desplegable
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

    // Comprobamos si este profesor ya tiene un registro en la tabla 'usuarios'
    // (podría no existir si nunca ha iniciado sesión ni configurado contraseña)
    $userProfesor = $modeloUsuarios->obtenerPorProfesorId($profesor_id);

    if ($userProfesor && !empty($userProfesor['password'])) {
        // ── Caso A: El profesor YA tiene contraseña configurada ──
        // Verificamos que la contraseña introducida sea correcta
        if (empty($password) || !password_verify($password, $userProfesor['password'])) {
            $_SESSION['error'] = 'Contraseña incorrecta.';
            header('Location: ' . BASE_URL . '/');
            exit();
        }
    } else {
        // ── Caso B: El profesor NO tiene contraseña todavía ──
        // Acceso libre, pero nos aseguramos de que exista su fila en 'usuarios'
        // para que pueda establecer contraseña más adelante desde su panel
        if (!$userProfesor) {
            // Generamos un nombre de usuario único basado en el ID del profesor
            $nombreUsuario = 'prof_' . $profesor_id;
            $modeloUsuarios->crearUsuarioProfesor($profesor_id, $nombreUsuario);
        }
    }

    // Autenticación exitosa: guardamos todos los datos relevantes en sesión
    $_SESSION['usuario']     = $profesor_id;
    $_SESSION['rol']         = 'profesor';
    $_SESSION['profesor_id'] = $profesor_id;        // ID para consultas de módulos
    $_SESSION['nombre']      = $profesor['nombre'];  // Nombre para mostrar en el panel
    $_SESSION['categoria']   = $profesor['categoria'] ?? '';  // PS o PT

    header('Location: ' . BASE_URL . '/?vista=profesor');
    exit();
}

// ── Acceso directo sin POST → redirigir al inicio ─────────────────────────────
// Si alguien accede a este archivo directamente sin un formulario POST, lo devolvemos al inicio
header('Location: ' . BASE_URL . '/');
exit();
