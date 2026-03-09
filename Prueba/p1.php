<?php
// Si se envía el formulario, guardamos la categoría seleccionada
if (isset($_POST['profesor'])) {
    $categoria = $_POST['profesor'];
} else {
    $categoria = "(PS)";
}

// LAS HORAS QUE TIENE CADA UNO 
$horas = [
    "(PS)" => 20,
    "(PT)" => 16
];

$horasTener = $horas[$categoria];

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

        
<!-- SI TENEMOS UN VALOR PS CARGA EL FORMULARIO DE ARRIBA 
 Y SI ES ASI NOS IMPREME selected Y NOS LO SACA DE FORMA MAS LIMPIA  -->
        <option value="(PS)" <?php if($categoria=="(PS)") echo "selected"; ?>>Ana (PS)</option>
        <option value="(PT)" <?php if($categoria=="(PT)") echo "selected"; ?>>Juan (PT)</option>
        <option value="(PS)" <?php if($categoria=="(PS)") echo "selected"; ?>>Marta (PS)</option>

    </select>
</form>
</div>

<!--meter un onchage a las horas  -->

<div class="section hours">
    Horas asignadas: <?php echo $horasTener; ?> / 20
</div>


<div class="section">
<label>Módulos disponibles</label>

<div class="modules">

<form method="POST">

    <input type="checkbox"> DWES - 2º - <?php echo $categoria; ?> - DAW - 8H <br>
    <input type="checkbox"> Despliegue - 2º - <?php echo $categoria; ?> - DAW - 4H <br>
    <input type="checkbox"> Diseño - 2º - <?php echo $categoria; ?> - DAW - 6H <br>

</form>

</div><br>
<!-- Tu entras y te salen todas las asiganaturas y solo se filtran cuando escoges al profesor   -->


<button>Asignar módulos</button>

</div>

</body>
</html>
