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

<style>

body{
    font-family: Arial, sans-serif;
    background-color:#f4f6f9;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.container{
    background:white;
    width:500px;
    padding:30px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

h1{
    text-align:center;
    margin-bottom:25px;
}

.section{
    margin-bottom:20px;
}

label{
    font-weight:bold;
}

select{
    width:100%;
    padding:8px;
    margin-top:5px;
}

.hours{
    background:#eef2ff;
    padding:10px;
    border-radius:6px;
    font-weight:bold;
}

.modules{
    border:1px solid #ddd;
    border-radius:6px;
    padding:10px;
    max-height:200px;
    overflow-y:auto;
}

.module{
    margin-bottom:8px;
}

button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:6px;
    background:#4CAF50;
    color:white;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    background:#45a049;
}

</style>
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
