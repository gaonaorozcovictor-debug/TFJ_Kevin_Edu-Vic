<?php

require_once __DIR__ . '/../recursos/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class Modelo_excel {
public function leerExcelProfesores($rutaArchivo){

    $spreadsheet = IOFactory::load($rutaArchivo);
    $hoja = $spreadsheet->getActiveSheet();

    $datos = $hoja->toArray(null, true, false, false);

    $profesores = [];

    foreach($datos as $index => $fila){

        if($index === 0) continue;

        $nombre = $fila[1] ?? null;
        $categoria = $fila[2] ?? null;

        if(empty($nombre) || empty($categoria)) continue;

        $profesores[] = [
            'nombre' => trim($nombre),
            'categoria' => trim($categoria)
        ];
    }

    return $profesores;
}

    public function leerExcelModulos($rutaArchivo){

        $spreadsheet = IOFactory::load($rutaArchivo);
        $hoja = $spreadsheet->getActiveSheet();

        $datos = $hoja->toArray(null, true, false, false);

        $modulos = [];

        foreach($datos as $index => $fila){

            if($index === 0) continue;

            if(empty($fila[2])) continue;

            $modulos[] = [
                'grado' => $fila[0] ?? null,
                'curso' => $fila[1] ?? null,
                'nombre_modulo' => $fila[2] ?? null,
                'horas' => $fila[3] ?? null,
                'categoria' => $fila[4] ?? null
            ];
        }

        return $modulos;
    }
}