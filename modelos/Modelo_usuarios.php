<?php
require_once __DIR__ . '/../core/BaseDatos.php';

class Modelo_usuarios {

    private PDO $db;

    public function __construct() {
        $this->db = BaseDatos::conexion();
    }

    public function obtenerPorUsuario(string $usuario): ?array {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE usuario = ? LIMIT 1');
        $stmt->execute([$usuario]);
        return $stmt->fetch() ?: null;
    }

    public function actualizarPassword(string $usuario, string $nuevoHash): void {
        $stmt = $this->db->prepare('UPDATE usuarios SET password = ? WHERE usuario = ?');
        $stmt->execute([$nuevoHash, $usuario]);
    }
}