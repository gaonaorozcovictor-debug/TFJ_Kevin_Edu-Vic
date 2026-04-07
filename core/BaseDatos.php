<?php
class BaseDatos {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "tfg_instituto";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }
}
?>