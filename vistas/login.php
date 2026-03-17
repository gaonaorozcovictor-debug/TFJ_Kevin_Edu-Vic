<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// Procesar login
if(isset($_POST['login'])){
    $_SESSION['sesion'] = true; // Aquí podrías guardar también el usuario
    header("Location: ./index.php"); // Redirige a index para mostrar módulos
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="estilos/style_login.css">
</head>
<body>

<h1>Login</h1>

<form method="post">
    <input type="text" name="usuario" placeholder="Usuario" required><br><br>
    <input type="submit" name="login" value="Login">
</form>

</body>
</html>