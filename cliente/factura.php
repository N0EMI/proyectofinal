<?php
session_start(); // Iniciar sesión para acceder a los datos del cliente y el carrito

// Verificar si hay productos en el carrito
$productos = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$total = 0;

if (isset($_SESSION['cliente'])) {
    $cliente = $_SESSION['cliente'];
} else {
    // Redirigir si no hay datos de cliente
    header("Location: finalizar_compra.php");
    exit;
}

// Calcular el total
foreach ($productos as $producto) {
    $total += $producto['precio']; // Sumar al total
}

// Eliminar el carrito al volver
if (isset($_POST['volver'])) {
    unset($_SESSION['carrito']); // Elimina el carrito de la sesión
    header("Location: index.php"); // Redirige a la página principal
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <link rel="stylesheet" href="css/factura.css">
    <style>

    </style>
</head>
<body>


    <main>
        <div class="container">
        <header>
        <img src="../img/logo1.png" alt="Logo de la Farmacia">
        <p>AV. Las Americas s/n</p>
        <p>Teléfono: (052) 456-7890</p>
        <p>Correo: DropaxOficial@gmail.com</p>
        <p>Ruc: 20778944567</p>
    </header>
            <h2>Factura N° 38957</h2>
            <h3>Datos del Cliente</h3>
            <p><strong>Nombre:</strong> <?php echo $cliente['nombre']; ?></p>
            <!--<p><strong>Correo:</strong> <?php echo $cliente['correo']; ?></p>-->
            <p><strong>Teléfono:</strong> <?php echo $cliente['telefono']; ?></p>
            <p><strong>Fecha:</strong> 03-12-2024</p>

            <h3>Productos Comprados</h3>
            <table>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                </tr>
                <?php if (count($productos) > 0): ?>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo $producto['nombre']; ?></td>
                            <td> S/ <?php echo isset($producto['precio']) ? number_format($producto['precio'], 2) : '0.00'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total">
                        <td><strong>Total</strong></td>
                        <td><strong>S/ <?php echo number_format($total, 2); ?></strong></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="2">No hay productos en el carrito.</td>
                    </tr>
                <?php endif; ?>
            </table>
            <p>Gracias por su compra!</p>
        </div>
        <a href="../index.php" class="btn">Volver a Inicio</a>
    </main>
</body>
</html>
