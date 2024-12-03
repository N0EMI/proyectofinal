<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmacia Dropax</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <style>
    </style>
</head>
<body>
<div class="left-container"></div>
<div class="right-container">
    <div class="login-container">
        <img src="../img/logo.jpg" alt="Logo">
        <form action="../login.php" method="post">
            <div class="input-container">
                <input name="correo" type="text" id="username" placeholder="Correo Electrónico" required>
                <i class="fas fa-user icon"></i>
            </div>
            <div class="input-container">
                <input type="password" name="contrasena" id="password" placeholder="Contraseña" required>
                <i class="fas fa-lock icon"></i>
            </div>
            <input type="submit" value="Ingresar">
        </form>
        <a href="#">¿Olvidaste tu contraseña?</a>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>🚫 Datos incorrectos<br> Por favor, intenta de nuevo.</p>
    </div>
</div>

<script>
    // Mostrar el modal si hay un error
    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
    document.getElementById('myModal').style.display = 'block';
    <?php endif; ?>

    // Cerrar el modal cuando se hace clic en la 'x'
    document.querySelector('.close').onclick = function () {
        document.getElementById('myModal').style.display = 'none';
    };

    // Cerrar el modal si se hace clic fuera de él
    window.onclick = function (event) {
        if (event.target == document.getElementById('myModal')) {
            document.getElementById('myModal').style.display = 'none';
        }
    };
</script>
</body>
</html>
