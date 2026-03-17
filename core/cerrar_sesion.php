<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// Cerrar sesión y redirigir
session_destroy();
header("Location: ../index.php");
exit();