<?php
session_start();

// Redirigir al login si no hay sesión iniciada
if(!isset($_SESSION['Usuario'])){
    header("Location: ../index.php");
    exit();
}

// Variables de sesión
$nomIntroducido = $_SESSION['Usuario'];
$contraIntroducida = $_SESSION['contrasena'] ?? '';

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

// Selección de profesor
if(isset($_POST['profesor'])){
    $datos=explode("-", $_POST['profesor']);
    $nombreProf= $datos[0];
    $categoria = $datos[1];
}else{
    $nombreProf= " ";
    $categoria="PS";
}

// Sumar horas seleccionadas
$horasTotales = 0;
if(isset($_POST['asignarM']) && isset($_POST['horas'])){
    foreach($_POST['horas'] as $hora){
        $horasTotales += $hora;
    }
}

// Función para imprimir asignaturas
function imprimirAsignaturas($asignaturas){
    foreach($asignaturas as $datos){
        $horas = intval($datos['horas']); // <-- corregido para 10H
        echo "<div class='module'>";
        echo "<input type='checkbox' name='horas[]' value='".$horas."'> ";
        foreach($datos as $valor){
            if(str_contains($valor,"H")){
                echo $valor;
            }else{
                echo $valor ." - ";
            }
        }
        echo "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Asignación de módulos</title>
<link rel="stylesheet" href="style_index.css">
</head>
<body>

<div class="container">

    <!-- Botón de cerrar sesión -->
    <form action="../Core/cerrar_sesion.php" method="post" class="logout-form">
        <button type="submit" class="logout-btn">Cerrar sesión</button>
    </form>

    <h1>Asignación de módulos</h1>

    <p>Bienvenido, <?php echo htmlspecialchars($nomIntroducido); ?>!</p>

    <!-- Selección de profesor -->
    <div class="section">
        <label>Profesor</label>
        <form method="POST">
            <input type="hidden" name="Usuario" value="<?php echo htmlspecialchars($nomIntroducido); ?>">
            <input type="hidden" name="contrasena" value="<?php echo htmlspecialchars($contraIntroducida); ?>">

            <select name="profesor" onchange="this.form.submit()">
                <option>--Selecciona una opción--</option>
                <option value="Ana-PS" <?php if($nombreProf=="Ana") echo "selected"; ?>>Ana (PS)</option>
                <option value="Juan-PT" <?php if($nombreProf=="Juan") echo "selected"; ?>>Juan (PT)</option>
                <option value="Marta-PS" <?php if($nombreProf=="Marta") echo "selected"; ?>>Marta (PS)</option>
            </select>
        </form>
    </div>

    <!-- Horas asignadas -->
    <div class="section hours">
        Horas asignadas: <?php echo $horasTotales; ?> / 20
    </div>

    <!-- Módulos disponibles -->
    <div class="section">
        <label>Módulos disponibles <?php echo $nombreProf; ?></label>
        <div class="modules">
            <form method="POST">
                <input type="hidden" name="Usuario" value="<?php echo htmlspecialchars($nomIntroducido); ?>">
                <input type="hidden" name="contrasena" value="<?php echo htmlspecialchars($contraIntroducida); ?>">
                <input type="hidden" name="profesor" value="<?php echo htmlspecialchars($_POST['profesor'] ?? ''); ?>">

                <?php 
                    if($categoria==="PS"){
                        imprimirAsignaturas($asignaturas_PS);
                    }else{
                        imprimirAsignaturas($asignaturas_PT);
                    }
                ?>
                <br>
                <button type="submit" name="asignarM">Asignar módulos</button>
            </form>
        </div>
    </div>

</div>

</body>
</html>