
<?php
include 'db.php';

$id = '';
$nombre = $_POST['nombre'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$correo = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['buscar'])) {
        if (!empty($nombre)) {
            $stmt = $conn->prepare("SELECT * FROM empleados WHERE LOWER(nombre) = LOWER(?)");
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows > 0) {
                $fila = $res->fetch_assoc();
                $id = $fila['id'];
                $correo = $fila['correo'];
                $telefono = $fila['telefono'];
                $mensaje = "Usuario encontrado.";
            } else {
                $mensaje = "No se encontró ningún usuario.";
            }
        }
    }

    if (isset($_POST['agregar'])) {
        if (!empty($nombre) && !empty($contrasena) && !empty($correo) && !empty($telefono)) {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO empleados (nombre, contrasena, correo, telefono) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $hash, $correo, $telefono);  
            if ($stmt->execute()) {
                $mensaje = "Usuario agregado.";
            } else {
                $mensaje = "Error al agregar.";
            }
        } else {
            $mensaje = "Todos los campos son obligatorios.";
        }
    }

    if (isset($_POST['eliminar'])) {
        if (!empty($nombre)) {
            $stmt = $conn->prepare("DELETE FROM empleados WHERE LOWER(nombre) = LOWER(?)");
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $mensaje = "Usuario eliminado.";
                $nombre = $contrasena = $correo = $telefono = $id = '';
            } else {
                $mensaje = "No se encontró ningún usuario con ese nombre.";
            }
        }
    }

    if (isset($_POST['modificar'])) {
        $nombre_actual = $_POST['nombre_actual'] ?? '';
        $nombre_nuevo = $_POST['nombre'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $telefono = $_POST['telefono'] ?? '';

        if (!empty($nombre_actual) && !empty($nombre_nuevo)) {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE empleados SET nombre=?, contrasena=?, correo=?, telefono=? WHERE LOWER(nombre) = LOWER(?)");
            $stmt->bind_param("sssss", $nombre_nuevo, $hash, $correo, $telefono, $nombre_actual);
            if ($stmt->execute()) {
                $mensaje = "Usuario modificado.";
                $nombre = $nombre_nuevo; // actualizar la variable para que se muestre el nuevo nombre en el formulario
            } else {
                $mensaje = "Error al modificar.";
            }
        } else {
            $mensaje = "Debe proporcionar el nombre original y el nuevo nombre.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="gestion.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+AU+NSW:wght@100..400&display=swap" rel="stylesheet">
    <title>Gestión de Productos</title>
</head>
<body>
    
    <div class="gestion">
        <div class="barra-arriba">
                <h2>Taqueria</h2><a href="gestion.php"><button type="button"class="botonR">Regresar</button></a>
        </div>
        <form method="post">
            <label>Nombre:</label><br>
            <input type="text" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required><br><br>
            <input type="hidden" name="nombre_actual" value="<?= htmlspecialchars($nombre) ?>" />


            <label>Contraseña:</label><br>
            <input type="text" name="contrasena" value="<?= htmlspecialchars($contrasena) ?>"><br><br>

            <label>Correo:</label><br>
            <input type="email" name="correo" value="<?= htmlspecialchars($correo) ?>"><br><br>

            <label>Teléfono:</label><br>
            <input type="text" name="telefono" value="<?= htmlspecialchars($telefono) ?>"><br><br>

            <label>ID:</label><br>
            <input type="text" name="id" value="<?= htmlspecialchars($id) ?>" readonly><br><br>

            <button type="submit"class="botonR" name="buscar">Buscar</button>
            <button type="submit"class="botonR" name="agregar">Agregar</button>
            <button type="submit"class="botonR" name="eliminar" onclick="return confirm('¿Eliminar este usuario?');">Eliminar</button>
            <button type="submit"class="botonR" name="modificar">Modificar</button>
        </form> 
        <?php if (!empty($mensaje)): ?>
            <p><?= $mensaje ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
