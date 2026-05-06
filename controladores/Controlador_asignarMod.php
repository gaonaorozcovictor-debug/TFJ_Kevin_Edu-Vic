<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'mensaje' => 'No autorizado']);
    exit();
}

require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_modulos.php';

$datos = json_decode(file_get_contents('php://input'), true);

if (!$datos || !isset($datos['asignaciones'], $datos['profesor_id'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Datos incorrectos']);
    exit();
}

$profesor_id  = (int)$datos['profesor_id'];
$idsEnviados  = array_map(fn($a) => (int)$a['modulo_id'], $datos['asignaciones']);

try {
    $modelo = new Modelo_modulos();

    $todosModulos = $modelo->obtenerModulos();

    // IDs ya asignados a OTROS profesores (no se pueden reasignar)
    $bloqueados = array_column(
        array_filter($todosModulos, fn($m) => $m['profesor_id'] !== null && (int)$m['profesor_id'] !== $profesor_id),
        'id'
    );

    $idsValidos = array_values(array_filter($idsEnviados, fn($id) => !in_array($id, $bloqueados)));

    // Calcular horas totales
    $horasMap     = array_column($todosModulos, 'horas', 'id');
    $horasTotales = array_sum(array_map(fn($id) => (int)($horasMap[$id] ?? 0), $idsValidos));

    // Actualizar BD
    foreach ($todosModulos as $mod) {
        $enLista = in_array($mod['id'], $idsValidos);
        $esMio   = (int)$mod['profesor_id'] === $profesor_id;

        if ($enLista && !$esMio) {
            $modelo->asignarProfesor($mod['id'], $profesor_id);
        } elseif (!$enLista && $esMio) {
            $modelo->asignarProfesor($mod['id'], null);
        }
    }

    $supera20 = $horasTotales > 20;

    echo json_encode([
        'ok'           => true,
        'mensaje'      => $supera20
            ? "⚠️ Guardado. El profesor supera las 20 horas ({$horasTotales}h)."
            : 'Módulos actualizados correctamente.',
        'supera_20'    => $supera20,
        'horas_totales'=> $horasTotales,
    ]);

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['ok' => false, 'mensaje' => 'Error interno: ' . $e->getMessage()]);
}
