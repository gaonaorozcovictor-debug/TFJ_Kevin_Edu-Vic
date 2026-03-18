<?php

class Modelo_modulos {

    private $conexion;

    public function __construct() {

        // Conectar a MySQL
        $this->conexion = new mysqli("localhost", "root", "", "profes");

        if ($this->conexion->connect_error) {
            file_put_contents("pendientes_modulos.txt", "Error de conexión\n", FILE_APPEND);
            return;
        }
    }

    public function guardarModulo($grado, $curso, $modulo, $horas) {

        $sql = "INSERT INTO modulos (grado, curso, modulo, horas)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssi", $grado, $curso, $modulo, $horas);
        $stmt->execute();
        $stmt->close();
    }
}
