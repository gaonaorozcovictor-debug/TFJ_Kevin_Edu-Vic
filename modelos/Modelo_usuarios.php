<?php
/**
 * =============================================================================
 * MODELO DE USUARIOS - modelos/Modelo_usuarios.php
 * =============================================================================
 * Gestiona todas las operaciones de base de datos relacionadas con la tabla
 * 'usuarios', que almacena las credenciales de login.
 *
 * Estructura de la tabla 'usuarios':
 *  - id          (int, PK, auto_increment)
 *  - usuario     (varchar, UNIQUE) - nombre de usuario
 *  - password    (varchar)         - hash bcrypt de la contraseña
 *  - rol         (enum: 'admin'|'profesor')
 *  - profesor_id (int, FK → profesores.orden, nullable)
 *                Solo se rellena para usuarios de tipo 'profesor'.
 *                El admin tiene profesor_id = NULL.
 */
require_once __DIR__ . '/../core/BaseDatos.php';

class Modelo_usuarios {

    private PDO $db;

    public function __construct() {
        // Obtenemos la conexión Singleton de la BD
        $this->db = BaseDatos::conexion();
    }

    /**
     * Busca un usuario por su nombre de usuario (login de admin).
     *
     * @param  string     $usuario  Nombre de usuario introducido en el formulario.
     * @return array|null           Fila completa de la tabla o null si no existe.
     */
    public function obtenerPorUsuario(string $usuario): ?array {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE usuario = ? LIMIT 1');
        $stmt->execute([$usuario]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Actualiza el hash de contraseña de un usuario (admin).
     * Se usa en el flujo de cambio de contraseña del administrador.
     *
     * @param string $usuario    Nombre de usuario cuya contraseña se actualiza.
     * @param string $nuevoHash  Nuevo hash bcrypt generado con password_hash().
     */
    public function actualizarPassword(string $usuario, string $nuevoHash): void {
        $stmt = $this->db->prepare('UPDATE usuarios SET password = ? WHERE usuario = ?');
        $stmt->execute([$nuevoHash, $usuario]);
    }

    /**
     * Obtiene el registro de usuario vinculado a un profesor concreto.
     * Usado en el login de profesores para comprobar si ya tienen contraseña.
     *
     * @param  int        $profesorId  ID del profesor (columna 'orden' en profesores).
     * @return array|null              Fila de la tabla o null si no existe.
     */
    public function obtenerPorProfesorId(int $profesorId): ?array {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE profesor_id = ? LIMIT 1');
        $stmt->execute([$profesorId]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Comprueba si un profesor ya tiene contraseña configurada.
     * Devuelve true si existe fila en usuarios y la contraseña no está vacía.
     *
     * @param  int  $profesorId  ID del profesor.
     * @return bool
     */
    public function profesorTienePassword(int $profesorId): bool {
        $user = $this->obtenerPorProfesorId($profesorId);
        return $user !== null && !empty($user['password']);
    }

    /**
     * Crea un registro de usuario vacío (sin contraseña) para un profesor.
     * Se llama la primera vez que un profesor accede al sistema sin haber
     * configurado contraseña previamente, para tener la fila lista en la tabla.
     *
     * Usa INSERT IGNORE para no fallar si ya existía la fila.
     *
     * @param int    $profesorId     ID del profesor.
     * @param string $nombreUsuario  Nombre de usuario generado (ej: 'prof_5').
     */
    public function crearUsuarioProfesor(int $profesorId, string $nombreUsuario): void {
        $stmt = $this->db->prepare(
            "INSERT IGNORE INTO usuarios (usuario, password, rol, profesor_id) VALUES (?, '', 'profesor', ?)"
        );
        $stmt->execute([$nombreUsuario, $profesorId]);
    }

    /**
     * Establece o actualiza la contraseña de un profesor por su profesor_id.
     * Requiere que ya exista fila en 'usuarios' (creada por crearUsuarioProfesor).
     *
     * @param  int    $profesorId  ID del profesor.
     * @param  string $hash        Hash bcrypt de la nueva contraseña.
     * @return bool                true si se actualizó, false si no existía la fila.
     */
    public function establecerPasswordProfesor(int $profesorId, string $hash): bool {
        $user = $this->obtenerPorProfesorId($profesorId);
        if ($user) {
            $stmt = $this->db->prepare('UPDATE usuarios SET password = ? WHERE profesor_id = ?');
            $stmt->execute([$hash, $profesorId]);
            return true;
        }
        return false;
    }
}
