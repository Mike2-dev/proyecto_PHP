<?php.      
include 'db.php';

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $contrasena2 = $_POST['contrasena2'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE nombre = ? OR correo = ?");
    $stmt->bind_param("ss", $nombre, $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $mensaje = "El nombre de usuario o correo ya está registrado.";
    } else {
        
        if (empty($nombre) || empty($contrasena) || empty($contrasena2) || empty($correo) || empty($telefono) || empty($direccion)) {
            $mensaje = "Completa todos los campos";
        } elseif ($contrasena !== $contrasena2) {
            $mensaje = "Las contraseñas no coinciden";
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $contrasena)) {
            $mensaje = "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.";
        } else {
            // Generar el hash de la contraseña
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);

            // Preparar e insertar
            $stmt = $conn->prepare("INSERT INTO usuario (Nombre, contrasena, correo, telefono, direccion) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nombre, $hash, $correo, $telefono, $direccion);

            // Ejecutar una sola vez
            if ($stmt->execute()) {
                $mensaje = "Usuario registrado correctamente";
            } else {
                $mensaje = "Error al registrar el usuario";
            }
        }   
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Registra Usuario</title>
    <link rel="stylesheet" href="SesionRegistro.css">
</head>
<body>
    <div class="contenedor">
        <h2>Registra Usuario</h2>

        <?php if ($mensaje): ?>
            <p class="mensaje-error"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form method="post">
                <label for="nombre">Nombre:</label><br>
                <input type="text" name="nombre" required><br><br>

                <label for="correo">Correo:</label><br>
                <input type="email" name="correo" required><br><br>

                <label for="telefono">Teléfono:</label><br>
                <input type="text" name="telefono" required><br><br>

                <label for="direccion">Dirección:</label><br>
                <input type="text" name="direccion" required><br><br>

                <label for="contrasena">Contraseña:</label><br>
                <input type="password" name="contrasena" required><br><br>

                <label for="contrasena2">Confirmar Contraseña:</label><br>
                <input type="password" name="contrasena2" required><br><br>

                <div class="botonregistrar">
                    <button type="submit" class="botonR">Registrar Usuario</button>
                </div>
            </form>
        <div class="contenedor2">
            <a href="login.php">
                <button type="button" class="botregresar">Regresar</button>
            </a>
        </div>
    </div>
</body>
</html>
