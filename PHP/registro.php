<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "empresa";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$dni = $_POST['dni'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$fecha_nac = $_POST['fecha_nac'] ?? null;
$tfno = $_POST['tfno'] ?? '';

if (empty($dni) || empty($nombre)) {
    echo "<script>alert('Por favor llena los campos obligatorios'); window.location.href='../registro.html';</script>";
    exit();
}

// Verifica si el usuario ya existe
$stmt = $conn->prepare("SELECT dni FROM cliente WHERE dni = ?");
$stmt->bind_param("i", $dni);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('El cliente ya está registrado'); window.location.href='../registro.html';</script>";
    exit();
}

$stmt->close();

// Cifra la contraseña
//$hash = password_hash($password, PASSWORD_DEFAULT);

// Inserta el cliente
$stmt = $conn->prepare("INSERT INTO cliente (dni, nombre, fecha_nac, tfno) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $dni, $nombre, $fecha_nac, $tfno);

if ($stmt->execute()) {
    echo "<script>alert('Registro exitoso'); window.location.href='../index.html';</script>";
} else {
    echo "Error al registrar: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
