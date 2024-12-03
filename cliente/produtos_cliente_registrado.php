<?php
// Incluir el archivo de conexión a la base de datos
include '../conexion.php';
session_start(); // Asegúrate de que la sesión esté iniciada

// Obtener la categoría seleccionada
$idcategoria = isset($_GET['idcategoria']) ? $_GET['idcategoria'] : null;
$productos = [];

// Consultar todas las categorías (para el menú)
$categorias = [];
$sql_categoria = "SELECT * FROM categoria";
$result_categoria = $conexion->query($sql_categoria);
if ($result_categoria) {
    while ($row = $result_categoria->fetch_assoc()) {
        $categorias[] = $row;
    }
}

// Validar si la categoría está definida
if ($idcategoria) {
    // Consulta para obtener los productos de la categoría seleccionada
    $sql_productos = "SELECT * FROM producto WHERE idcategoria = ?";
    $stmt = $conexion->prepare($sql_productos);
    $stmt->bind_param("i", $idcategoria);
    $stmt->execute();
    $result_productos = $stmt->get_result();

    if ($result_productos->num_rows > 0) {
        while ($row = $result_productos->fetch_assoc()) {
            $productos[] = $row; // Guardar productos
        }
    } else {
        $mensaje = "No hay productos disponibles en esta categoría.";
        $tipo_alerta = "error";
    }
} else {
    $mensaje = "Categoría no seleccionada.";
    $tipo_alerta = "error";
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
    <title>Productos de la Categoría</title>
    <link rel="stylesheet" href="css/productos_cliente_registrado.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
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
    .button {
        background-color:  #40aba8;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .button:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    .button:active {
        background-color: #004494;
        transform: scale(1);
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
            <form action="../cliente/carrito.php" method="post">
            <button type="submit" class="styled-button">
            <i class="fas fa-shopping-cart"></i> Mi carrito
            </button>
            </form>
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
        <section class="productos">
            <div class="productos-list">
                <?php if (count($productos) > 0): ?>
                    <?php foreach ($productos as $producto): ?>
                        <div class="producto-item">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($producto['foto']); ?>" alt="<?php echo $producto['nombre']; ?>" /> <h3><?php echo $producto['nombre']; ?></h3>
                            <p><?php echo $producto['descripcion']; ?></p>
                            <p>Precio: S/ <?php echo $producto['precio']; ?></p>
                            <br><br>
                            <!-- Botón para añadir al carrito -->
                            <button class="button" onclick="abrirModal(<?php echo $producto['idproducto']; ?>, '<?php echo $producto['nombre']; ?>', <?php echo $producto['precio']; ?>)">Añadir al carrito</button>

                            <!-- Modal para ingresar la cantidad -->
                            <div id="modalCantidad" class="modal-producto">
                            <div class="modal-producto-content">
                                <span class="close-producto" onclick="cerrarModalCantidad()">&times;</span>
                                <h3>Seleccionar cantidad</h3>
                                <input type="number" id="cantidad" min="1" value="1" class="input-cantidad-producto" />
                                <button onclick="agregarCarrito()" class="button-producto">Añadir al carrito</button>
                            </div>
                            </div>

                            <script>
                            // Aquí va tu código JavaScript

                            let idproductoSeleccionado = null;
                            let precioSeleccionado = null;
                            let nombreProductoSeleccionado = null;

                            function abrirModal(idproducto, nombre, precio) {
                                idproductoSeleccionado = idproducto;
                                precioSeleccionado = precio;
                                nombreProductoSeleccionado = nombre;
                                document.getElementById('modalCantidad').style.display = 'block';
                            }

                            function cerrarModalCantidad() {
                                document.getElementById('modalCantidad').style.display = 'none';
                            }

                            function agregarCarrito() {
                                const cantidad = document.getElementById('cantidad').value;
                                
                                if (cantidad > 0) {
                                    // Enviar los datos al servidor para agregar el producto al carrito
                                    fetch('../cliente/agregar_carrito.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded'
                                        },
                                        body: `idproducto=${idproductoSeleccionado}&cantidad=${cantidad}`
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            alert('Producto añadido al carrito');
                                            cerrarModalCantidad();
                                        } else {
                                            alert('Hubo un problema al agregar el producto al carrito');
                                        }
                                    });
                                } else {
                                    alert('La cantidad debe ser mayor que 0');
                                }
                            }
                            </script>


                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay productos disponibles en esta categoría.</p>
                <?php endif; ?>
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
    <div class="footer-content">
        <p>&copy; 2024 Droprax. Todos los derechos reservados.</p>
        <a href="https://www.facebook.com" target="_blank" class="facebook">
            <i class="fa fa-facebook"></i>
        </a>
        <a href="https://www.instagram.com" target="_blank" class="instagram">
            <i class="fa fa-instagram"></i>
        </a>
    </div>
</footer>

</body>
</html>
