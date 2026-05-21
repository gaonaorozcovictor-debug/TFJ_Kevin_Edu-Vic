<?php
require_once __DIR__ . '/../Recursos/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class Modelo_excel {

    public function leerExcel($rutaArchivo){
        $datos = [];

        $spreadsheet = IOFactory::load($rutaArchivo);
        $hoja = $spreadsheet->getActiveSheet();

        foreach ($hoja->getRowIterator() as $fila){
            $filaDatos = [];

            $cellIterator = $fila->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            foreach ($cellIterator as $celda){
                $filaDatos[] = $celda->getCalculatedValue();
            }

            $datos[] = $filaDatos;
        }

        return $datos;
    }
}