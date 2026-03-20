<?php
require_once __DIR__ . '/../recursos/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class Modelo_excel {

    public function leerExcel($rutaArchivo){

        if(!file_exists($rutaArchivo)){
            
            throw new Exception("El archivo no existe");
        }

        $spreadsheet = IOFactory::load($rutaArchivo);
        $hoja = $spreadsheet->getActiveSheet();

        return $hoja->toArray();

    }
}