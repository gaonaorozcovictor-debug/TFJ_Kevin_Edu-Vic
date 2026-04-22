<?php
require_once __DIR__ . '/../modelos/Modelo_excel.php';
require_once __DIR__ . '/../modelos/Modelo_profesores.php';
require_once __DIR__ . '/../modelos/Modelo_modulos.php';

class Controlador_modulos {

    private Modelo_profesores $modeloProf;
    private Modelo_modulos    $modeloMod;
    private Modelo_excel      $modeloExcel;

    public function __construct() {
        $this->modeloProf  = new Modelo_profesores();
        $this->modeloMod   = new Modelo_modulos();
        $this->modeloExcel = new Modelo_excel();
    }

    public function manejarPost(): void {
        if ($_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . '/?vista=profesor');
            exit();
        }

        try {

            if (isset($_POST['subir_profesores'])) {
                $this->_subirProfesores();
            } elseif (isset($_POST['subir_modulos'])) {
                $this->_subirModulos();
            }

        } catch (Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error'] = 'Error interno: ' . $e->getMessage();
            header('Location: ' . BASE_URL . '/?vista=admin');
            exit();
        }
    }

    // ── Privados ─────────────────────────────────────────────────────────────

    private function _subirProfesores(): void {
        $archivo = $_FILES['archivo_profesores'] ?? null;

        if (!$archivo || $archivo['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'No se pudo cargar el archivo de profesores.';
            header('Location: ' . BASE_URL . '/?vista=admin');
            exit();
        }

        $datos = $this->modeloExcel->leerExcelProfesores($archivo['tmp_name']);

        if (empty($datos)) {
            $_SESSION['error'] = 'El Excel de profesores está vacío o tiene un formato incorrecto.';
            header('Location: ' . BASE_URL . '/?vista=admin');
            exit();
        }

        $guardados = 0;
        foreach ($datos as $fila) {
            if ($this->modeloProf->existeProfesor($fila['nombre'])) continue;
            $this->modeloProf->guardarProfesor($fila['nombre'], $fila['categoria']);
            $guardados++;
        }

        $_SESSION['mensaje'] = "Profesores importados: {$guardados} nuevos.";
        header('Location: ' . BASE_URL . '/?vista=admin');
        exit();
    }

    private function _subirModulos(): void {
        $archivo = $_FILES['archivo_modulos'] ?? null;

        if (!$archivo || $archivo['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'No se pudo cargar el archivo de módulos.';
            header('Location: ' . BASE_URL . '/?vista=admin');
            exit();
        }

        $datos = $this->modeloExcel->leerExcelModulos($archivo['tmp_name']);

        if (empty($datos)) {
            $_SESSION['error'] = 'El Excel de módulos está vacío o tiene un formato incorrecto.';
            header('Location: ' . BASE_URL . '/?vista=admin');
            exit();
        }

        foreach ($datos as $mod) {
            $this->modeloMod->guardarModulo(
                $mod['grado'],
                $mod['curso'],
                $mod['nombre_modulo'],
                $mod['horas'],
                $mod['categoria']
            );
        }

        $_SESSION['mensaje'] = count($datos) . ' módulos importados correctamente.';
        header('Location: ' . BASE_URL . '/?vista=admin');
        exit();
    }
}
