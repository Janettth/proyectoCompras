<?php
session_start();
include("conexion.php");

// Validamos sesión
if (!isset($_SESSION['usuario_dni'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_producto = $_POST['codigo_producto'];
    $dni_cliente = $_SESSION['usuario_dni']; // lo tomamos de la sesión

    // Insertar en la tabla compras
    $sql = "INSERT INTO compras (dni_cliente, codigo_producto) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $dni_cliente, $codigo_producto);

    if ($stmt->execute()) {
        echo "<script>alert('Compra exitosa'); window.location.href='../producto.php';</script>";
    } else {
        echo "<script>alert('Error al registrar la compra'); window.location.href='../producto.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
