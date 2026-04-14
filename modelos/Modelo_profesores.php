<?php
class Modelo_profesores {

    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("134.0.14.185", "asignaciones", "aplicacion$2026dAw", "asignaciones");

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function guardarProfesor($nombre, $categoria) {

        $sql = "INSERT INTO profesores (nombre, categoria) VALUES (?, ?)";

        $stmt = $this->conexion->prepare($sql);

        if(!$stmt){
            die("Error prepare: " . $this->conexion->error);
        }

        $stmt->bind_param("ss", $nombre, $categoria);

        if(!$stmt->execute()){
            die("Error al insertar: " . $stmt->error);
        }

        $stmt->close();
    }

    public function existeProfesor($nombre){

        $stmt = $this->conexion->prepare(
            "SELECT orden FROM profesores WHERE nombre = ? LIMIT 1"
        );

        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    public function obtenerProfesores() {
        return $this->conexion->query("SELECT * FROM profesores")->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerProfesorPorId($id) {

        $stmt = $this->conexion->prepare("SELECT * FROM profesores WHERE orden = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Método para eliminar todos los profesores
public function eliminarTodosProfesores() {
    $sql = "DELETE FROM profesores";
    $resultado = $this->conexion->query($sql);
    
    if(!$resultado) {
        throw new Exception("Error al eliminar profesores: " . $this->conexion->error);
    }
    
    // Resetear AUTO_INCREMENT
    $this->conexion->query("ALTER TABLE profesores AUTO_INCREMENT = 1");
    
    return true;
}
}