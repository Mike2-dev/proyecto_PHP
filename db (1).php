<?php
$servername = "";  // El nombre del servidor
$username = "";          // Tu nombre de usuario
$password = "";               // Tu contraseña
$dbname = "";                 // El nombre real de la base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

?>
