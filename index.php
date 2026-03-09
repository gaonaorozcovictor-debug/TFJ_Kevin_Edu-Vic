<?php

// Asignaturas para profesores PS
$asignaturas_PS = [
    ['nombre' => 'DWES', 'curso' => '2º', 'categoria' => '(PS)', 'grado' => 'DAW', 'horas' => '8H'],
    ['nombre' => 'Despliegue', 'curso' => '2º', 'categoria' => '(PS)', 'grado' => 'DAW', 'horas' => '4H'],
    ['nombre' => 'Diseño', 'curso' => '2º', 'categoria' => '(PS)', 'grado' => 'DAW', 'horas' => '6H'],
];

// Asignaturas para profesores PT
$asignaturas_PT = [
    ['nombre' => 'DWEC', 'curso' => '2º', 'categoria' => '(PT)', 'grado' => 'DAW', 'horas' => '10H'],
    ['nombre' => 'Sistemas', 'curso' => '2º', 'categoria' => '(PT)', 'grado' => 'DAW', 'horas' => '6H'],
    ['nombre' => 'Bases de Datos', 'curso' => '2º', 'categoria' => '(PT)', 'grado' => 'DAW', 'horas' => '4H'],
];


if(isset($_POST['profesor'])){
    $categoria = $_POST['profesor'];
}else{
    $categoria="(PS)";
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
        <option value="PS">Ana (PS)</option>
        <option value="PT">Juan (PT)</option>
        <option value="PS">Marta (PS)</option>
    </select>
</form>
</div>

<div class="section hours">
Horas asignadas: 12 / 20
</div>

<div class="section">
<label>Módulos disponibles</label>

<div class="modules">

<form method="POST">

   <?php if($categoria==="PS"){imprimirAsignaturas($asignaturas_PS);}else{imprimirAsignaturas($asignaturas_PT);} ?>

</form>

</div><br>

<button>Asignar módulos</button>

</div>

</body>
</html>
<?php

function imprimirAsignaturas($asignaturas){
    foreach($asignaturas as $datos){
        echo "<input type='checkbox'>";
        foreach($datos as $valor){
            if(str_contains($valor,"H")){
                echo $valor;
            }else{
                echo $valor ." - ";
            }
        }
        echo "<br>";
    }
}

?>