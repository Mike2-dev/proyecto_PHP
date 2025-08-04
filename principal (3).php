<?php
/* pagina principal */
include 'db.php';
session_start();
$sql = "SELECT * FROM productos ORDER BY tipo, nombre";
$result = $conn->query($sql);
$productos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[$row['tipo']][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">    
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+AU+NSW:wght@100..400&display=swap" rel="stylesheet"
    <title>Productos</title>
</head>
<body>
    <div class="barra-arriba">
            <h2>Taqueria</h2><a href="index.php"><button type="button"class="botonR">Regresar</button></a>
            <a href="carrito.php"><button class="botonR">Ver carrito</button></a>
    </div>
        <div class="Menu-pri">
            <h1>----Bienvenido----</h1>

            <div class="productos">
                <?php foreach ($productos as $tipo => $grupo): ?>
                
                    <div class="prod">
                        <h2><?= htmlspecialchars($tipo) ?></h2> 

                        <?php foreach ($grupo as $producto): ?>
                        <?php $rutaImagen = $producto['imagen']; ?>
                            <div class="item">
                                <span class="nombre"><?= htmlspecialchars($producto['nombre']) ?></span>
                                <span class="precio">$<?= number_format($producto['precio'], 2) ?></span>
                                <?php if (!empty($producto['imagen'])): ?>
                                    <img src="<?= htmlspecialchars($rutaImagen) ?>" alt="Imagen del producto" width="250">
                                <?php endif; ?>
                                <form action="pedido.php" method="post">
                                    <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
                                    <button type="submit" class="botonR">Ordenar</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
</body>
</html>
