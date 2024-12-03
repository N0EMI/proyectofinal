<?php
// Incluir el archivo de conexión a la base de datos
include '../conexion.php';

// Variables para mensajes de alerta
$mensaje = '';
$tipo_alerta = '';

// Manejar acciones POST (Agregar, Editar, Eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];
// Agregar Producto
    if ($accion === 'agregar') {
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $descripcion = $_POST['descripcion'];
        $stock = $_POST['stock'];
        $descuento = $_POST['descuento'];
        $foto = $_POST['foto'];
        $idcategoria = $_POST['idcategoria'];

        $sql_agregar = "INSERT INTO producto (nombre, precio, descripcion, stock, descuento, foto, idcategoria) 
                        VALUES ('$nombre', '$precio', '$descripcion', '$stock', '$descuento', '$foto', '$idcategoria')";
        if ($conexion->query($sql_agregar)) {
            $mensaje = "Producto agregado correctamente";
            $tipo_alerta = "success";
        } else {
            $mensaje = "Error al agregar producto: " . $conexion->error;
            $tipo_alerta = "error";
        }
    } 
// Editar Producto
    elseif ($accion === 'editar') {
        $idproducto = $_POST['idproducto'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $descripcion = $_POST['descripcion'];
        $stock = $_POST['stock'];
        $descuento = $_POST['descuento'];
        $foto = $_POST['foto'];

        $sql_editar = "UPDATE producto 
                       SET nombre='$nombre', precio='$precio', descripcion='$descripcion', stock='$stock', descuento='$descuento', foto='$foto' 
                       WHERE idproducto='$idproducto'";
        if ($conexion->query($sql_editar)) {
            $mensaje = "Producto editado correctamente";
            $tipo_alerta = "success";
        } else {
            $mensaje = "Error al editar producto: " . $conexion->error;
            $tipo_alerta = "error";
        }
    } 
//Eliminar Producto
    elseif ($accion === 'eliminar') {
        $idproducto = $_POST['idproducto'];
        $sql_eliminar = "DELETE FROM producto WHERE idproducto='$idproducto'";
        if ($conexion->query($sql_eliminar)) {
            $mensaje = "Producto eliminado correctamente";
            $tipo_alerta = "success";
        } else {
            $mensaje = "Error al eliminar producto: " . $conexion->error;
            $tipo_alerta = "error";
        }
    }
}

// Obtener categorías
$categoria = [];
$sql_categoria = "SELECT * FROM categoria";
$result = $conexion->query($sql_categoria);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categoria[] = $row;
    }
}

// Obtener productos por categoría seleccionada
$idcategoria = isset($_GET['idcategoria']) ? intval($_GET['idcategoria']) : 0;
$productos = [];
if ($idcategoria > 0) {
    $sql_producto = "SELECT * FROM producto WHERE idcategoria = $idcategoria";
    $result = $conexion->query($sql_producto);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    }
}

//categoria
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

//Agregar Categoria
    if ($accion === 'agregar_categoria') {
        $idcategoria = $_POST['idcategoria'];
        $nombrecat = $_POST['nombrecat'];
        $icono = $_POST['icono'];

        $sql_agregar = "INSERT INTO categoria (idcategoria, nombrecat, icono) VALUES (?, ? , ?)";
        $stmt = $conexion->prepare($sql_agregar);
        $stmt->bind_param("sss", $idcategoria, $nombrecat, $icono);

        if ($stmt->execute()) {
            $mensaje = "Categoría agregada correctamente";
            $tipo_alerta = "success";
        } else {
            $mensaje = "Error al agregar categoría: " . $stmt->error;
            $tipo_alerta = "error";
        }
        $stmt->close();
    } 

//Editar Categoria
    elseif ($accion === 'editar_categoria') {
        $idcategoria = $_POST['idcategoria'];
        $nombrecat = $_POST['nombrecat'];
        $icono = $_POST['icono'];

        $sql_editar = "UPDATE categoria SET nombrecat = ?, icono = ? WHERE idcategoria = ?";
        $stmt = $conexion->prepare($sql_editar);
        $stmt->bind_param("sss", $nombrecat, $idcategoria, $icono);

        if ($stmt->execute()) {
            $mensaje = "Categoría editada correctamente";
            $tipo_alerta = "success";
        } else {
            $mensaje = "Error al editar categoría: " . $stmt->error;
            $tipo_alerta = "error";
        }
        $stmt->close();
    } 
    
