<?php
$host = "localhost";
$user = "root";      // cámbialo si tienes otro usuario
$pass = "";          // tu contraseña si aplica
$db   = "empresa";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>