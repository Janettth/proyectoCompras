<?php
session_start();
include("PHP/conexion.php");

if (!isset($_SESSION['usuario_dni'])) {
    header("Location: ../index.html");
    exit();
}

$dni_cliente = $_SESSION['usuario_dni'];


$sql = "SELECT p.codigo, p.nombre, p.precio, pr.nombre AS proveedor
        FROM producto p
        INNER JOIN proveedor pr ON p.nif_proveedor = pr.nif";
$result = $conn->query($sql);


$sqlCompras = "SELECT pr.nombre AS producto, pr.precio, pv.nombre AS proveedor
               FROM compras c
               INNER JOIN producto pr ON c.codigo_producto = pr.codigo
               INNER JOIN proveedor pv ON pr.nif_proveedor = pv.nif
               WHERE c.dni_cliente = ?";

$stmt = $conn->prepare($sqlCompras);
$stmt->bind_param("i", $dni_cliente);
$stmt->execute();
$resultCompras = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="CSS/estilo_compras.css">
</head>
<body>

    <h1 id="titulo_bienvenida" >Bienvenido a Caramelia, <?php echo $_SESSION['usuario_nombre']; ?> </h1>

    <div id="contenido">

        <div id="cont_productos">
            <h2 id="titulo_productos">Productos Disponibles</h2>

            <div id="contenedor_productos">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <div class='plantilla_producto'>
                            <p class='nombre_producto'>{$row['nombre']}</p>
                            <p class='precio'>$ {$row['precio']}</p>
                            <p class='codigo_producto'>Código: {$row['codigo']}</p>
                            <p class='proveedor'>{$row['proveedor']}</p>
                            
                            <form action='PHP/comprar.php' method='POST'>
                                <input type='hidden' name='codigo_producto' value='{$row['codigo']}'>
                                <button class='btn_estilo' type='submit'>Comprar</button>
                            </form>
                        </div>
                        ";
                    }
                } else {
                    echo "<p>No hay productos disponibles</p>";
                }
                ?>
            </div>
    
        </div>

        <div id="cont_compras">
            <h2>Tus compras</h2>
            <div id="compras">
                <?php
                if ($resultCompras->num_rows > 0) {
                    while ($compra = $resultCompras->fetch_assoc()) {
                        echo "<p>{$compra['producto']} <br> $ {$compra['precio']}</p>";
                    }
                } else {
                    echo "<p>No has comprado nada todavía.</p>";
                }
                ?>

                
            </div>

            <form action="PHP/logout.php" method="post" style="display:inline;">
                    <button class='btn_salir' type="submit">Cerrar sesión</button>
            </form>
        </div>

    </div>
</body>
</html>
