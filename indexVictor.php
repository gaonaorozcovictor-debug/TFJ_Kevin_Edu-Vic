<?php

// Datos de asignaturas
/*todo dentro del mismo array 
es mas comodo y facil de escalar */
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
// el value no es ps o pt ya es el nombrte del profe y lo que es 
$nombreProf = "";
$categoria = "PS"; // nos muestra el PS por defecto al entrar para que no salga nada vacio 

if (isset($_POST['profesor'])) {
    list($nombreProf, $categoria) = explode("-", $_POST['profesor']); // obtenemos mediante post su nombre y categoria 
    //Ana-PS el xplode es para que pueda leeer los datos que hay abajo 
    // <option value="Ana-PS">Ana (PS)</option>

    //$nombreProf = " para " . $nombreProf;
}





// Calcular horas totales igual que los carritos de vicky 
$horasTotales = 0;

if (isset($_POST['asignarM']) && isset($_POST['horas'])) {
    foreach ($_POST['horas'] as $hora) {
        $horasTotales += $hora;
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
<label>Módulos disponibles para  <?php echo $nombreProf; ?></label>

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

function imprimirAsignaturas($lista){
    foreach ($lista as $datos) {

        // Extraer número de horas (ej: "10H" → 10)
        // lo import 
        $horas = intval($datos['horas']); // usamo el intaval para que se aun numero y no 8H 

        echo "<label>";
        echo "<input type='checkbox' name='horas[]' value='$horas'> ";

        // Mostrar datos del array apuntes tema 2 y 3 
        echo "{$datos['nombre']} - {$datos['curso']} - {$datos['categoria']} - {$datos['grado']} - {$datos['horas']}";

        echo "</label><br>";
    }
}

?>