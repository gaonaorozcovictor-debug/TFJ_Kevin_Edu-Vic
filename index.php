<?php
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
        <option value="(PS)">Ana (PS)</option>
        <option value="(PT)">Juan (PT)</option>
        <option value="(PS)">Marta (PS)</option>
    </select>
<form>
</div>

<div class="section hours">
Horas asignadas: 12 / 20
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

<button>Asignar módulos</button>

</div>

</body>
</html>
