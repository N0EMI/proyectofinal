<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "farma";

// Crear conexión
$conexion = mysqli_connect($server, $user, $pass, $db);

// Verificar conexión
if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
} else {
    //echo "Conexión exitosa";
}

?>