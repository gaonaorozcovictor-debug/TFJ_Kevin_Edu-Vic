<?php
session_start();

// Cabeceras anti-caché
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Base URL del proyecto (se usa en vistas para construir enlaces y rutas JS)
define('BASE_URL', '/asignaciones');

require_once __DIR__ . '/modelos/Modelo_profesores.php';
require_once __DIR__ . '/modelos/Modelo_modulos.php';
require_once __DIR__ . '/modelos/Modelo_excel.php';

// Sin sesión → mostrar login
if (!isset($_SESSION['usuario'])) {
    _mostrarLogin();
    exit();
}

$vista = $_GET['vista'] ?? '';
$rol   = $_SESSION['rol'];

// Acciones POST del admin (subir ficheros, etc.)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/controladores/Controlador_modulo.php';
    (new Controlador_modulos())->manejarPost();
    exit();
}

switch ($vista) {

    case 'admin':
        _requiereRol('admin');
        $modeloMod  = new Modelo_modulos();
        $modeloProf = new Modelo_profesores();
        $modulos            = $modeloMod->obtenerModulosConProfesor();
        $profesores         = $modeloProf->obtenerProfesores();
        $profesoresConHoras = $modeloMod->obtenerTodosProfesoresConHoras();
        require __DIR__ . '/vistas/adminPanel.php';
        break;

    case 'asignacion':
        _requiereRol('admin');
        $modeloMod  = new Modelo_modulos();
        $modeloProf = new Modelo_profesores();
        $modulos            = $modeloMod->obtenerModulosConProfesor();
        $profesores         = $modeloProf->obtenerProfesores();
        $profesoresConHoras = $modeloMod->obtenerTodosProfesoresConHoras();
        require __DIR__ . '/vistas/asignarModulos.php';
        break;

    case 'profesor':
        _requiereRol('profesor');
        $modeloMod = new Modelo_modulos();
        $modulos   = $modeloMod->obtenerModulosPorProfesor((int)$_SESSION['profesor_id']);
        require __DIR__ . '/vistas/profesorPanel.php';
        break;

    case 'logout':
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        break;

    default:
        // Redirigir al panel según rol
        header('Location: ' . BASE_URL . '/?vista=' . ($rol === 'admin' ? 'admin' : 'profesor'));
        break;
}

// ── Helpers ──────────────────────────────────────────────────────────────────

function _mostrarLogin(): void {
    $modeloProf = new Modelo_profesores();
    $profesores = $modeloProf->obtenerProfesores();
    require __DIR__ . '/vistas/login.php';
}

function _requiereRol(string $rolNecesario): void {
    if ($_SESSION['rol'] !== $rolNecesario) {
        $destino = $_SESSION['rol'] === 'admin' ? 'admin' : 'profesor';
        header('Location: ' . BASE_URL . '/?vista=' . $destino);
        exit();
    }
}
