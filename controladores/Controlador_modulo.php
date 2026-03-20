<?php
require_once __DIR__ . '/../modelos/Modelo_excel.php';
require_once __DIR__ . '/../modelos/Modelo_profesores.php';
require_once __DIR__ . '/../modelos/Modelo_modulos.php';

class Controlador_modulos {

    private $modeloProfesores;
    private $modeloModulos;
    private $modeloExcel;

    public function __construct() {
        $this->modeloProfesores = new Modelo_profesores();
        $this->modeloModulos = new Modelo_modulos();
        $this->modeloExcel = new Modelo_excel();
    }

        public function mostrarModulos() {

            if(!isset($_SESSION['usuario'])){
                header("Location: ../index.php");
                exit();
            }

            $rol = $_SESSION['rol'];

            try {

                // SUBIR PROFESORES
                // ========================
// SUBIR PROFESORES
// ========================
            if(
                $rol === 'admin' &&
                isset($_POST['subir_profesores']) &&
                isset($_FILES['archivo_profesores']) &&
                $_FILES['archivo_profesores']['error'] === UPLOAD_ERR_OK
            ){

                // BLOQUEO SI YA HAY PROFESORES
                if(count($this->modeloProfesores->obtenerProfesores()) > 0){
                    $_SESSION['mensaje'] = " Ya hay profesores cargados. No puedes subir otro Excel.";
                } else {

                    $archivo = $_FILES['archivo_profesores']['tmp_name'];
                    $datos = $this->modeloExcel->leerExcel($archivo);

                    foreach($datos as $index => $fila){

                        if($index === 0) continue;

                        if(empty($fila[1]) || empty($fila[2])) continue;

                        $nombre = trim($fila[1]);
                        $categoria = trim($fila[2]);
                        $departamento = isset($fila[3]) ? trim($fila[3]) : null;

                        $this->modeloProfesores->guardarProfesor($nombre, $categoria, $departamento);
                    }

                    $_SESSION['mensaje'] = "Excel de Profesores subido correctamente";
                }
            }


                // SUBIR MÓDULOS
                if(
                    $rol === 'admin' &&
                    isset($_POST['subir_modulos']) &&
                    isset($_FILES['archivo_modulos']) &&
                    $_FILES['archivo_modulos']['error'] === UPLOAD_ERR_OK
                ){

                    if(count($this->modeloModulos->obtenerModulos()) > 0){
                        $_SESSION['mensaje'] = "Ya existe un Excel cargado.";
                    } else {

                        $archivo = $_FILES['archivo_modulos']['tmp_name'];
                        $datos = $this->modeloExcel->leerExcel($archivo);

                        foreach($datos as $index => $fila){

                            if($index === 0) continue;

                            if(!isset($fila[2])) continue;

                            $nombre_modulo = trim($fila[2]);

                            $this->modeloModulos->guardarModulo(null, $nombre_modulo);
                        }

                        $_SESSION['mensaje'] = "Excel de modulos subido correctamente";
                    }
                }

                // MOSTRAR DATOS
                $modulos = $this->modeloModulos->obtenerModulos();
                $profesores = $this->modeloProfesores->obtenerProfesores();

            } catch(Exception $e){
                error_log("ERROR: " . $e->getMessage());
            }

            if($rol === 'admin'){
                require __DIR__ . '/../vistas/adminPanel.php';
            } else {
                require __DIR__ . '/../vistas/asignarModulos.php';
            }
        }
}