<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'mensaje' => 'No autorizado']);
    exit();
}

require_once __DIR__ . '/../modelos/Modelo_modulos.php';
header('Content-Type: application/json');

$profesor_id = isset($_GET['profesor_id']) ? (int)$_GET['profesor_id'] : 0;
if ($profesor_id <= 0) {
    echo json_encode(['ok' => false, 'mensaje' => 'Profesor no válido']);
    exit();
}

try {
    $modelo = new Modelo_modulos();

    $modulos = $modelo->obtenerModulos();
    $modulosProfesor = $modelo->obtenerModulosPorProfesor($profesor_id);
    $idsProfesor = array_column($modulosProfesor, 'id');

    foreach ($modulos as &$mod) {
        $mod['asignado_a_profe'] = in_array($mod['id'], $idsProfesor);
        $mod['asignado_a_otro'] = $mod['profesor_id'] !== null && $mod['profesor_id'] != $profesor_id;
        $mod['es_pspt'] = stripos($mod['nombre_modulo'], 'PS') !== false || stripos($mod['nombre_modulo'], 'PT') !== false;
    }

    echo json_encode(['ok' => true, 'modulos' => $modulos]);

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['ok' => false, 'mensaje' => 'Error interno']);
}