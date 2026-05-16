<?php
// controladores/Controlador_plantillaPDF.php
// Sirve la plantilla oficial PDF como base64 para que el JS pueda usarla con pdf-lib

if (session_status() === PHP_SESSION_NONE) session_start();

// Solo profesores o administradores autenticados
if (!isset($_SESSION['usuario'])) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'mensaje' => 'No autorizado']);
    exit();
}

$rutaPlantilla = __DIR__ . '/../Recursos/plantilla_oficial.pdf';

if (!file_exists($rutaPlantilla)) {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'mensaje' => 'Plantilla no encontrada']);
    exit();
}

$pdfBytes = file_get_contents($rutaPlantilla);
$base64   = base64_encode($pdfBytes);

header('Content-Type: application/json');
header('Cache-Control: private, max-age=3600');
echo json_encode(['ok' => true, 'base64' => $base64]);
?>
