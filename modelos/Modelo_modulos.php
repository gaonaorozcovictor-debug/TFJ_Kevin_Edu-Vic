<?php
class Modelo_modulos {

    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "gestion_modulos");
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function guardarModulo($grado, $curso, $modulo, $horas, $categoria, $profesor_id = null) {
        $sql = "INSERT INTO modulos (grado, curso, modulo, horas, categoria, profesor_id)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssisi", $grado, $curso, $modulo, $horas, $categoria, $profesor_id);
        $stmt->execute();
        $stmt->close();
    }

    public function obtenerModulos() {
        $resultado = $this->conexion->query("SELECT * FROM modulos");
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // ===============================================
    // Nuevo método para asignar un profesor a un módulo
    // ===============================================
    public function asignarProfesor($modulo_id, $profesor_id) {
        $sql = "UPDATE modulos SET profesor_id = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);

        if(!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param("ii", $profesor_id, $modulo_id);

        if(!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $stmt->close();
    }
}