<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// Si no hay sesión activa, redirige a login
if(!isset($_SESSION['sesion'])){
    header("Location: ../index.php");
    exit();
}

// Autoload de Composer para PhpSpreadsheet
require_once __DIR__ . "/../Recursos/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Asignación de módulos</title>
<link rel="stylesheet" href="estilos/style_index.css">
</head>
<body>

<h1>Módulos de profesores</h1>

<!-- Botón de Cerrar Sesión -->
<form action="./core/cerrar_sesion.php" method="post">
    <button type="submit">Cerrar sesión</button>
</form>

<!-- Subir archivo Excel -->
<h2>Subir archivo Excel</h2>
<form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="archivo_excel" accept=".xls,.xlsx" required>
    <button type="submit">Subir</button>
</form>

<?php
if(isset($_FILES['archivo_excel'])){
    $archivoTmp = $_FILES['archivo_excel']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($archivoTmp);
        $hoja = $spreadsheet->getActiveSheet();

        echo "<h2>Datos del Excel:</h2>";
        echo "<table border='1' cellpadding='5'>";

        foreach ($hoja->getRowIterator() as $fila){
            echo "<tr>";
            foreach ($fila->getCellIterator() as $celda){
                echo "<td>" . htmlspecialchars($celda->getValue()) . "</td>";
            }
            echo "</tr>";
        }

        echo "</table>";

    } catch (Exception $e){
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}
?>
</body>
</html>