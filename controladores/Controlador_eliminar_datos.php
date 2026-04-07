<?php
session_start();

// Seguridad básica: solo admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . "/../core/BaseDatos.php";

$db = new BaseDatos();
$conn = $db->conn;

try {

    // IMPORTANTE: desactivar FK temporalmente
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");

    // Vaciar tablas
    $conn->query("TRUNCATE TABLE modulos");
    $conn->query("TRUNCATE TABLE profesores");

    // Reactivar FK
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");

    $_SESSION['mensaje'] = "Datos eliminados correctamente";

} catch (Exception $e) {
    $_SESSION['error'] = "Error al eliminar datos";
}

// Redirigir al index (vista admin)
header("Location: ../index.php?vista=admin");
exit();