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

// SUMAR HORAS DE LOS MÓDULOS SELECCIONADOS
$horasSeleccionadas = 0;

if (isset($_POST['modulos'])) {
    foreach ($_POST['modulos'] as $h) {
        $horasSeleccionadas += intval($h);
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
        <option value="(PS)" <?php if($categoria=="(PS)") echo "selected"; ?>>Ana (PS)</option>
        <option value="(PT)" <?php if($categoria=="(PT)") echo "selected"; ?>>Juan (PT)</option>
        <option value="(PS)" <?php if($categoria=="(PS)") echo "selected"; ?>>Marta (PS)</option>
    </select>
</form>
</div>

<div class="section hours">
    Horas asignadas: <?php echo $horasSeleccionadas; ?> / <?php echo $horasTener; ?>
</div>

<div class="section">
<label>Módulos disponibles</label>

<div class="modules">

<form method="POST">

    <input type="hidden" name="profesor" value="<?php echo $categoria; ?>">

    <input type="checkbox" name="modulos[]" value="8"> DWES - 2º - <?php echo $categoria; ?> - DAW - 8H <br>
    <input type="checkbox" name="modulos[]" value="4"> Despliegue - 2º - <?php echo $categoria; ?> - DAW - 4H <br>
    <input type="checkbox" name="modulos[]" value="6"> Diseño - 2º - <?php echo $categoria; ?> - DAW - 6H <br>

    <br>
    <button type="submit">Asignar módulos</button>

</form>

</div>

</div>

</body>
</html>
