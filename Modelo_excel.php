<?php
/**
 * =============================================================================
 * MODELO DE EXCEL - modelos/Modelo_excel.php
 * =============================================================================
 * Encapsula la lectura de ficheros Excel (.xlsx) usando la librería
 * PhpSpreadsheet (instalada vía Composer en Recursos/vendor/).
 *
 * Proporciona dos métodos especializados según el tipo de Excel:
 *  - leerExcelProfesores(): para el fichero con la lista de profesores
 *  - leerExcelModulos():    para el fichero con la lista de módulos
 *
 * Formato esperado del Excel de PROFESORES (columnas desde la 2ª fila):
 *  Columna 0 (A): (ignorada)
 *  Columna 1 (B): Nombre del profesor
 *  Columna 2 (C): Categoría (PS/PT)
 *
 * Formato esperado del Excel de MÓDULOS (columnas desde la 2ª fila):
 *  Columna 0 (A): Grado (ciclo formativo)
 *  Columna 1 (B): Curso
 *  Columna 2 (C): Nombre del módulo
 *  Columna 3 (D): Horas semanales
 *  Columna 4 (E): Categoría requerida
 */
require_once __DIR__ . '/../Recursos/vendor/autoload.php'; // Autoloader de Composer
use PhpOffice\PhpSpreadsheet\IOFactory;

class Modelo_excel {

    /**
     * Lee un Excel de profesores y devuelve un array de datos normalizados.
     *
     * Ignora la primera fila (cabecera) y las filas donde nombre o categoría
     * estén vacíos.
     *
     * @param  string $ruta  Ruta al fichero temporal subido ($_FILES[...]['tmp_name']).
     * @return array         Array de ['nombre' => string, 'categoria' => string].
     */
    public function leerExcelProfesores(string $ruta): array {
        // IOFactory detecta automáticamente el formato del fichero (.xlsx, .xls, etc.)
        $hoja  = IOFactory::load($ruta)->getActiveSheet();
        // toArray convierte la hoja completa en un array de filas (array de arrays)
        $filas = $hoja->toArray(null, true, false, false);
        $profesores = [];

        foreach ($filas as $i => $fila) {
            if ($i === 0) continue; // Saltamos la fila de cabecera (índice 0)

            $nombre    = trim($fila[1] ?? '');
            $categoria = trim($fila[2] ?? '');

            // Ignoramos filas incompletas (nombre o categoría vacíos)
            if ($nombre === '' || $categoria === '') continue;

            $profesores[] = ['nombre' => $nombre, 'categoria' => $categoria];
        }

        return $profesores;
    }

    /**
     * Lee un Excel de módulos y devuelve un array de datos normalizados.
     *
     * Ignora la primera fila (cabecera) y las filas donde la columna del
     * nombre del módulo (columna C, índice 2) esté vacía.
     *
     * @param  string $ruta  Ruta al fichero temporal subido.
     * @return array         Array de módulos con claves: grado, curso, nombre_modulo, horas, categoria.
     */
    public function leerExcelModulos(string $ruta): array {
        $hoja  = IOFactory::load($ruta)->getActiveSheet();
        $filas = $hoja->toArray(null, true, false, false);
        $modulos = [];

        foreach ($filas as $i => $fila) {
            if ($i === 0) continue;      // Saltamos cabecera
            if (empty($fila[2])) continue; // Si no hay nombre de módulo, ignoramos la fila

            $modulos[] = [
                'grado'         => $fila[0] ?? null,
                'curso'         => $fila[1] ?? null,
                'nombre_modulo' => $fila[2] ?? null,
                // Convertimos a entero; null si no hay valor
                'horas'         => isset($fila[3]) ? (int)$fila[3] : null,
                'categoria'     => $fila[4] ?? null,
            ];
        }

        return $modulos;
    }
}
