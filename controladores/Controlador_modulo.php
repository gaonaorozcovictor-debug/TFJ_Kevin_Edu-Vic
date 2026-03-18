<?php
require_once __DIR__ . '/../modelos/Modelo_excell.php';

class Controlador_modulos {

    public function mostrarModulos() {

        if(!isset($_SESSION['sesion'])){
            header("Location: ../index.php");
            exit();
        }

        $modelo = new Modelo_excel();
        $error = null;

        // Inicializar sesión para tablas si no existe
        if(!isset($_SESSION['datosProfesores'])){
            $_SESSION['datosProfesores'] = [];
        }
        if(!isset($_SESSION['datosModulos'])){
            $_SESSION['datosModulos'] = [];
        }

        try {
            // Subida Profesores
            if(isset($_FILES['archivo_profesores']) && $_FILES['archivo_profesores']['tmp_name'] !== ''){
                $archivo = $_FILES['archivo_profesores']['tmp_name'];
                $_SESSION['datosProfesores'] = $modelo->leerExcel($archivo);
            }

            // Subida Módulos
            if(isset($_FILES['archivo_modulos']) && $_FILES['archivo_modulos']['tmp_name'] !== ''){
                $archivo = $_FILES['archivo_modulos']['tmp_name'];
                $_SESSION['datosModulos'] = $modelo->leerExcel($archivo);
            }

        } catch(Exception $e){
            $error = $e->getMessage();
        }

        // Usar las sesiones para la vista
        $datosProfesores = $_SESSION['datosProfesores'];
        $datosModulos = $_SESSION['datosModulos'];

        require __DIR__ . '/../vistas/mostrarModulos.php';
    }
}