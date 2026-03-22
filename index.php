<?php
session_start();

// EVITAR CACHÉ 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");


// SI NO HAY SESIÓN LOGIN
if (!isset($_SESSION['usuario'])) {

    require_once __DIR__ . "/modelos/Modelo_profesores.php";

    $modelo = new Modelo_profesores();
    $profesores = $modelo->obtenerProfesores();

    require __DIR__ . "/vistas/login.php";
    exit();
}


// SI HAY SESIÓN CONTROL DE VISTAS
require_once __DIR__ . "/controladores/Controlador_modulo.php";

$vista = $_GET['vista'] ?? '';
$rol = $_SESSION['rol'];

$controlador = new Controlador_modulos();

switch($vista){

    case 'admin':
        if($rol !== 'admin'){
            header("Location: index.php?vista=profesor");
            exit();
        }
        $controlador->mostrarModulos();
        break;

    case 'profesor':
        if($rol !== 'profesor'){
            header("Location: index.php?vista=admin");
            exit();
        }
        $controlador->mostrarModulos();
        break;

    case 'logout':
        session_unset();
        session_destroy();

        header("Location: index.php?vista=login");
        exit();

    default:
        if($rol === 'admin'){
            header("Location: index.php?vista=admin");
        } else {
            header("Location: index.php?vista=profesor");
        }
        exit();
}