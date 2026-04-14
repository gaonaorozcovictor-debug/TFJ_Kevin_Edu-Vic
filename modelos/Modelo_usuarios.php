<?php
class Modelo_usuarios {

    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("134.0.14.185", "asignaciones", "aplicacion$2026dAw", "asignaciones");

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function obtenerPorUsuario($usuario){

        $stmt = $this->conexion->prepare(
            "SELECT * FROM usuarios WHERE usuario = ?"
        );

        $stmt->bind_param("s", $usuario);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
}