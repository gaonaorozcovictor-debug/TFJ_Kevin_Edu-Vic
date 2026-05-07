<?php
/**
 * Controlador_exportarPDF.php
 * Genera un PDF con los datos del profesor usando la plantilla oficial.
 * Llama al script Python que superpone los datos sobre la plantilla.
 */
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'mensaje' => 'No autorizado']);
    exit();
}

require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_modulos.php';
require_once __DIR__ . '/../modelos/Modelo_profesores.php';

$profesor_id = $_SESSION['usuario']; // El ID del profesor en sesión

try {
    $modeloMod  = new Modelo_modulos();
    $modeloProf = new Modelo_profesores();
    
    $modulos     = $modeloMod->obtenerModulosPorProfesor($profesor_id);
    $profesorRow = $modeloProf->obtenerPorOrden($profesor_id);
    
    $nombre      = $profesorRow['nombre']    ?? ($_SESSION['nombre'] ?? 'Profesor');
    $especialidad = $profesorRow['categoria'] ?? '-';
    
    // Preparar datos para el script Python
    $datos = [
        'nombre'        => $nombre,
        'especialidad'  => $especialidad,
        'modulos'       => array_values($modulos),
        'plantilla_path' => __DIR__ . '/../vistas/plantilla_modulos.pdf',
    ];
    
    $json_input = json_encode($datos, JSON_UNESCAPED_UNICODE);
    
    // Llamar al script Python
    $script = __DIR__ . '/generar_pdf_profesor.py';
    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];
    
    $proc = proc_open("python3 " . escapeshellarg($script), $descriptors, $pipes);
    
    if (!is_resource($proc)) {
        throw new Exception('No se pudo ejecutar el generador de PDF');
    }
    
    fwrite($pipes[0], $json_input);
    fclose($pipes[0]);
    
    $pdf_output = stream_get_contents($pipes[1]);
    $stderr_out = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exit_code = proc_close($proc);
    
    if ($exit_code !== 0 || empty($pdf_output)) {
        throw new Exception('Error generando PDF: ' . $stderr_out);
    }
    
    // Nombre del archivo
    $nombre_safe = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $nombre);
    $fecha = date('d-m-Y');
    $filename = "Modulos_{$nombre_safe}_{$fecha}.pdf";
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($pdf_output));
    header('Cache-Control: no-cache');
    
    echo $pdf_output;
    
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'mensaje' => 'Error al generar PDF: ' . $e->getMessage()]);
}
?>
