<?php
require_once __DIR__ . '/../modelos/Modelo_profesores.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$modelo = new Modelo_profesores();


// LOGIN ADMIN
if(isset($_POST['login_admin'])){

    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    if($usuario === "admin" && $password === "1234"){

        $_SESSION['usuario'] = 'admin';
        $_SESSION['rol'] = 'admin';
        $_SESSION['nombre'] = 'Administrador';

        header("Location: ../index.php?vista=admin");
        exit();

    } else {
        $_SESSION['error'] = "Credenciales incorrectas";
        header("Location: ../index.php?vista=login");
        exit();
    }
}


// LOGIN PROFESOR
if(isset($_POST['profesor_id'])){

    $profesor_id = $_POST['profesor_id'];

    if(empty($profesor_id)){
        $_SESSION['error'] = "Selecciona un profesor";
        header("Location: ../index.php?vista=login");
        exit();
    }

    $profesor = $modelo->obtenerProfesorPorId($profesor_id);

    if(!$profesor){
        $_SESSION['error'] = "Profesor no encontrado";
        header("Location: ../index.php?vista=login");
        exit();
    }

    $_SESSION['usuario'] = $profesor_id;
    $_SESSION['rol'] = 'profesor';
    $_SESSION['profesor_id'] = $profesor_id;
    $_SESSION['nombre'] = $profesor['nombre'];

    header("Location: ../index.php?vista=profesor");
    exit();
}