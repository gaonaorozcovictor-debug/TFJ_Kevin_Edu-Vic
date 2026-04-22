<?php
require_once __DIR__ . '/../core/BaseDatos.php';

class Modelo_profesores {

    private PDO $db;

    public function __construct() {
        $this->db = BaseDatos::conexion();
    }

    public function guardarProfesor(string $nombre, string $categoria): void {
        $stmt = $this->db->prepare(
            'INSERT INTO profesores (nombre, categoria) VALUES (?, ?)'
        );
        $stmt->execute([$nombre, $categoria]);
    }

    public function existeProfesor(string $nombre): bool {
        $stmt = $this->db->prepare(
            'SELECT orden FROM profesores WHERE nombre = ? LIMIT 1'
        );
        $stmt->execute([$nombre]);
        return $stmt->rowCount() > 0;
    }

    public function obtenerProfesores(): array {
        return $this->db->query('SELECT * FROM profesores ORDER BY nombre')->fetchAll();
    }

    public function obtenerProfesorPorId(int $id): ?array {
        $stmt = $this->db->prepare('SELECT * FROM profesores WHERE orden = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }


public function eliminarTodos() {
    try {
        // Aseguramos que los módulos se queden "huérfanos" pero no se borren.
        // Importante: No usamos FOREIGN_KEY_CHECKS aquí para que el UPDATE sea forzoso.
        $this->db->exec("UPDATE modulos SET profesor_id = NULL"); 

        $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
        // Usamos DELETE para mayor seguridad que TRUNCATE
        $this->db->exec("DELETE FROM profesores");
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
    } catch (Exception $e) {
        error_log("Error en eliminarTodos Profesores: " . $e->getMessage());
        throw $e;
    }
}
}
