<?php
/**
 * =============================================================================
 * ENDPOINT AJAX - OBTENER MÓDULOS POR PROFESOR
 * controladores/Controlador_obtenerModulosProfesor.php
 * =============================================================================
 * Devuelve en formato JSON la lista completa de módulos con información
 * de asignación para un profesor concreto. Se usa en la vista de asignación
 * para cargar dinámicamente los módulos al seleccionar un profesor.
 *
 * Método:  GET
 * Params:  profesor_id (int)
 *           - Si profesor_id = 0: devuelve TODOS los módulos (vista general)
 *           - Si profesor_id > 0: devuelve módulos con estado de asignación
 *                                 relativo a ese profesor
 *
 * Acceso:  Solo administradores autenticados.
 *
 * Cada módulo en la respuesta incluye campos extra calculados:
 *  - asignado_a_profe (bool): ¿Está asignado al profesor solicitado?
 *  - asignado_a_otro  (bool): ¿Está asignado a un profesor diferente?
 *  - es_pspt          (bool): ¿El nombre contiene 'PS' o 'PT'? (tipo de módulo)
 *  - nombre_ocupante  (str):  Nombre del profesor que lo tiene asignado (si aplica)
 */
session_start();
header('Content-Type: application/json');

// Solo admins pueden consultar este endpoint
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'mensaje' => 'No autorizado']);
    exit();
}

require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_modulos.php';

$profesor_id = (int)($_GET['profesor_id'] ?? 0);

if ($profesor_id < 0) {
    echo json_encode(['ok' => false, 'mensaje' => 'Profesor no válido']);
    exit();
}

try {
    $modelo = new Modelo_modulos();

    // ── Caso especial: profesor_id = 0 → vista global de todos los módulos ──
    if ($profesor_id === 0) {
        $modulos = $modelo->obtenerModulosConProfesor(); // Incluye JOIN con nombre del profesor

        // Añadimos campos calculados para la vista
        foreach ($modulos as &$mod) {
            $mod['asignado_a_profe'] = false; // No hay profesor seleccionado
            $mod['asignado_a_otro']  = $mod['profesor_id'] !== null; // Está asignado a alguien
            // Detectamos módulos de tipo PS (Profesor Especialista) o PT (Profesor Técnico)
            $mod['es_pspt']          = stripos($mod['nombre_modulo'], 'PS') !== false
                                    || stripos($mod['nombre_modulo'], 'PT') !== false;
            $mod['nombre_ocupante']  = $mod['profesor_nombre'] ?? 'Otro profesor';
        }
        echo json_encode(['ok' => true, 'modulos' => $modulos]);
        exit();
    }

    // ── Caso normal: profesor_id > 0 → módulos con estado relativo a ese profesor ──
    $modulos = $modelo->obtenerModulos();

    // Obtenemos los IDs de módulos ya asignados a este profesor (para la comparación)
    $idsProf = array_column($modelo->obtenerModulosPorProfesor($profesor_id), 'id');

    // Añadimos campos calculados para cada módulo
    foreach ($modulos as &$mod) {
        // ¿Este módulo está asignado al profesor solicitado?
        $mod['asignado_a_profe'] = in_array($mod['id'], $idsProf);
        // ¿Está asignado a un profesor diferente? (bloqueado para reasignación directa)
        $mod['asignado_a_otro']  = $mod['profesor_id'] !== null
                                && (int)$mod['profesor_id'] !== $profesor_id;
        // Flag para distinguir módulos PS/PT en la interfaz (pueden tener restricciones)
        $mod['es_pspt']          = stripos($mod['nombre_modulo'], 'PS') !== false
                                || stripos($mod['nombre_modulo'], 'PT') !== false;
    }

    echo json_encode(['ok' => true, 'modulos' => $modulos]);

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['ok' => false, 'mensaje' => 'Error interno']);
}
?>
