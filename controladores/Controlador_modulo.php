<?php
require_once __DIR__ . '/../modelos/Modelo_excell.php';
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

        if(!isset($_SESSION['sesion'])){
            header("Location: ../index.php");
            exit();
        }

        $error = null;

        try {
            // Subida Profesores
            if(isset($_FILES['archivo_profesores']) && $_FILES['archivo_profesores']['tmp_name'] !== '') {
                $archivo = $_FILES['archivo_profesores']['tmp_name'];
                $datosProfesores = $this->modeloExcel->leerExcel($archivo);

                foreach($datosProfesores as $index => $fila) {
                    if($index == 0) continue; // saltar cabecera
                    $nombre = trim($fila[1]); // columna Profesor
                    $categoria = trim($fila[2]); // columna Categoría
                    $this->modeloProfesores->guardarProfesor($nombre, $categoria);
                }
                $_SESSION['datosProfesores'] = $datosProfesores;
                $mensajeProfesores = "Profesores guardados correctamente en la base de datos.";
            }

            // Subida Módulos
            if(isset($_FILES['archivo_modulos']) && $_FILES['archivo_modulos']['tmp_name'] !== '') {
                $archivo = $_FILES['archivo_modulos']['tmp_name'];
                $datosModulos = $this->modeloExcel->leerExcel($archivo);

                foreach($datosModulos as $index => $fila) {
                    if($index == 0) continue; // saltar cabecera
                    $grado = trim($fila[0]);
                    $curso = trim($fila[1]);
                    $modulo = trim($fila[2]);
                    $horas = (int)trim($fila[3]);
                    $categoria = trim($fila[4]);
                    $this->modeloModulos->guardarModulo($grado, $curso, $modulo, $horas, $categoria);
                }
                $_SESSION['datosModulos'] = $datosModulos;
                $mensajeModulos = "Módulos guardados correctamente en la base de datos.";
            }

        } catch(Exception $e) {
            $error = $e->getMessage();
        }

        // Traer datos de la BD para mostrarlos en la vista
        $datosProfesoresBD = $this->modeloProfesores->obtenerProfesores();
        $datosModulosBD = $this->modeloModulos->obtenerModulos();

        require __DIR__ . '/../vistas/mostrarModulos.php';
    }
}