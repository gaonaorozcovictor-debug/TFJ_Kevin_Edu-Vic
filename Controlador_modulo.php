<?php
/**
 * =============================================================================
 * CONTROLADOR DE MÓDULOS - controladores/Controlador_modulo.php
 * =============================================================================
 * Gestiona las acciones POST del panel de administración relacionadas con
 * la importación de datos desde ficheros Excel:
 *
 *  - subir_profesores: importa la lista de profesores desde un .xlsx
 *  - subir_modulos:    importa la lista de módulos desde un .xlsx
 *
 * Es instanciado desde index.php cuando llega una petición POST.
 */
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

    /**
     * Punto de entrada principal para peticiones POST.
     * Determina qué acción ejecutar según los campos del formulario enviado.
     * Solo los administradores pueden realizar estas acciones.
     */
    public function manejarPost(): void {
        // Verificación de seguridad: solo el admin puede subir archivos
        if ($_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . '/?vista=profesor');
            exit();
        }

        try {
            // Detectamos qué botón del formulario fue pulsado
            if (isset($_POST['subir_profesores'])) {
                $this->_subirProfesores();
            } elseif (isset($_POST['subir_modulos'])) {
                $this->_subirModulos();
            }

        } catch (Exception $e) {
            // Registramos el error en el log del servidor y mostramos mensaje al usuario
            error_log($e->getMessage());
            $_SESSION['error'] = 'Error interno: ' . $e->getMessage();
            header('Location: ' . BASE_URL . '/?vista=admin');
            exit();
        }
    }

    // ── Métodos privados ──────────────────────────────────────────────────────

    /**
     * Procesa la subida de un Excel de profesores.
     *
     * Flujo:
     *  1. Valida que el archivo se haya subido correctamente.
     *  2. Llama al modelo Excel para parsear el fichero.
     *  3. Guarda solo los profesores que no existan ya (evita duplicados).
     *  4. Informa al usuario cuántos profesores nuevos se han importado.
     */
    private function _subirProfesores(): void {
        $archivo = $_FILES['archivo_profesores'] ?? null;

        // Comprobamos que el archivo llegó sin errores de subida
        if (!$archivo || $archivo['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'No se pudo cargar el archivo de profesores.';
            header('Location: ' . BASE_URL . '/?vista=admin');
            exit();
        }

        // Parseamos el Excel: devuelve array de ['nombre' => ..., 'categoria' => ...]
        $datos = $this->modeloExcel->leerExcelProfesores($archivo['tmp_name']);

        if (empty($datos)) {
            $_SESSION['error'] = 'El Excel de profesores está vacío o tiene un formato incorrecto.';
            header('Location: ' . BASE_URL . '/?vista=admin');
            exit();
        }

        // Insertamos solo los profesores que no estén ya en la BD (por nombre)
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

    /**
     * Procesa la subida de un Excel de módulos.
     *
     * Flujo:
     *  1. Valida que el archivo se haya subido correctamente.
     *  2. Llama al modelo Excel para parsear el fichero.
     *  3. Inserta todos los módulos en la base de datos.
     *     (No comprueba duplicados: se asume que se sube una lista limpia)
     *  4. Informa al usuario cuántos módulos se han importado.
     */
    private function _subirModulos(): void {
        $archivo = $_FILES['archivo_modulos'] ?? null;

        if (!$archivo || $archivo['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'No se pudo cargar el archivo de módulos.';
            header('Location: ' . BASE_URL . '/?vista=admin');
            exit();
        }

        // Parseamos el Excel: devuelve array con grado, curso, nombre_modulo, horas, categoria
        $datos = $this->modeloExcel->leerExcelModulos($archivo['tmp_name']);

        if (empty($datos)) {
            $_SESSION['error'] = 'El Excel de módulos está vacío o tiene un formato incorrecto.';
            header('Location: ' . BASE_URL . '/?vista=admin');
            exit();
        }

        // Insertamos todos los módulos en la BD
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
