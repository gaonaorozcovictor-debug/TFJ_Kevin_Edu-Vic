<?php
/**
 * =============================================================================
 * ENDPOINT AJAX - CHECK CONTRASEÑA PROFESOR
 * controladores/Controlador_checkPasswordProfesor.php
 * =============================================================================
 * Endpoint ligero que consulta si un profesor ya tiene contraseña configurada.
 *
 * Se llama desde el formulario de login (JavaScript) cuando el usuario
 * selecciona un profesor en el desplegable, para saber si debe mostrar
 * el campo de contraseña o no.
 *
 * Método:  GET
 * Params:  profesor_id (int)
 * Respuesta JSON: { "tienePassword": true/false }
 *
 * Nota: este endpoint es público (no requiere sesión) porque se llama
 * antes de iniciar sesión, durante el proceso de login.
 */
require_once __DIR__ . '/../core/BaseDatos.php';
require_once __DIR__ . '/../modelos/Modelo_usuarios.php';

header('Content-Type: application/json');

$profesorId = (int)($_GET['profesor_id'] ?? 0);

// Si no se pasa un ID válido, asumimos que no tiene contraseña
if ($profesorId <= 0) {
    echo json_encode(['tienePassword' => false]);
    exit();
}

// Consultamos en la BD si existe un hash de contraseña para este profesor
$modelo = new Modelo_usuarios();
echo json_encode(['tienePassword' => $modelo->profesorTienePassword($profesorId)]);
exit();