//Eliminar Categoria
    elseif ($accion === 'eliminar_categoria') {
        $idcategoria = $_POST['idcategoria'];

        $sql_eliminar = "DELETE FROM categoria WHERE idcategoria = ?";
        $stmt = $conexion->prepare($sql_eliminar);
        $stmt->bind_param("s", $idcategoria);

        if ($stmt->execute()) {
            $mensaje = "Categoría eliminada correctamente";
            $tipo_alerta = "success";
        } else {
            $mensaje = "Error al eliminar categoría: " . $stmt->error;
            $tipo_alerta = "error";
        }
        $stmt->close();
    }
}
// Cerrar la conexión
$conexion->close();
?>
<!--Codigo HTML-->
<!DOCTYPE html>
<html lang="es">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DROPRAX</title>
    <link rel="stylesheet" href="css/admi.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> 
    <style> 

    </style>
</head>
<body>
<div class="top-banner"><p>Bienvenido Nos da gusto tenerte aqui..!</p></div>
<header>
    <div class="header-nav">
        <img src="../img/logo1.png" alt="logo dropax" class="logo">
        <div class="button-container">
        <button onclick="abrirModal('modalAgregarCategoria')" class="button">
            <i class="fas fa-plus"></i> Agregar Categoría
        </button>
        <button onclick="abrirModal('modalEditarCategoria')" class="button">
            <i class="fas fa-edit"></i> Editar Categoría
        </button>
        <button onclick="abrirModal('modalEliminarCategoria')" class="button">
            <i class="fas fa-trash-alt"></i> Eliminar Categoría
        </button>
    </div>
    </div>
</header>


<!-- Modal para agregar categoría -->
<div id="modalAgregarCategoria" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal('modalAgregarCategoria')">&times;</span>
        <div class="form-header"><h3>Agregar Categoría</h3></div>
        <form method="POST">
            <input type="hidden" name="accion" value="agregar_categoria">
            <label for="idcategoria">ID Categoría:</label>
            <input type="text" name="idcategoria" id="idcategoria" required>
            <label for="nombrecat">Nombre:</label>
            <input type="text" name="nombrecat" id="nombrecat" required>
            <label for="icono">Icono:</label>
            <input type="text" name="icono" id="icono" required>
            <button type="submit">Agregar</button>
        </form>
    </div>
</div>

<!-- Modal para editar categoría -->
<div id="modalEditarCategoria" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal('modalEditarCategoria')">&times;</span>
        <div class="form-header"><h3>Editar Categoría</h3></div>
        <form method="POST">
            <input type="hidden" name="accion" value="editar_categoria">
            <label for="idcategoriaEditar">ID Categoría:</label>
            <input type="text" name="idcategoria" id="idcategoriaEditar" required>
            <label for="nombrecatEditar">Nuevo Nombre:</label>
            <input type="text" name="nombrecat" id="nombrecatEditar" required>
            <label for="icono">Nuevo Icono:</label>
            <input type="text" name="icono" id="icono" required>
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</div>

<!-- Modal para eliminar categoría -->
<div id="modalEliminarCategoria" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal('modalEliminarCategoria')">&times;</span>
        <div class="form-header"><h3>Eliminar Categoría</h3></div>
        <form method="POST">
            <input type="hidden" name="accion" value="eliminar_categoria">
            <label for="idcategoriaEliminar">ID Categoría:</label>
            <input type="text" name="idcategoria" id="idcategoriaEliminar" required>
            <button type="submit">Eliminar</button>
            <button type="button" onclick="cerrarModal('modalEliminar')">Cancelar</button>
        </form>
    </div>
</div>
<!-- Alertas -->
<?php if (!empty($mensaje)): ?>
    <div class="alert <?php echo $tipo_alerta; ?>">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
        <?php echo $mensaje; ?>
    </div>
<?php endif; ?>

<!-- Barra de Categorías -->
<div class="scrolling-bar">
    <ul class="category-list">
        <?php foreach ($categoria as $cat): ?>
            <li>
                <form  method="GET">
                    <input type="hidden" name="idcategoria" value="<?php echo $cat['idcategoria']; ?>">
                    <button type="submit" class="btn-category">
                    <i class="<?php echo $cat['icono']; ?>"></i> <?php echo $cat['nombrecat']; ?>
                    </button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php if ($idcategoria > 0): ?>
    <div class="gestion-productos">
        <h3 class="titulo">GESTIÓN DE PRODUCTOS DE LA CATEGORÍA <?php echo $idcategoria; ?></h3>

        <?php if (empty($productos)): ?>
            <!-- Mostrar mensaje si no hay productos -->
            <p class="mensaje-no-productos">No hay productos que mostrar en esta categoría.</p>
            <button class="btn agregar" onclick="abrirModal('modalAgregar')"><i class="fas fa-plus"></i> Agregar Producto</button>

        <?php else: ?>
            <!-- Botón para agregar -->
            <button class="btn agregar" onclick="abrirModal('modalAgregar')"><i class="fas fa-plus"></i> Agregar Producto</button>

            <!-- Tabla de productos -->
            <table class="tabla-productos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Descripción</th>
                        <th>Stock</th>
                        <th>Descuento</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo $producto['idproducto']; ?></td>
                            <td><?php echo $producto['nombre']; ?></td>
                            <td><?php echo $producto['precio']; ?></td>
                            <td><?php echo $producto['descripcion']; ?></td>
                            <td><?php echo $producto['stock']; ?></td>
                            <td><?php echo $producto['descuento']; ?>%</td>
                            <td><img src="data:image/jpeg;base64,<?php echo base64_encode($producto['foto']); ?>" alt="<?php echo $producto['nombre']; ?>" style="width: 100px; height: 100px; object-fit: cover;" />
                            <h3><?php echo $producto['nombre']; ?></h3></td>

                            <td>
                                <button class="btn editar" onclick="abrirModalEditar(<?php echo htmlspecialchars(json_encode($producto)); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn eliminar" onclick="abrirModalEliminar(<?php echo $producto['idproducto']; ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
