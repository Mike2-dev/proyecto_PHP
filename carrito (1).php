<?php
/* pagina carrito */
session_start();
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'], $_POST['id_producto'])) {
    $id_usuario = $_SESSION['id'];
    $id_producto = $_POST['id_producto'];

    $id_usuario = $_SESSION['id'];
    $id_producto = $_POST['id_producto'];

    // Obtener la cantidad actual
    $stmt = $conn->prepare("SELECT cantidad FROM ordenes WHERE id_usuario = ? AND id_producto = ?");
    $stmt->bind_param("ii", $id_usuario, $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fila = $result->fetch_assoc();
        $cantidad = $fila['cantidad'];

        if ($cantidad > 1) {
            // Restar 1
            $update = $conn->prepare("UPDATE ordenes SET cantidad = cantidad - 1 WHERE id_usuario = ? AND id_producto = ?");
            $update->bind_param("ii", $id_usuario, $id_producto);
            $update->execute();
        } else {
            // Si solo hay 1, eliminar la fila
            $delete = $conn->prepare("DELETE FROM ordenes WHERE id_usuario = ? AND id_producto = ?");
            $delete->bind_param("ii", $id_usuario, $id_producto);
            $delete->execute();
        }
    }

    header("Location: carrito.php");
    exit;

}
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = $_SESSION['id'];

$stmt = $conn->prepare("
    SELECT 
        p.id AS id_producto,
        p.nombre,
        p.precio,
        c.cantidad,
        (p.precio * c.cantidad) AS total
    FROM ordenes c
    JOIN productos p ON c.id_producto = p.id
    WHERE c.id_usuario = ?
");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();


$totalGeneral = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>   
    <meta charset="UTF-8">
    <title>Carrito</title>
    <link rel="stylesheet" href="carrito.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+AU+NSW:wght@100..400&display=swap" rel="stylesheet"
</head>
<body>
    <div class="barra-arriba">
        <h2>Tu Carrito</h2>
        <a href="principal.php"><button class="botonR">Seguir comprando</button></a>
    </div>

    <div class="contenido-carrito">
        <?php if ($resultado->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td>$<?= number_format($fila['precio'], 2) ?></td>
                        <td><?= $fila['cantidad'] ?></td>
                        <td>$<?= number_format($fila['total'], 2) ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="id_producto" value="<?= $fila['id_producto'] ?>">
                                <input type="hidden" name="eliminar" value="1">
                                <button type="submit" class="botonR" onclick="return confirm('¿Eliminar este producto?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php $totalGeneral += $fila['total']; ?>
                <?php endwhile; ?>
                <tr>
                    <td colspan="3"><strong>Total general:</strong></td>
                    <td><strong>$<?= number_format($totalGeneral, 2) ?></strong></td>
                </tr>
            </table>

            <form action="confirmar_pedido.php" method="post">
                <button type="submit" href="principal.php"class="botonR">Confirmar pedido</button>

            </form>
            

        <?php else: ?>
            <p>No tienes productos en el carrito.</p>
        <?php endif; ?>
    </div>
</body>
</html>
