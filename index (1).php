<?php
date_default_timezone_set("America/Mexico_City");
$dia  = date("N"); 
$hora = date("G"); 

$diaPermitido = in_array($dia, [1, 5, 6, 7]);  
$horaPermitida = ($hora >= 19 && $hora < 23);     
$fueraDeHorario = !($diaPermitido && $horaPermitida);

if ($fueraDeHorario) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Horario Restringido</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="bloqueo-horario">
            
            <h2>⚠️ Sitio fuera de horario</h2>
            <p>Este sistema solo está disponible <strong>los dias lunes, viernes, sábados y domingos</strong><br>
            entre las <strong>7:00 PM a 00:00 AM 💀</strong>.</p>
        </div>
    </body>
    </html>
    <?php
    exit();
}
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

            if ($contraseña === $fila['contrasena']) {
                header("Location: Empleados.php");
                exit();
            } else {
                $mensaje = "Contraseña incorrecta.";
            }
        } else {
            // Si no está en empleados, buscar en usuario
            $stmt = $conn->prepare("SELECT * FROM usuario WHERE Nombre = ?");
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows === 1) {
                $fila = $resultado->fetch_assoc();

                if ($contraseña === $fila['contrasena']) {
                    $_SESSION['nombre']  = $fila['Nombre'];
                    $_SESSION['contrasena'] = $fila['contrasena'];
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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="contenedor">
        <h2>Iniciar Sesión</h2>

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
                <a href="Registro.php">
                <button type="button"class="botonR">Regirstrar</button>
                </a>

            </div>
        </form>
    </div>

</body>
</html>