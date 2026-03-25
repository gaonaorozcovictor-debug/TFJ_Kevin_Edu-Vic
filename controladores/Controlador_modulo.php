<?php

//los cargo todos  
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
    public function getModeloModulos() {
    return $this->modeloModulos;
}

    public function mostrarModulos() {

        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if(!isset($_SESSION['usuario'])){
            header("Location: ../index.php");
            exit();
        }

        $rol = $_SESSION['rol'] ?? null;

        try {

            //SUBIR PROFESORES
            if (
                $rol === 'admin' &&
                isset($_POST['subir_profesores'])
            ) {

                if (
                    !isset($_FILES['archivo_profesores']) ||
                    $_FILES['archivo_profesores']['error'] !== UPLOAD_ERR_OK
                ) {
                    $_SESSION['error'] = "No se ha subido correctamente el archivo de profesores";
                    header("Location: index.php?vista=modulos");
                    exit();
                }

                $archivo = $_FILES['archivo_profesores']['tmp_name'];

                if (!file_exists($archivo)) {
                    $_SESSION['error'] = "El archivo de profesores no existe";
                    header("Location: index.php?vista=modulos");
                    exit();
                }

                $datos = $this->modeloExcel->leerExcelProfesores($archivo);

                if (empty($datos)) {
                    $_SESSION['error'] = "El Excel de profesores está vacío o mal leído";
                    header("Location: index.php?vista=modulos");
                    exit();
                }

                foreach ($datos as $fila) {

                    if (!isset($fila['nombre']) || !isset($fila['categoria'])) {
                        continue;
                    }

                    if ($this->modeloProfesores->existeProfesor($fila['nombre'])) {
                        continue;
                    }

                    $this->modeloProfesores->guardarProfesor(
                        $fila['nombre'],
                        $fila['categoria']
                    );
                }

                $_SESSION['mensaje'] = "Profesores subidos correctamente";
                header("Location: index.php?vista=modulos");
                exit();
            }

            //SUBIR MÓDULOS
            if (
                $rol === 'admin' &&
                isset($_POST['subir_modulos'])
            ) {

                if (
                    !isset($_FILES['archivo_modulos']) ||
                    $_FILES['archivo_modulos']['error'] !== UPLOAD_ERR_OK
                ) {
                    $_SESSION['error'] = "No se ha subido correctamente el archivo de módulos";
                    header("Location: index.php?vista=modulos");
                    exit();
                }

                $archivo = $_FILES['archivo_modulos']['tmp_name'];

                if (!file_exists($archivo)) {
                    $_SESSION['error'] = "El archivo de módulos no existe";
                    header("Location: index.php?vista=modulos");
                    exit();
                }

                $datos = $this->modeloExcel->leerExcelModulos($archivo);

                foreach ($datos as $modulo) {

                    $this->modeloModulos->guardarModulo(
                        $modulo['grado'],
                        $modulo['curso'],
                        $modulo['nombre_modulo'],
                        $modulo['horas'],
                        $modulo['categoria']
                    );
                }

                $_SESSION['mensaje'] = "Módulos subidos correctamente";
                header("Location: index.php?vista=modulos");
                exit();
            }

            // Obtener módulos según el rol
            if($rol === 'profesor') {
                // Profesor solo ve sus módulos asignados
                $profesor_id = $_SESSION['profesor_id'];
                $modulos = $this->modeloModulos->obtenerModulosPorProfesor($profesor_id);
                $profesores = $this->modeloProfesores->obtenerProfesores();
            } else {
                // Admin ve todos los módulos (con JOIN para obtener nombre del profesor)
                $modulos = $this->modeloModulos->obtenerModulosConProfesor();
                $profesores = $this->modeloProfesores->obtenerProfesores();
                // Para el listado de profesores con horas totales (prioridad media)
                $profesoresConHoras = $this->modeloModulos->obtenerTodosProfesoresConHoras();
            }

        } catch(Exception $e){
            error_log($e->getMessage());
            $_SESSION['error'] = "Error interno: " . $e->getMessage();
        }

        // Cargar la vista correspondiente según el rol
        // Cargar la vista correspondiente según el rol
if($rol === 'admin'){
    require __DIR__ . '/../vistas/adminPanel.php';
} else {
    // PROFESOR: carga su panel de visualización (NO el de asignación)
    // Asegurar que la variable $modulos existe
    if(!isset($modulos)) {
        $modulos = [];
    }
    require __DIR__ . '/../vistas/profesorPanel.php';
}
    }
}