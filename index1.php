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
    $datos=explode("-", $_POST['profesor']);
    $nombreProf= " para ". $datos[0];
    $categoria = $datos[1];
}else{
    $nombreProf= " ";
    $categoria="PS";
}

$horasTotales = 0;

if(isset($_POST['asignarM'])){
    $horas = $_POST['horas'];
    foreach($horas as $hora){
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
<label>Módulos disponibles <?php echo $nombreProf; ?></label>

<div class="modules">

<form method="POST">

    <?php if($categoria==="PS"){imprimirAsignaturas($asignaturas_PS);}else{imprimirAsignaturas($asignaturas_PT);} ?><br>

    <button type="submit" name="asignarM" >Asignar módulos</button>
</form>

</div>

</div>

</body>
</html>
<?php

function imprimirAsignaturas($asignaturas){
    foreach($asignaturas as $datos){

        $horas=substr($datos['horas'],0,1);

        echo "<input type='checkbox' name='horas[]' value='".$horas."'>";
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