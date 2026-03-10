<?php

// Datos de asignaturas
// todo dentro del mismo array 
$asignaturas = [
    'PS' => [
        ['nombre' => 'DWES', 'curso' => '2º', 'categoria' => '(PS)', 'grado' => 'DAW', 'horas' => '8H'],
        ['nombre' => 'Despliegue', 'curso' => '2º', 'categoria' => '(PS)', 'grado' => 'DAW', 'horas' => '4H'],
        ['nombre' => 'Diseño', 'curso' => '2º', 'categoria' => '(PS)', 'grado' => 'DAW', 'horas' => '6H'],
    ],
    'PT' => [
        ['nombre' => 'DWEC', 'curso' => '2º', 'categoria' => '(PT)', 'grado' => 'DAW', 'horas' => '10H'],
        ['nombre' => 'Sistemas', 'curso' => '2º', 'categoria' => '(PT)', 'grado' => 'DAW', 'horas' => '6H'],
        ['nombre' => 'Bases de Datos', 'curso' => '2º', 'categoria' => '(PT)', 'grado' => 'DAW', 'horas' => '4H'],
    ]
];





// Procesar profesor seleccionado
$nombreProf = "";
$categoria = "PS";

if (isset($_POST['profesor'])) {
    list($nombreProf, $categoria) = explode("-", $_POST['profesor']);
    $nombreProf = " para " . $nombreProf;
}





// Calcular horas totales
$horasTotales = 0;

if (isset($_POST['asignarM']) && isset($_POST['horas'])) {
    foreach ($_POST['horas'] as $hora) {
        $horasTotales += intval($hora);
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignación de módulos</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h1>Asignación de módulos</h1>

<div class="section">
<label>Profesor</label>
<form method="POST">
    <select name="profesor" onchange="this.form.submit()">
        <option>--Selecciona una opcion--</option>
        <option value="Ana-PS">Ana (PS)</option>
        <option value="Juan-PT">Juan (PT)</option>
        <option value="Marta-PS">Marta (PS)</option>
    </select>
</form>
</div>

<div class="section hours">
    Horas asignadas: <?php echo $horasTotales; ?> / 20
</div>

<div class="section">
<label>Módulos disponibles <?php echo $nombreProf; ?></label>

<div class="modules">

<form method="POST">

    <?php imprimirAsignaturas($asignaturas[$categoria]); ?>

    <button type="submit" name="asignarM">Asignar módulos</button>
</form>

</div>

</div>

</body>
</html>

<?php

function imprimirAsignaturas($lista)
{
    foreach ($lista as $datos) {

        // Extraer número de horas (ej: "10H" → 10)
        // lo import 
        $horas = intval($datos['horas']);

        echo "<label>";
        echo "<input type='checkbox' name='horas[]' value='$horas'> ";

        // Mostrar datos
        echo "{$datos['nombre']} - {$datos['curso']} - {$datos['categoria']} - {$datos['grado']} - {$datos['horas']}";

        echo "</label><br>";
    }
}

?>
