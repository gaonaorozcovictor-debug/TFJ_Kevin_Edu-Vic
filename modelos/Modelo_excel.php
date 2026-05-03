<?php
require_once __DIR__ . '/../recursos/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class Modelo_excel {

    public function leerExcelProfesores(string $ruta): array {
        $hoja  = IOFactory::load($ruta)->getActiveSheet();
        $filas = $hoja->toArray(null, true, false, false);
        $profesores = [];

        foreach ($filas as $i => $fila) {
            if ($i === 0) continue; // cabecera

            $nombre    = trim($fila[1] ?? '');
            $categoria = trim($fila[2] ?? '');

            if ($nombre === '' || $categoria === '') continue;

            $profesores[] = ['nombre' => $nombre, 'categoria' => $categoria];
        }

        return $profesores;
    }

    public function leerExcelModulos(string $ruta): array {
        $hoja  = IOFactory::load($ruta)->getActiveSheet();
        $filas = $hoja->toArray(null, true, false, false);
        $modulos = [];

        foreach ($filas as $i => $fila) {
            if ($i === 0) continue; // cabecera
            if (empty($fila[2])) continue;

            $modulos[] = [
                'grado'         => $fila[0] ?? null,
                'curso'         => $fila[1] ?? null,
                'nombre_modulo' => $fila[2] ?? null,
                'horas'         => isset($fila[3]) ? (int)$fila[3] : null,
                'categoria'     => $fila[4] ?? null,
            ];
        }

        return $modulos;
    }
}
