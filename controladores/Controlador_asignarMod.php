<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'mensaje' => 'No autorizado']);
    exit();
}

require_once __DIR__ . '/../modelos/Modelo_modulos.php';
header('Content-Type: application/json');

$body = file_get_contents('php://input');
$datos = json_decode($body, true);

if (!$datos || !isset($datos['asignaciones']) || !isset($datos['profesor_id'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Datos incorrectos']);
    exit();
}

$asignaciones = $datos['asignaciones'];
$profesor_id = (int)$datos['profesor_id'];

try {
    $modelo = new Modelo_modulos();

    // 🔹 Obtener módulos actuales del profesor
    $modulosActuales = $modelo->obtenerModulosPorProfesor($profesor_id);
    $idsActuales = array_column($modulosActuales, 'id');

    // 🔹 IDs enviados desde el frontend
    $idsEnviados = array_map(fn($a) => (int)$a['modulo_id'], $asignaciones);

    // 🔹 Obtener todos los módulos para calcular horas y ver asignaciones
    $todosModulos = $modelo->obtenerModulos();
    $modulosHoras = [];
    $modulosAsignadosOtros = [];
    foreach ($todosModulos as $mod) {
        $modulosHoras[$mod['id']] = (int)$mod['horas'];
        if ($mod['profesor_id'] !== null && $mod['profesor_id'] != $profesor_id) {
            $modulosAsignadosOtros[] = $mod['id'];
        }
    }

    // 🔹 Filtrar módulos enviados que no pertenecen a otros profesores
    $idsValidos = array_filter($idsEnviados, fn($id) => !in_array($id, $modulosAsignadosOtros));

    // 🔹 Determinar módulos finales que el profesor tendrá
    $idsFinales = array_unique(array_merge(
        array_diff($idsActuales, array_diff($idsActuales, $idsValidos)),
        $idsValidos
    ));

    // 🔹 Calcular horas totales
    $horasTotales = 0;
    foreach ($idsFinales as $id) {
        $horasTotales += $modulosHoras[$id] ?? 0;
    }

    // ✅ CAMBIO: Solo avisar, NO bloquear
    $supera20 = $horasTotales > 20;
    $mensajeAdvertencia = $supera20 ? "⚠️ AVISO: El profesor supera las 20 horas ({$horasTotales}h). Se ha guardado igualmente." : null;

    // 🔹 Actualizar asignaciones en la base de datos (SIEMPRE se guarda)
    foreach ($todosModulos as $mod) {
        $modId = $mod['id'];
        if (in_array($modId, $idsFinales)) {
            if ($mod['profesor_id'] != $profesor_id) {
                $modelo->asignarProfesor($modId, $profesor_id);
            }
        } else {
            if ($mod['profesor_id'] == $profesor_id) {
                $modelo->asignarProfesor($modId, null);
            }
        }
    }

    echo json_encode([
        'ok' => true, 
        'mensaje' => $mensajeAdvertencia ?? 'Módulos actualizados correctamente',
        'supera_20' => $supera20,
        'horas_totales' => $horasTotales
    ]);

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['ok' => false, 'mensaje' => 'Error interno: ' . $e->getMessage()]);
}
?>