<?php endif; ?>


<!-- Modal para agregar producto -->
<div id="modalAgregar" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal('modalAgregar')">&times;</span>
        <div class="form-header"><h3>Agregar Producto</h3></div>
        <form idcategoria=<?php echo $idcategoria; ?> method="POST">
            <input type="hidden" name="accion" value="agregar">
            <input type="text" name="nombre" placeholder="Nombre del producto" required>
            <input type="number" name="precio" placeholder="Precio" required>
            <textarea name="descripcion" placeholder="Descripción" required></textarea>
            <input type="number" name="stock" placeholder="Stock" required>
            <input type="number" step="0.01" name="descuento" placeholder="Descuento (%)" required>
            <input type="text" name="foto" placeholder="URL de la foto" required>
            <input type="text" name="idcategoria" placeholder="idcategoria" required>
           <!--<input type="hidden" name="idcategoria" value="<?php echo $idcategoria; ?>"-->
            <button type="submit">Agregar</button>
        </form>
    </div>
</div>

<!-- Modal para editar producto -->
<div id="modalEditar" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal('modalEditar')">&times;</span>
        <div class="form-header"><h3>Editar Producto</h3></div>
        <form id="formEditar" idcategoria=<?php echo $idcategoria; ?> method="POST">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="idproducto" id="editarIdProducto">
            <input type="text" name="nombre" id="editarNombre" required>
            <input type="number" name="precio" id="editarPrecio" required>
            <textarea name="descripcion" id="editarDescripcion" required></textarea>
            <input type="number" name="stock" id="editarStock" required>
            <input type="number" step="0.01" name="descuento" id="editarDescuento" required>
            <input type="text" name="foto" id="editarFoto" required>
            <button type="submit">Guardar Cambios</button>
     
        </form>
    </div>
</div>

<!-- Modal para eliminar producto -->
<div id="modalEliminar" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal('modalEliminar')">&times;</span>
        <div class="form-header"><h3>Eliminar Producto</h3></div>
        <p>¿Estás seguro de que deseas eliminar este producto?</p>
        <form id="formEliminar" <?php echo $idcategoria; ?> method="POST">
            <input type="hidden" name="accion" value="eliminar">
            <input type="hidden" name="idproducto" id="eliminarIdProducto">
            <button type="submit">Eliminar</button>
            <button type="button" onclick="cerrarModal('modalEliminar')">Cancelar</button>
        </form>
    </div>
</div>

<footer>
        <p>&copy; 2024 Droprax. Todos los derechos reservados.</p>
</footer>

<!-- Codigo Script-->
<script>
// Funciones para manejar los modales de Producto
    function abrirModal(id) {
        document.getElementById(id).style.display = 'block';
    }

    function cerrarModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function abrirModalEditar(producto) {
        abrirModal('modalEditar');
        document.getElementById('editarIdProducto').value = producto.idproducto;
        document.getElementById('editarNombre').value = producto.nombre;
        document.getElementById('editarPrecio').value = producto.precio;
        document.getElementById('editarDescripcion').value = producto.descripcion;
        document.getElementById('editarStock').value = producto.stock;
        document.getElementById('editarDescuento').value = producto.descuento;
        document.getElementById('editarFoto').value = producto.foto;
    }

    function abrirModalEliminar(idproducto) {
        abrirModal('modalEliminar');
        document.getElementById('eliminarIdProducto').value = idproducto;
    }

// Funcion para manejar los modales de Categoria
    function abrirModal(id) {
    document.getElementById(id).style.display = 'block';
    }

    function cerrarModal(id) {
    document.getElementById(id).style.display = 'none';
    }
// Movimiento de categorias
    const scrollingBar = document.querySelector('.scrolling-bar .category-list');
    scrollingBar.addEventListener('mouseenter', () => {
        scrollingBar.style.animationPlayState = 'paused';
    });
    scrollingBar.addEventListener('mouseleave', () => {
        scrollingBar.style.animationPlayState = 'running';
    });


</script>
</body>
</html>
