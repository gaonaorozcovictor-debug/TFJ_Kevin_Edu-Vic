<?php
session_start();

// Si hay sesión activa
if(isset($_SESSION['sesion']) && $_SESSION['sesion']){
    require_once __DIR__ . "/controladores/Controlador_modulo.php";
    $controlador = new Controlador_modulos();
    $controlador->mostrarModulos();

} 
else {
    // Si no hay sesión, mostrar login (puede tener su propio controlador si quieres)
    require_once __DIR__ . "/vistas/login.php";
}