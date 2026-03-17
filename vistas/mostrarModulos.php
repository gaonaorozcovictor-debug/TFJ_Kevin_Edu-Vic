<?php

// Redirigir al login si no hay sesión iniciada
if(!isset($_SESSION['sesion'])){
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Asignación de módulos</title>
<!--<link rel="stylesheet" href="style_index.css">-->
</head>
<body>

    <!-- Botón de cerrar sesión -->
    <form action="core/cerrar_sesion.php" method="post" class="logout-form">
        <button type="submit" class="logout-btn">Cerrar sesión</button>
    </form>

    <h1>Módulos de profesores</h1>

    <h2>Subir archivo Excel</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="archivo_excel" accept=".xls,.xlsx" required>
        <button type="submit">Subir</button>
    </form>

</body>
</html>
<?php
// Cargar autoload de Composer
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if(isset($_FILES['archivo_excel'])) {
    $archivoTmp = $_FILES['archivo_excel']['tmp_name'];

    try {
        // Cargar el archivo Excel
        $spreadsheet = IOFactory::load($archivoTmp);

        // Obtener la hoja activa (la primera por defecto)
        $hoja = $spreadsheet->getActiveSheet();

        echo "<h2>Datos del Excel:</h2>";
        echo "<table border='1' cellpadding='5'>";

        // Recorrer filas
        foreach ($hoja->getRowIterator() as $fila) {
            echo "<tr>";

            // Recorrer celdas
            $celdas = $fila->getCellIterator();

            foreach ($celdas as $celda) {
                $valor = $celda->getValue();
                echo "<td>" . htmlspecialchars($valor) . "</td>";
            }

            echo "</tr>";
        }

        echo "</table>";

    } catch (Exception $e) {
        echo "Error al leer el archivo: " . $e->getMessage();
    }

}
?>