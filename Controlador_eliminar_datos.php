<?php
/**
 * =============================================================================
 * CONTROLADOR DE ELIMINACIÓN DE DATOS - controladores/Controlador_eliminar_datos.php
 * =============================================================================
 * Permite al administrador eliminar masivamente profesores o módulos de la BD.
 * Recibe el tipo de eliminación a través del parámetro GET 'tipo'.
 *
 * Valores válidos de 'tipo':
 *  - 'profesores': elimina todos los profesores y pone a NULL el profesor_id
 *                  de todos los módulos (quedan "sin asignar" pero no se borran).
 *  - 'modulos':    elimina todos los módulos (los profesores no se ven afectados).
 *
 * ⚠️ OPERACIÓN DESTRUCTIVA: No hay confirmación adicional en el servidor.
 *    La confirmación debe hacerse en el lado del cliente (vista).
 *
 * Acceso: Solo administradores autenticados.
 */
session_start();
if (!defined('BASE_URL')) define('BASE_URL', '/asignaciones');

// Verificación de seguridad: solo el admin puede borrar datos
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ' . BASE_URL . '/');
    exit();
}

require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_modulos.php';
require_once __DIR__ . '/../modelos/Modelo_profesores.php';

// Leemos el tipo de eliminación solicitado
$tipo = $_GET['tipo'] ?? null;

try {
    if ($tipo === 'profesores') {
        eliminarSoloProfesores();
    } elseif ($tipo === 'modulos') {
        eliminarSoloModulos();
    } else {
        // Tipo no reconocido: informamos al usuario
        $_SESSION['error'] = 'No se seleccionó una opción válida.';
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'Error crítico al eliminar.';
}

// Siempre redirigimos al panel de admin al terminar
header('Location: ' . BASE_URL . '/?vista=admin');
exit();

// ── Funciones de eliminación ──────────────────────────────────────────────────

/**
 * Elimina todos los profesores de la base de datos.
 *
 * Pasos que realiza el modelo internamente:
 *  1. Pone a NULL el profesor_id de todos los módulos (preserva los módulos).
 *  2. Desactiva las claves foráneas temporalmente para poder hacer el DELETE.
 *  3. Elimina todos los registros de la tabla 'profesores'.
 *  4. Reactiva las claves foráneas.
 */
function eliminarSoloProfesores() {
    $modelo = new Modelo_profesores();
    $modelo->eliminarTodos();
    $_SESSION['mensaje'] = 'Se han eliminado los profesores. Los módulos ahora no tienen asignación.';
}

/**
 * Elimina todos los módulos de la base de datos.
 *
 * Los profesores no se ven afectados. Los módulos son los "hijos"
 * en la relación con profesores, por lo que se pueden borrar directamente.
 */
function eliminarSoloModulos() {
    $modelo = new Modelo_modulos();
    $modelo->eliminarTodos();
    $_SESSION['mensaje'] = 'Se han eliminado todos los módulos correctamente.';
}
