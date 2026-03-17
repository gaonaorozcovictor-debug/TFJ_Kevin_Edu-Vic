<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="estilos/style_login.css">
</head>
<body>

<form method="post">

    <input type="text" name="usuario" placeholder="Usuario"><br><br>

    <input type="submit" name="login" value="Login">

</form>

</body>
</html>

<?php

if(isset($_POST['login'])){
    $_SESSION['sesion']=true;
    header("Location: " . $_SERVER['PHP_SELF']);
}

?>