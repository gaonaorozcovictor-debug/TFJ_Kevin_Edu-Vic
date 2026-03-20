<?php
class Modelo_profesores {

    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "tfg_instituto");
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function guardarProfesor($nombre, $categoria, $departamento = null) {

        $sql = "INSERT INTO profesores (nombre, categoria, departamento) VALUES (?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sss", $nombre, $categoria, $departamento);

        if(!$stmt->execute()){
            echo "Error al insertar profesor: " . $stmt->error;
        }

        $stmt->close();
    }

    public function obtenerProfesores() {
        $resultado = $this->conexion->query("SELECT * FROM profesores");
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}