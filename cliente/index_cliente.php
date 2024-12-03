<?php
// Incluir el archivo de conexión a la base de datos
include '../conexion.php';
session_start(); // Asegúrate de que la sesión esté iniciad

// Inicializar variables
$mensaje = '';
$tipo_alerta = '';
$cliente = null; // Inicializar variable para el cliente

// Consulta para obtener categorías
$categorias = [];
$sql_categoria = "SELECT * FROM categoria";
$result = $conexion->query($sql_categoria);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
}

// Consultar los datos del cliente que ha iniciado sesión
if (isset($_SESSION['idcliente'])) {
    $idcliente = $_SESSION['idcliente'];
    $sql_cliente = "SELECT * FROM cliente WHERE idcliente = ?";
    $stmt = $conexion->prepare($sql_cliente);
    $stmt->bind_param("i", $idcliente);
    $stmt->execute();
    $result_cliente = $stmt->get_result();

    if ($result_cliente->num_rows > 0) {
        $cliente = $result_cliente->fetch_assoc(); // Obtener datos del cliente
    } else {
        $mensaje = "No se encontraron datos del cliente.";
        $tipo_alerta = "error";
    }
}

// Cerrar la conexión
$conexion->close();
?>

<?php if (!empty($mensaje)): ?>
    <div class="alert <?php echo $tipo_alerta; ?>" id="alerta">
        <span class="closebtn" onclick="cerrarAlerta()">&times;</span> 
        <?php echo $mensaje; ?>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal del Cliente</title>
    <link rel="stylesheet" href="css/index_cliente.css">
    <link rel="stylesheet" href="css/boton.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
           /* Estilo del modal */
    .modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-10%, -100%);
        z-index: 1000;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 20px;
        max-width: 400px;
        width: 100%;
        height: auto;
    }
    .modal-content {
        text-align: center;
        border: none;
    }
    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
        color: #888;
    }
    .close:hover {
        color: #000;
    }
    h3 {
        margin-bottom: 15px;
        font-family: 'Arial', sans-serif;
        color: #333;
    }
    .styled-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        text-align: center;
        margin: 10px 0;
        font-family: 'Arial', sans-serif;
        transition: background-color 0.3s ease;
    }
    .styled-button i {
        font-size: 18px;
    }
    .styled-button:hover {
        background-color: #0056b3;
    }
    .login-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    </style>
</head>
<body>
    <header>
        <div class="top-banner">
            <p>¿Aún no realizas tus compras?&nbsp;&nbsp;&nbsp;<a href="https://wa.me/51935099454" target="_blank"><i class="fab fa-whatsapp"></i>&nbsp;Escríbenos al +51 935 099 454</a></p>
        </div>
        <div class="header-nav">
            <img src="../img/logo1.png" alt="logo" class="logo">
            <input type="text" placeholder="Busca una marca o producto">
            <button type="submit"><i class="fas fa-search"></i></button>
            <a href="#" class="user-profile-btn" id="btnPerfil">
            <i class="fas fa-user user-profile-icon"></i> Perfil de Usuario</a>             
        </div>
    </header>
    <div class="scrolling-bar">
        <ul class="category-list">
            <?php foreach ($categorias as $cat): ?>
                <li>
                    <a href="../cliente/produtos_cliente_registrado.php?idcategoria=<?php echo $cat['idcategoria']; ?>">
                        <i class="<?php echo $cat['icono']; ?>"></i> 
                        <?php echo $cat['nombrecat']; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <main>
        <section class="promo-banner">
            <div class="carousel">
                <div class="slides">
                    <img src="../img/banner1.jpg" alt="Eucerin Promotion">
                    <img src="../img/banner2.jpeg" alt="Vichy Promotion">
                    <img src="../img/banner3.jpg" alt="Vichy Promotion">
                    <img src="../img/banner4.jpg" alt="Vichy Promotion">
                    <img src="../img/banner5.jpeg" alt="Vichy Promotion">
                    <img src="../img/banner6.jpg" alt="Vichy Promotion">
                </div>
            </div>
        </section>
    </main>

     <!-- Modal para mostrar datos del usuario -->
     <div id="modalUsuario" class="modal">
    <div class="modal-content">
        <span class="close" id="cerrarModal">&times;</span>
        <h3>Datos del Usuario</h3>
        <?php if ($cliente): ?>
            <p><strong>Nombre:</strong> <?php echo $cliente['nombrecliente']; ?></p>
            <p><strong>Apellido:</strong> <?php echo $cliente['apellidocliente']; ?></p>
            <p><strong>Correo:</strong> <?php echo $cliente['correocliente']; ?></p>
            <p><strong>Dirección:</strong> <?php echo $cliente['direccioncliente']; ?></p>
            <p><strong>Teléfono:</strong> <?php echo $cliente['telefono']; ?></p>
            <div class="login-container">
                <form action="../logout.php" method="post">
                    <button type="submit" class="styled-button">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </button>
                </form>
                <form action="../cliente/historial.php" method="get">
                    <button type="submit" class="styled-button">
                        <i class="fas fa-history"></i> Historial
                    </button>
                </form>
                <form action="../cliente/rastreo.php" method="get">
                    <button type="submit" class="styled-button">
                        <i class="fas fa-map-marker-alt"></i> Rastrear Pedido
                    </button>
                </form>
            </div>
        <?php else: ?>
            <p>No se encontraron datos del usuario.</p>
        <?php endif; ?>
    </div>
</div>
    <script>
        // Función para cerrar la alerta
        function cerrarAlerta() {
            document.getElementById('alerta').style.display = 'none';
        }

        // Mostrar la alerta si hay un mensaje
        window.onload = function() {
            var alerta = document.getElementById('alerta');
            if (alerta) {
                alerta.style.display = 'block';
            }
        }

        // Mostrar el modal al hacer clic en el botón de perfil
        document.getElementById('btnPerfil').onclick = function() {
            document.getElementById('modalUsuario').style.display = 'block';
        }

        // Cerrar el modal cuando se hace clic en la 'x'
        document.getElementById('cerrarModal').onclick = function() {
            document.getElementById('modalUsuario').style.display = 'none';
        }

        // Cerrar el modal si se hace clic fuera de él
        window.onclick = function(event) {
            if (event.target == document.getElementById('modalUsuario')) {
                document.getElementById('modalUsuario').style.display = 'none';
            }
        }
    </script>

    <footer>
        <p>&copy; 2024 Droprax. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
