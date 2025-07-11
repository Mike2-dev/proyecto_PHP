<?php
include 'db.php';

$nombre = $_POST['nombre'] ?? '';
$contraseña = $_POST['contraseña'] ?? '';

if (!empty($nombre) && !empty($contraseña)) {
    $stmt = $conn->prepare("SELECT * FROM empleados WHERE nombre = ?");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $fila = $resultado->fetch_assoc();
        
        // Comparar contraseña (si está cifrada, usa password_verify en su lugar)
        if ($contraseña === $fila['contraseña']) {
            // Login exitoso
            header("Location: bienvenido.php");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
} else {
    echo "Por favor completa todos los campos.";
}
?>
