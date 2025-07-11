<?php
include 'db.php';

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $contrasena2 = $_POST['contrasena2'] ?? '';

    if (empty($nombre) || empty($contrasena) || empty($contrasena2)) {
        $mensaje = "Completa todos los campos";
    } elseif ($contrasena !== $contrasena2) {
        $mensaje = "Las contraseñas no coinciden";
    } else {

        $mensaje = "Usuario registrado correctamente";
        
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Registra Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="contenedor1">
        <h2>Registra Usuario</h2>

        <?php if ($mensaje): ?>
            <p class="mensaje-error"><?php echo $mensaje; ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="nombre">Nombre:</label><br>
            <input type="text" name="nombre" required><br><br>

            <label for="contraseña">Contraseña:</label><br>
            <input type="password" name="contrasena" required><br><br>

            <label for="contrasena">Confirmar Contraseña:</label><br>
            <input type="password" name="contrasena2" required><br><br>
            <input type="submit" value="Registar Usuario">
        </form>
        <div class="contenedor2">
            <a href="index.php">
                <button type="button" class="boton-regresar">Regresar </button>
            </a>
        </div>
   
    </div>

    </div>
</body>
</html>