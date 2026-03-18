<?php
class Modelo_profesores {

    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "gestion_modulos");
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function guardarProfesor($nombre, $categoria) {
        $sql = "INSERT INTO profesores (nombre, categoria) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $nombre, $categoria);
        $stmt->execute();
        $stmt->close();
    }

    public function obtenerProfesores() {
        $resultado = $this->conexion->query("SELECT * FROM profesores");
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}