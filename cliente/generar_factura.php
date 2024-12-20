<?php
session_start();
include '../conexion.php';

// Verificar parámetros requeridos
if (!isset($_GET['idpedido']) || !isset($_SESSION['idcliente'])) {
    echo "No se pudo cargar la factura. Faltan parámetros.";
    exit;
}

$idpedido = $_GET['idpedido'];
$idcliente = $_SESSION['idcliente'];

// Obtener datos del pedido
$sql_pedido = "SELECT p.fecha, p.direccion_envio, p.metodo_pago, p.total_pago, p.descuento, c.nombrecliente, c.apellidocliente, c.correocliente, c.telefono 
               FROM pedido p 
               JOIN cliente c ON p.idcliente = c.idcliente 
               WHERE p.idpedido = ? AND p.idcliente = ?";
$stmt_pedido = $conexion->prepare($sql_pedido);
$stmt_pedido->bind_param("ii", $idpedido, $idcliente);
$stmt_pedido->execute();
$result_pedido = $stmt_pedido->get_result();

if ($result_pedido->num_rows === 0) {
    echo "No se encontró el pedido.";
    exit;
}

$pedido = $result_pedido->fetch_assoc();

// Obtener productos del pedido
$sql_productos = "SELECT p.nombre, ph.cantidad, p.precio, ph.subtotal 
                  FROM pedido_has_producto ph 
                  JOIN producto p ON ph.idproducto = p.idproducto 
                  WHERE ph.idpedido = ?";
$stmt_productos = $conexion->prepare($sql_productos);
$stmt_productos->bind_param("i", $idpedido);
$stmt_productos->execute();
$result_productos = $stmt_productos->get_result();

$productos = [];
while ($row = $result_productos->fetch_assoc()) {
    $productos[] = $row;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <link rel="stylesheet" href="css/generar_factura.css">

    <style>

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="../img/logo1.png" alt="Logo" class="logo">
            <p>AV. Las Americas s/n</p>
            <p>Teléfono: (052) 456-7890</p>
            <p>Correo: DropaxOficial@gmail.com</p>
            <p>Ruc: 20778944567</p>
            <h2>Factura N° 38958</h2>
            <p><strong>Fecha:</strong> <?php echo date("d-m-Y", strtotime($pedido['fecha'])); ?></p>
        </div>
        
        <div class="datos-cliente">
            <h3>Datos del Cliente</h3>
            <p><strong>Nombre:</strong> <?php echo $pedido['nombrecliente'] . ' ' . $pedido['apellidocliente']; ?></p>
            <!--<p><strong>Correo:</strong> <?php echo $pedido['correocliente']; ?></p>-->
            <p><strong>Teléfono:</strong> <?php echo $pedido['telefono']; ?></p>
            <p><strong>Dirección de Envío:</strong> <?php echo $pedido['direccion_envio']; ?></p>
        </div>

        <div class="tabla-productos">
            <h3>Productos Comprados</h3>
            <table>
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Descripción</th>
                        <th>Precio Unitario</th>
                        <th>Precio Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo $producto['cantidad']; ?></td>
                        <td><?php echo $producto['nombre']; ?></td>
                        <td>S/ <?php echo number_format($producto['precio'], 2); ?></td>
                        <td>S/ <?php echo number_format($producto['subtotal'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="total">
            <p><strong>Descuento:</strong> S/ <?php echo number_format($pedido['descuento'], 2); ?></p>
            <h3><strong>Total a Pagar:</strong> S/ <?php echo number_format($pedido['total_pago'], 2); ?></h3>
        </div>
    </div>
    <a href="../cliente/index_cliente.php" class="btn">Volver a Inicio</a>
</body>
</html>
