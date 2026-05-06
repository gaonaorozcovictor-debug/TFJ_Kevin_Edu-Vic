<?php
// controladores/Controlador_obtenerModulosProfesor.php
session_start();
header('Content-Type: application/json');

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
    $modelo  = new Modelo_modulos();

    // Si el profesor_id es 0, obtenemos TODOS los módulos (sin filtrar por asignación)
    if ($profesor_id === 0) {
        $modulos = $modelo->obtenerModulosConProfesor();
        // Formateamos la salida para que sea consistente
        foreach ($modulos as &$mod) {
            $mod['asignado_a_profe'] = false;
            $mod['asignado_a_otro']  = $mod['profesor_id'] !== null;
            $mod['es_pspt']          = stripos($mod['nombre_modulo'], 'PS') !== false || stripos($mod['nombre_modulo'], 'PT') !== false;
            $mod['nombre_ocupante']  = $mod['profesor_nombre'] ?? 'Otro profesor';
        }
        echo json_encode(['ok' => true, 'modulos' => $modulos]);
        exit();
    }

    // --- Lógica original para un profesor específico ---
    $modulos = $modelo->obtenerModulos();
    $idsProf = array_column($modelo->obtenerModulosPorProfesor($profesor_id), 'id');

    foreach ($modulos as &$mod) {
        $mod['asignado_a_profe'] = in_array($mod['id'], $idsProf);
        $mod['asignado_a_otro']  = $mod['profesor_id'] !== null && (int)$mod['profesor_id'] !== $profesor_id;
        $mod['es_pspt']          = stripos($mod['nombre_modulo'], 'PS') !== false || stripos($mod['nombre_modulo'], 'PT') !== false;
    }

    echo json_encode(['ok' => true, 'modulos' => $modulos]);

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['ok' => false, 'mensaje' => 'Error interno']);
}
?>