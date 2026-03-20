<?php
session_start();

require_once __DIR__ . '/../modelos/Modelo_modulos.php';

header('Content-Type: application/json');

//Seguridad: solo usuarios logueados
if(!isset($_SESSION['usuario'])){
    echo json_encode(['ok' => false, 'mensaje' => 'No autorizado']);
    exit;
}

$modeloModulos = new Modelo_modulos();

// Leer JSON
$asignaciones = json_decode(file_get_contents('php://input'), true);

if(!$asignaciones){
    echo json_encode(['ok' => false, 'mensaje' => 'No se recibieron datos']);
    exit;
}

try {

    foreach($asignaciones as $asig){

        $modulo_id = (int)$asig['modulo_id'];
        $profesor_id = (int)$asig['profesor_id'];

        $modeloModulos->asignarProfesor($modulo_id, $profesor_id);
    }

    echo json_encode([
        'ok' => true,
        'mensaje' => 'Asignaciones guardadas correctamente'
    ]);

} catch(Exception $e){

    echo json_encode([
        'ok' => false,
        'mensaje' => 'Error: ' . $e->getMessage()
    ]);
}