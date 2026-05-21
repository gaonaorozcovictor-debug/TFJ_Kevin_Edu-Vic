<?php
// Endpoint AJAX: devuelve JSON indicando si un profesor ya tiene contraseña
require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_usuarios.php';

header('Content-Type: application/json');

$profesorId = (int)($_GET['profesor_id'] ?? 0);
if ($profesorId <= 0) {
    echo json_encode(['tienePassword' => false]);
    exit();
}

$modelo = new Modelo_usuarios();
echo json_encode(['tienePassword' => $modelo->profesorTienePassword($profesorId)]);
exit();
