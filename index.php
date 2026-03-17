<?php

session_start();

if(isset($_SESSION['sesion']) && $_SESSION['sesion']){
    require_once "vistas/mostrarModulos.php";

}else{
    require_once "vistas/login.php";
}

?>
