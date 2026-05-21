<?php
/**
 * =============================================================================
 * MODELO DE MÓDULOS - modelos/Modelo_modulos.php
 * =============================================================================
 * Gestiona todas las operaciones de base de datos sobre la tabla 'modulos'.
 *
 * Estructura de la tabla 'modulos':
 *  - id            (int, PK, auto_increment)
 *  - profesor_id   (int, FK → profesores.orden, nullable) - Profesor asignado
 *  - nombre_modulo (varchar) - Nombre del módulo/asignatura
 *  - grado         (varchar) - Ciclo formativo (ASIR, DAM, DAW, SMR...)
 *  - curso         (varchar) - Curso (1, 2, 1A, 1B, 2A, 2B...)
 *  - horas         (int)     - Horas semanales del módulo
 *  - categoria     (varchar) - Categoría requerida para impartirlo (INF, SAI...)
 *
 * Relaciones:
 *  - Muchos módulos pueden pertenecer a un profesor (N:1 con profesores)
 *  - profesor_id = NULL significa que el módulo no tiene profesor asignado
 */
require_once __DIR__ . '/../core/BaseDatos.php';

class Modelo_modulos {

    private PDO $db;

    public function __construct() {
        $this->db = BaseDatos::conexion();
    }

    /**
     * Inserta un nuevo módulo en la base de datos.
     * Todos los parámetros son opcionales (nullable) para flexibilidad
     * al importar desde Excel, donde algunas celdas pueden estar vacías.
     *
     * @param string|null $grado         Ciclo formativo del módulo.
     * @param string|null $curso         Curso al que pertenece.
     * @param string|null $nombre_modulo Nombre del módulo/asignatura.
     * @param int|null    $horas         Horas semanales.
     * @param string|null $categoria     Categoría docente necesaria.
     * @param int|null    $profesor_id   ID del profesor asignado (null = sin asignar).
     */
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

    /**
     * Devuelve todos los módulos ordenados por grado y curso.
     * Sin información del profesor (solo el ID si lo tiene).
     *
     * @return array
     */
    public function obtenerModulos(): array {
        return $this->db->query(
            'SELECT * FROM modulos ORDER BY grado, curso'
        )->fetchAll();
    }

    /**
     * Devuelve todos los módulos con el nombre del profesor asignado (JOIN).
     * Usa LEFT JOIN para incluir también los módulos sin asignar (profesor_id = NULL).
     *
     * Añade la columna 'profesor_nombre' con el nombre del profesor o NULL.
     *
     * @return array
     */
    public function obtenerModulosConProfesor(): array {
        return $this->db->query(
            'SELECT m.*, p.nombre AS profesor_nombre
             FROM modulos m
             LEFT JOIN profesores p ON m.profesor_id = p.orden
             ORDER BY m.grado, m.curso'
        )->fetchAll();
    }

    /**
     * Devuelve solo los módulos asignados a un profesor concreto.
     * Se usa en el panel del profesor para mostrar su carga horaria.
     *
     * @param  int   $profesor_id  ID del profesor.
     * @return array
     */
    public function obtenerModulosPorProfesor(int $profesor_id): array {
        $stmt = $this->db->prepare(
            'SELECT * FROM modulos WHERE profesor_id = ? ORDER BY grado, curso'
        );
        $stmt->execute([$profesor_id]);
        return $stmt->fetchAll();
    }

    /**
     * Calcula el total de horas asignadas a un profesor.
     * Usa COALESCE para devolver 0 en lugar de NULL si no tiene módulos.
     *
     * @param  int $profesor_id  ID del profesor.
     * @return int               Total de horas (0 si no tiene módulos asignados).
     */
    public function obtenerHorasProfesor(int $profesor_id): int {
        $stmt = $this->db->prepare(
            'SELECT COALESCE(SUM(horas), 0) AS total FROM modulos WHERE profesor_id = ?'
        );
        $stmt->execute([$profesor_id]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Devuelve todos los profesores junto con su total de horas asignadas.
     * Usa GROUP BY para agregar y LEFT JOIN para incluir profesores sin módulos.
     * Se usa en el panel admin para mostrar el resumen de carga horaria.
     *
     * @return array  Array con columnas de profesores + 'total_horas'.
     */
    public function obtenerTodosProfesoresConHoras(): array {
        return $this->db->query(
            'SELECT p.*, COALESCE(SUM(m.horas), 0) AS total_horas
             FROM profesores p
             LEFT JOIN modulos m ON p.orden = m.profesor_id
             GROUP BY p.orden
             ORDER BY p.nombre'
        )->fetchAll();
    }

    /**
     * Asigna o desasigna un profesor de un módulo concreto.
     * Es la operación central de la funcionalidad de asignaciones.
     *
     * @param int      $modulo_id    ID del módulo a modificar.
     * @param int|null $profesor_id  ID del profesor a asignar, o NULL para desasignar.
     */
    public function asignarProfesor(int $modulo_id, ?int $profesor_id): void {
        $stmt = $this->db->prepare(
            'UPDATE modulos SET profesor_id = ? WHERE id = ?'
        );
        $stmt->execute([$profesor_id, $modulo_id]);
    }

    /**
     * Elimina todos los módulos de la base de datos.
     * Los profesores no se ven afectados (son la tabla "padre").
     *
     * Desactiva temporalmente FK_CHECKS para garantizar el borrado
     * aunque haya referencias activas.
     *
     * ⚠️ Esta operación no se puede deshacer.
     */
    public function eliminarTodos() {
        try {
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
            // DELETE en lugar de TRUNCATE para mayor seguridad y control
            $this->db->exec("DELETE FROM modulos");
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");

        } catch (Exception $e) {
            error_log("Error en eliminarTodos Módulos: " . $e->getMessage());
            throw $e;
        }
    }
}
