<?php
/**
 * =============================================================================
 * MODELO DE PROFESORES - modelos/Modelo_profesores.php
 * =============================================================================
 * Gestiona todas las operaciones de base de datos sobre la tabla 'profesores'.
 *
 * Estructura de la tabla 'profesores':
 *  - orden       (int, PK, auto_increment) - ID del profesor
 *  - nombre      (varchar)                 - Nombre completo
 *  - categoria   (varchar)                 - Categoría laboral (PS = Profesor Secundaria,
 *                                            PT = Profesor Técnico)
 *  - departamento (varchar, default '')    - Departamento al que pertenece
 *
 * Relaciones:
 *  - Un profesor puede tener asignados N módulos (relación 1:N en modulos.profesor_id)
 *  - Un profesor puede tener un usuario de acceso (relación 1:1 en usuarios.profesor_id)
 */
require_once __DIR__ . '/../core/BaseDatos.php';

class Modelo_profesores {

    private PDO $db;

    public function __construct() {
        $this->db = BaseDatos::conexion();
    }

    /**
     * Inserta un nuevo profesor en la base de datos.
     * No comprueba duplicados: usa existeProfesor() antes de llamar a este método.
     *
     * @param string $nombre    Nombre completo del profesor.
     * @param string $categoria Categoría laboral (PS o PT).
     */
    public function guardarProfesor(string $nombre, string $categoria): void {
        $stmt = $this->db->prepare(
            'INSERT INTO profesores (nombre, categoria) VALUES (?, ?)'
        );
        $stmt->execute([$nombre, $categoria]);
    }

    /**
     * Comprueba si ya existe un profesor con ese nombre exacto.
     * Se usa para evitar importar duplicados al subir el Excel.
     *
     * @param  string $nombre  Nombre a buscar.
     * @return bool            true si ya existe, false si no.
     */
    public function existeProfesor(string $nombre): bool {
        $stmt = $this->db->prepare(
            'SELECT orden FROM profesores WHERE nombre = ? LIMIT 1'
        );
        $stmt->execute([$nombre]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Devuelve todos los profesores ordenados alfabéticamente por nombre.
     * Se usa para poblar el desplegable del login y las listas del panel admin.
     *
     * @return array  Array de filas asociativas.
     */
    public function obtenerProfesores(): array {
        return $this->db->query('SELECT * FROM profesores ORDER BY nombre')->fetchAll();
    }

    /**
     * Busca un profesor por su ID (columna 'orden').
     *
     * @param  int        $id  ID del profesor.
     * @return array|null      Fila del profesor o null si no existe.
     */
    public function obtenerProfesorPorId(int $id): ?array {
        $stmt = $this->db->prepare('SELECT * FROM profesores WHERE orden = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Elimina todos los profesores de la base de datos.
     *
     * Proceso cuidadoso para respetar las claves foráneas:
     *  1. Primero ponemos a NULL el profesor_id de todos los módulos,
     *     para que los módulos queden sin asignar pero no se borren.
     *  2. Desactivamos FK_CHECKS temporalmente para poder hacer el DELETE.
     *  3. Eliminamos todos los registros de la tabla.
     *  4. Reactivamos FK_CHECKS.
     *
     * ⚠️ Esta operación no se puede deshacer.
     */
    public function eliminarTodos() {
        try {
            // Paso 1: Desvinculamos todos los módulos de sus profesores
            $this->db->exec("UPDATE modulos SET profesor_id = NULL");

            // Paso 2 y 4: Desactivar/reactivar FK para el DELETE
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
            // Usamos DELETE en lugar de TRUNCATE para mayor control y seguridad
            $this->db->exec("DELETE FROM profesores");
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");

        } catch (Exception $e) {
            error_log("Error en eliminarTodos Profesores: " . $e->getMessage());
            throw $e; // Relanzamos para que el controlador lo capture
        }
    }
}
