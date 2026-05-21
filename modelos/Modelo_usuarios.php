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

    /** Obtiene el registro de usuario vinculado a un profesor_id (si existe) */
    public function obtenerPorProfesorId(int $profesorId): ?array {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE profesor_id = ? LIMIT 1');
        $stmt->execute([$profesorId]);
        return $stmt->fetch() ?: null;
    }

    /** Comprueba si un profesor ya tiene contraseña establecida */
    public function profesorTienePassword(int $profesorId): bool {
        $user = $this->obtenerPorProfesorId($profesorId);
        return $user !== null && !empty($user['password']);
    }

    /** Crea un usuario para un profesor (sin contraseña aún) */
    public function crearUsuarioProfesor(int $profesorId, string $nombreUsuario): void {
        $stmt = $this->db->prepare(
            'INSERT IGNORE INTO usuarios (usuario, password, rol, profesor_id) VALUES (?, \'\', \'profesor\', ?)'
        );
        $stmt->execute([$nombreUsuario, $profesorId]);
    }

    /** Establece o actualiza la contraseña de un profesor por su profesor_id */
    public function establecerPasswordProfesor(int $profesorId, string $hash): bool {
        // Si ya tiene fila en usuarios, actualizar
        $user = $this->obtenerPorProfesorId($profesorId);
        if ($user) {
            $stmt = $this->db->prepare('UPDATE usuarios SET password = ? WHERE profesor_id = ?');
            $stmt->execute([$hash, $profesorId]);
            return true;
        }
        return false;
    }
}
