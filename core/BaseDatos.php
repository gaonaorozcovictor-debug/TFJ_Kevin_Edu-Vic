<?php

// Usen los datos del correo que nos pasó,
// pero al subir al github, borren los otros
// y coloquen esto ahí, por seguridad no es
// bueno subirlo al github pues es público.

/*

    private $host = "Aqui_va_la_ip";
    private $user = "Aqui_va_el_usuario";
    private $pass = "Aqui_va_la_contraseña";
    private $db = "Aqui_va_el_nombre_de_la_bbdd";

*/

class BaseDatos {
    private $host = "Aqui_va_la_ip";
    private $user = "Aqui_va_el_usuario";
    private $pass = "Aqui_va_la_contraseña";
    private $db = "Aqui_va_el_nombre_de_la_bbdd";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }
}
?>