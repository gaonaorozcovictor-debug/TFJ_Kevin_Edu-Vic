<?php
require_once __DIR__ . '/../core/BaseDatos.php';

class Modelo_modulos {

    private PDO $db;

    public function __construct() {
        $this->db = BaseDatos::conexion();
    }

    public function guardarModulo(
        ?string $grado,
        ?string $curso,
        ?string $nombre_modulo,
        ?int    $horas,
        ?string $categoria,
        ?int    $profesor_id = null
    ): void {
        $stmt = $this->db->prepare(
            'INSERT INTO modulos (grado, curso, nombre_modulo, horas, categoria, profesor_id)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$grado, $curso, $nombre_modulo, $horas, $categoria, $profesor_id]);
    }

    public function obtenerModulos(): array {
        return $this->db->query(
            'SELECT * FROM modulos ORDER BY grado, curso'
        )->fetchAll();
    }

    public function obtenerModulosConProfesor(): array {
        return $this->db->query(
            'SELECT m.*, p.nombre AS profesor_nombre
             FROM modulos m
             LEFT JOIN profesores p ON m.profesor_id = p.orden
             ORDER BY m.grado, m.curso'
        )->fetchAll();
    }

    public function obtenerModulosPorProfesor(int $profesor_id): array {
        $stmt = $this->db->prepare(
            'SELECT * FROM modulos WHERE profesor_id = ? ORDER BY grado, curso'
        );
        $stmt->execute([$profesor_id]);
        return $stmt->fetchAll();
    }

    public function obtenerHorasProfesor(int $profesor_id): int {
        $stmt = $this->db->prepare(
            'SELECT COALESCE(SUM(horas), 0) AS total FROM modulos WHERE profesor_id = ?'
        );
        $stmt->execute([$profesor_id]);
        return (int)$stmt->fetchColumn();
    }

    public function obtenerTodosProfesoresConHoras(): array {
        return $this->db->query(
            'SELECT p.*, COALESCE(SUM(m.horas), 0) AS total_horas
             FROM profesores p
             LEFT JOIN modulos m ON p.orden = m.profesor_id
             GROUP BY p.orden
             ORDER BY p.nombre'
        )->fetchAll();
    }

    public function asignarProfesor(int $modulo_id, ?int $profesor_id): void {
        $stmt = $this->db->prepare(
            'UPDATE modulos SET profesor_id = ? WHERE id = ?'
        );
        $stmt->execute([$profesor_id, $modulo_id]);
    }

public function eliminarTodos() {
    try {
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
        // Borramos SOLO la tabla módulos. 
        // Al borrar módulos, los profesores NO se ven afectados porque ellos son el "padre".
        $this->db->exec("DELETE FROM modulos"); 
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
    } catch (Exception $e) {
        error_log("Error en eliminarTodos Módulos: " . $e->getMessage());
        throw $e;
    }
}
}
