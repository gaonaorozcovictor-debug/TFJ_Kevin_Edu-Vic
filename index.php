<?php
session_start();

if(isset($_POST['Usuario']) && isset($_POST['contrasena'])){

    $_SESSION['Usuario'] = $_POST['Usuario'];
    $_SESSION['contrasena'] = $_POST['contrasena'];

    header("Location: Vistas/pagina_principal.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="./Vistas/style_login.css">
</head>
<body>

<form method="post">
<input type="text" name="Usuario" placeholder="Usuario">
<br><br>
<input type="password" name="contrasena" placeholder="Contraseña">
<br><br>
<button type="submit">Login</button>
</form>

</body>
</html>