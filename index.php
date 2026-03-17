<?php
session_start();

// Si hay sesión activa, mostrar módulos
if(isset($_SESSION['sesion']) && $_SESSION['sesion']){
    require_once "vistas/mostrarModulos.php";
} else {
    // Si no hay sesión, mostrar login
    require_once "vistas/login.php";
}