<?php
class Modelo_usuarios {

    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "tfg_instituto");

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