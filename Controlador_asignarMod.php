<?php
/**
 * =============================================================================
 * CONTROLADOR DE ASIGNACIÓN DE MÓDULOS - controladores/Controlador_asignarMod.php
 * =============================================================================
 * Endpoint AJAX (JSON) que gestiona la asignación/desasignación de módulos
 * a un profesor concreto desde la vista de asignación del administrador.
 *
 * Método: POST (cuerpo JSON)
 * Acceso: Solo administradores autenticados.
 *
 * Payload esperado (JSON):
 * {
 *   "profesor_id": 5,
 *   "asignaciones": [
 *     { "modulo_id": 12 },
 *     { "modulo_id": 15 }
 *   ]
 * }
 *
 * Respuesta (JSON):
 * {
 *   "ok": true,
 *   "mensaje": "...",
 *   "supera_20": false,
 *   "horas_totales": 18
 * }
 *
 * Lógica de negocio importante:
 *  - Un módulo ya asignado a OTRO profesor no puede ser reasignado.
 *  - Si el total de horas supera 20, se advierte pero se guarda igualmente.
 *  - Los módulos que ya tenía el profesor y no están en la nueva lista
 *    se desasignan automáticamente (professor_id = NULL).
 */
session_start();
header('Content-Type: application/json'); // Indicamos que la respuesta es JSON

// ── Verificación de autorización ──────────────────────────────────────────────
// Solo admins autenticados pueden llamar a este endpoint
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'mensaje' => 'No autorizado']);
    exit();
}

require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_modulos.php';

// ── Lectura del cuerpo JSON ───────────────────────────────────────────────────
// Los datos llegan como JSON en el cuerpo de la petición (no como formulario)
$datos = json_decode(file_get_contents('php://input'), true);

if (!$datos || !isset($datos['asignaciones'], $datos['profesor_id'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Datos incorrectos']);
    exit();
}

$profesor_id = (int)$datos['profesor_id'];
// Extraemos solo los IDs de módulo del array de asignaciones recibido
$idsEnviados = array_map(fn($a) => (int)$a['modulo_id'], $datos['asignaciones']);

try {
    $modelo = new Modelo_modulos();

    // Obtenemos todos los módulos de la BD para poder hacer la lógica de comparación
    $todosModulos = $modelo->obtenerModulos();

    // ── Filtro de módulos bloqueados ──────────────────────────────────────────
    // Un módulo está "bloqueado" si ya está asignado a un profesor DIFERENTE.
    // Estos módulos no se pueden reasignar sin desasignarlos primero.
    $bloqueados = array_column(
        array_filter($todosModulos, fn($m) => $m['profesor_id'] !== null && (int)$m['profesor_id'] !== $profesor_id),
        'id'
    );

    // Filtramos los IDs enviados para quedarnos solo con los que no están bloqueados
    $idsValidos = array_values(array_filter($idsEnviados, fn($id) => !in_array($id, $bloqueados)));

    // ── Cálculo de horas totales ──────────────────────────────────────────────
    // Construimos un mapa [id => horas] para calcular el total de horas del profesor
    $horasMap     = array_column($todosModulos, 'horas', 'id');
    $horasTotales = array_sum(array_map(fn($id) => (int)($horasMap[$id] ?? 0), $idsValidos));

    // ── Actualización en la base de datos ─────────────────────────────────────
    // Recorremos TODOS los módulos y actualizamos solo los que han cambiado de estado
    foreach ($todosModulos as $mod) {
        $enLista = in_array($mod['id'], $idsValidos);   // ¿Está en la nueva lista?
        $esMio   = (int)$mod['profesor_id'] === $profesor_id; // ¿Ya era de este profesor?

        if ($enLista && !$esMio) {
            // Módulo en la lista pero no asignado a este profesor → ASIGNAR
            $modelo->asignarProfesor($mod['id'], $profesor_id);
        } elseif (!$enLista && $esMio) {
            // Módulo asignado a este profesor pero ya no está en la lista → DESASIGNAR
            $modelo->asignarProfesor($mod['id'], null);
        }
        // Si está en lista y ya era suyo, o no está en lista y no era suyo → no hacemos nada
    }

    // ── Respuesta con advertencia de horas ────────────────────────────────────
    // Avisamos si el profesor supera el límite recomendado de 20 horas
    $supera20 = $horasTotales > 20;

    echo json_encode([
        'ok'            => true,
        'mensaje'       => $supera20
            ? "⚠️ Guardado. El profesor supera las 20 horas ({$horasTotales}h)."
            : 'Módulos actualizados correctamente.',
        'supera_20'     => $supera20,
        'horas_totales' => $horasTotales,
    ]);

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['ok' => false, 'mensaje' => 'Error interno: ' . $e->getMessage()]);
}
