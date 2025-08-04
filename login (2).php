<?php
include 'db.php';
session_start();    

$mensaje = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $contraseña = $_POST['contrasena'] ?? '';

    if (!empty($nombre) && !empty($contraseña)) {
        // Buscar en empleados
        $stmt = $conn->prepare("SELECT * FROM empleados WHERE nombre = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $fila = $resultado->fetch_assoc();

            if (password_verify($contraseña, $fila['contrasena'])) {
                $_SESSION['id'] = $fila['id']; 
                $_SESSION['nombre'] = $fila['nombre'];

                header("Location: gestion.php");
                exit();
            } else {
                $mensaje = "Contraseña incorrecta.";
            }
        } else {
            // Buscar en usuario
            $stmt = $conn->prepare("SELECT * FROM usuario WHERE nombre = ?");
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows === 1) {
                $fila = $resultado->fetch_assoc();

                if (password_verify($contraseña, $fila['contrasena'])) {
                    $_SESSION['id'] = $fila['id']; 
                    $_SESSION['nombre'] = $fila['nombre'];

                    header("Location: principal.php");
                    exit();
                } else {
                    $mensaje = "Contraseña incorrecta.";
                }
            } else {
                $mensaje = "Usuario no encontrado.";
            }
        }
    } else {
        $mensaje = "Por favor completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Iniciar Sesión</title>
        <link rel="stylesheet" href="SesionRegistro.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playwrite+AU+NSW:wght@100..400&display=swap" rel="stylesheet">
</head>
<body>
    <div class="contenedor">
        <a href="index.php">
            <button type="button" class="botregresar">X</button>
        </a>
        <a href="Registro.php">
            <button type="button"class="botonR">Regirstrar</button>
        </a>
        <h2>Iniciar Sesion</h2>

        <?php if ($mensaje): ?>
        <p class="mensaje-error"><?php echo $mensaje; ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="nombre">Nombre:</label><br>
            <input type="text" name="nombre" required><br><br>
            <label for="contrasena">Contraseña:</label><br>
            <input type="password" name="contrasena" required><br><br>  
            <div class="contenedor3">
                <button type="submit"class="botonR">Ingresar</button>
            </div>
        </form>
    </div>
</body>
</html>