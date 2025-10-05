<?php
session_start();

$servername = "localhost"; 
$username   = "root";
$password   = "";
$dbname     = "empresa";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$nombre = $_POST['nombre'] ?? '';
$dni    = $_POST['dni'] ?? '';

if (empty($nombre) || empty($dni)) {
    echo "<script>alert('Por favor completa todos los campos'); window.location.href='../index.html';</script>";
    exit();
}

$sql = "SELECT * FROM cliente WHERE dni = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dni); // i = integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    // Validar nombre
    if (strcasecmp($nombre, $usuario['nombre']) == 0) {
        $_SESSION['usuario_dni'] = $usuario['dni'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        header("Location: ../producto.php");
        exit();
    } else {
        echo "<script>alert('Nombre incorrecto'); window.location.href='../index.html';</script>";
    }
} else {
    echo "<script>alert('DNI no encontrado'); window.location.href='../index.html';</script>";
}


$stmt->close();
$conn->close();
?>
