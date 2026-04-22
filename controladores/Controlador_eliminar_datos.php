<?php
session_start();
define('BASE_URL', '/asignaciones');

// Verificación de seguridad básica
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ' . BASE_URL . '/');
    exit();
}

require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_modulos.php';
require_once __DIR__ . '/../modelos/Modelo_profesores.php';

// Capturamos el tipo
$tipo = $_GET['tipo'] ?? null;

try {
    if ($tipo === 'profesores') {
        eliminarSoloProfesores();
    } 
    elseif ($tipo === 'modulos') {
        eliminarSoloModulos();
    } 
    else {
        $_SESSION['error'] = 'No se seleccionó una opción válida.';
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'Error crítico al eliminar.';
}

// Redirigir siempre al final
header('Location: ' . BASE_URL . '/?vista=admin');
exit();

// ── FUNCIONES DE ELIMINACIÓN ──────────────────────────────────────────

function eliminarSoloProfesores() {
    $modelo = new Modelo_profesores();
    $modelo->eliminarTodos(); 
    $_SESSION['mensaje'] = 'Se han eliminado los profesores. Los módulos ahora no tienen asignación.';
}

function eliminarSoloModulos() {
    $modelo = new Modelo_modulos();
    $modelo->eliminarTodos();
    $_SESSION['mensaje'] = 'Se han eliminado todos los módulos correctamente.';
}