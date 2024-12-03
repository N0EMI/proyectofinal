<?php

include 'conexion.php'; 

// Capturar datos del formulario
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
session_start();
$_SESSION['Correo'] = $correo;

// Verificar si el correo y la contraseña coinciden con un administrador
$consulta_admin = "SELECT * FROM administrador WHERE correo = ? AND contrasena = ?";
$stmt = $conexion->prepare($consulta_admin);
$stmt->bind_param("ss", $correo, $contrasena);
$stmt->execute();
$resultado_admin = $stmt->get_result();

if ($resultado_admin && $resultado_admin->num_rows > 0) {
    // Usuario administrador autenticado
    header("Location: administrador/admi.php");
    exit();
}

// Verificar si el correo y la contraseña coinciden con un cliente
$consulta_cliente = "SELECT * FROM cliente WHERE correocliente = ? AND contrasenacliente = ?";
$stmt = $conexion->prepare($consulta_cliente);
$stmt->bind_param("ss", $correo, $contrasena);
$stmt->execute();
$resultado_cliente = $stmt->get_result();

if ($resultado_cliente && $resultado_cliente->num_rows > 0) {
    $cliente = $resultado_cliente->fetch_assoc();
    $_SESSION['Correo'] = $correo; // Guardar el correo en la sesión
    $_SESSION['idcliente'] = $cliente['idcliente']; // Guardar el ID del cliente en la sesión
    header("Location: cliente/index_cliente.php");
    exit();
}

// Si no se encuentra ninguna coincidencia
$stmt->close();
$conexion->close();
header("Location: cliente/inicia_cliente.php?error=1"); // Redirigir con mensaje de error
exit();
?>
