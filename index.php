<?php
session_start();

if(!isset($_SESSION['usuario'])){
    require_once __DIR__ . "/vistas/login.php";
    exit();
}

require_once __DIR__ . "/controladores/Controlador_modulo.php";

$controlador = new Controlador_modulos();
$controlador->mostrarModulos();