<?php
include 'db.php';
$tipo = $_POST['tipo'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$precio = $_POST['precio'] ?? '';
$id = '';
$mensaje = '';
$productos_por_tipo = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['buscar_tipo'])) {
        if (!empty($tipo)) {
            $stmt = $conn->prepare("SELECT * FROM productos WHERE LOWER(tipo) = LOWER(?)");
            $stmt->bind_param("s", $tipo);
            $stmt->execute();
            $nombre = '';
            $precio = '';
            $id = '';              
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    $productos_por_tipo[] = $fila;
                }
                $mensaje = "Productos del tipo '$tipo' encontrados.";
            } else {
                $mensaje = "No se encontraron productos del tipo especificado.";
            }
        } else {
            $mensaje = "Por favor, introduce un tipo para buscar.";
        }
    }
    if (isset($_POST['buscar'])) {
        // buscar
        if (!empty($nombre)) {
            $stmt = $conn->prepare("SELECT * FROM productos WHERE LOWER(nombre) = LOWER(?)");
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $fila = $resultado->fetch_assoc();
                $id = $fila['id'];
                $precio = $fila['precio'];
                $rutaImagen = $fila['imagen'];
                $tipo = $fila['tipo'];
                $mensaje = "Producto encontrado.";
            } else {
                $mensaje = "No se encontró ningún producto con ese nombre.";
            }
        } else {
            $mensaje = "Por favor, introduce el nombre para buscar.";
        }
    }

    if (isset($_POST['agregar'])) {
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $tipo = $_POST['tipo'] ?? '';

        if (!empty($nombre) && is_numeric($precio)) {
            // Subida de imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                $nombreImagen = basename($_FILES['imagen']['name']);
                $rutaDestino = 'imagen/' . $nombreImagen;
                move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);
            } else {
                $rutaDestino = null;
            }

            $stmt = $conn->prepare("INSERT INTO productos (nombre, precio, imagen, tipo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdss", $nombre, $precio, $rutaDestino, $tipo);
            if ($stmt->execute()) {
                echo "Producto agregado correctamente.";
            } else {
                echo "Error al agregar.";
            }
        }
    }


    if (isset($_POST['eliminar'])) {
        // Eliminar 
        if (!empty($nombre)) {
            $stmt = $conn->prepare("DELETE FROM productos WHERE LOWER(nombre) = LOWER(?)");
            $stmt->bind_param("s", $nombre);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $mensaje = "Producto eliminado correctamente.";
                    // Limpiar campos 
                    $nombre = '';
                    $precio = '';
                    $id = '';
                    $tipo = '';
                } else {
                    $mensaje = "No se encontró ningún producto con ese nombre para eliminar.";
                }
            } else {
                $mensaje = "Error al eliminar el producto.";
            }
        } else {
            $mensaje = "Por favor, introduce el nombre del producto para eliminar.";
        }
    }

    if (isset($_POST['modificar'])) {
        $nombre_actual = $_POST['nombre_actual'] ?? '';
        $nuevo_nombre = $_POST['nombre'] ?? '';
        $precio = $_POST['precio'] ?? '';    
        $tipo = $_POST['tipo'] ?? '';
        
        if (!empty($nuevo_nombre) && is_numeric($precio)) {
            $stmt = $conn->prepare("SELECT id FROM productos WHERE LOWER(nombre) = LOWER(?)");
            $stmt->bind_param("s", $nombre_actual);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                    $nombreImagen = basename($_FILES['imagen']['name']);
                    $rutaImagen = 'imagen/' . $nombreImagen;
                    move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen);
                    $stmt = $conn->prepare("UPDATE productos SET nombre = ?, precio = ?, imagen = ?, tipo = ? WHERE LOWER(nombre) = LOWER(?)");
                    $stmt->bind_param("sdsss", $nuevo_nombre, $precio, $rutaImagen, $tipo, $nombre_actual);
                } else {
                    $stmt = $conn->prepare("UPDATE productos SET nombre = ?, precio = ? WHERE LOWER(nombre) = LOWER(?)");
                    $stmt->bind_param("sds", $nuevo_nombre, $precio, $nombre_actual);
                }

                if ($stmt->execute()) {
                    $mensaje = "Producto modificado correctamente.";
                } else {
                    $mensaje = "Error al modificar el producto.";
                }
            } else {
                $mensaje = "El producto no existe. No se puede modificar.";
            }
        } else {
            $mensaje = "Por favor, introduce un nombre y un precio válido para modificar.";
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
                <h2>Taqueria</h2><a href="index.php"><button type="button"class="botonR">Regresar</button></a>
        </div>
            <div class="contenedor-principal">
            <form method="post" enctype="multipart/form-data"class="formulario">

                <label>Tipo:</label><br />
                <input type="text" name="tipo" value="<?= htmlspecialchars($tipo) ?>"required /><br /><br />
                <label>Nombre:</label><br />
                <input type="text" name="nombre" value="<?= htmlspecialchars($nombre) ?>"/><br /><br />
                <input type="hidden" name="nombre_actual" value="<?= htmlspecialchars($nombre) ?>" />

                <label>Precio:</label><br />
                <input type="number" step="0.01" name="precio" value="<?= htmlspecialchars($precio) ?>" /><br /><br />

                <label>ID:</label><br />
                <input type="text" name="id" value="<?= htmlspecialchars($id) ?>" readonly /><br /><br />

                <label>Imagen:</label><br>
                <input type="file" name="imagen" accept="imagen/*"><br><br>

                <button type="submit" class="botonR" name="buscar_tipo">Buscar por Tipo</button>
                <button type="submit"class="botonR" name="buscar">Buscar por Nombre</button>
                <button type="submit"class="botonR" name="agregar">Agregar</button>
                <button type="submit"class="botonR" name="eliminar" onclick="return confirm('¿Seguro que deseas eliminar este producto?');">Eliminar</button>
                <button type="submit"class="botonR" name="modificar">Modificar</button>    
                    
            </form>
            <?php if (!empty($rutaImagen)): ?>
                <div class="imagen" >
                <p>Imagen del producto:</p>
                <img src="<?= htmlspecialchars($rutaImagen) ?>" alt="Imagen del producto" width="250">
                </div>
            <?php endif; ?>   

            <?php if (!empty($mensaje)): ?>
                <p><?= $mensaje ?></p>
            <?php endif; ?>
            <?php if (!empty($productos_por_tipo)): ?>
                <div class="productos-tipo resultado">
                    <?php foreach ($productos_por_tipo as $producto): ?>
                        <div class="producto">
                            <p><strong>Nombre:</strong> <?= htmlspecialchars($producto['nombre']) ?></p>
                            <p><strong>Precio:</strong> $<?= htmlspecialchars($producto['precio']) ?></p>
                            <?php if (!empty($producto['imagen'])): ?>
                                <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="Imagen del producto" width="150">
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div      
    </div
</body>
</html>
