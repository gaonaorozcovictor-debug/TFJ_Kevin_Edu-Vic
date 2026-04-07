<?php
class Modelo_modulos {

    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "tfg_instituto");

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function guardarModulo($grado, $curso, $nombre_modulo, $horas, $categoria, $profesor_id = null) {

        $sql = "INSERT INTO modulos (grado, curso, nombre_modulo, horas, categoria, profesor_id) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);

        if(!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param("sssisi", $grado, $curso, $nombre_modulo, $horas, $categoria, $profesor_id);

        if(!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $stmt->close();
    }


    public function obtenerHorasProfesor($profesor_id) {

    $stmt = $this->conexion->prepare(
        "SELECT COALESCE(SUM(horas), 0) as total FROM modulos WHERE profesor_id = ?"
    );

    $stmt->bind_param("i", $profesor_id);
    $stmt->execute();

    $resultado = $stmt->get_result()->fetch_assoc();

    return (int)$resultado['total'];
}

    public function obtenerModulosPorProfesor($profesor_id) {

        $stmt = $this->conexion->prepare(
            "SELECT * FROM modulos WHERE profesor_id = ? ORDER BY grado, curso"
        );

        $stmt->bind_param("i", $profesor_id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerModulos() {

        $resultado = $this->conexion->query("SELECT * FROM modulos ORDER BY grado, curso");

        if(!$resultado){
            throw new Exception("Error en la consulta: " . $this->conexion->error);
        }

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

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

    public function obtenerModulosConProfesor() {
        $resultado = $this->conexion->query("
            SELECT m.*, p.nombre as profesor_nombre 
            FROM modulos m
            LEFT JOIN profesores p ON m.profesor_id = p.orden
            ORDER BY m.grado, m.curso
        ");
        
        if(!$resultado){
            throw new Exception("Error en la consulta: " . $this->conexion->error);
        }
        
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerTodosProfesoresConHoras() {
        $resultado = $this->conexion->query("
            SELECT p.*, 
                   COALESCE(SUM(m.horas), 0) as total_horas
            FROM profesores p
            LEFT JOIN modulos m ON p.orden = m.profesor_id
            GROUP BY p.orden
            ORDER BY p.nombre
        ");
        
        if(!$resultado){
            throw new Exception("Error en la consulta: " . $this->conexion->error);
        }
        
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function eliminarTodosModulos() {
        $sql = "DELETE FROM modulos";
        $resultado = $this->conexion->query($sql);
        
        if(!$resultado) {
            throw new Exception("Error al eliminar módulos: " . $this->conexion->error);
        }
        
        $this->conexion->query("ALTER TABLE modulos AUTO_INCREMENT = 1");
        
        return true;
    }
}