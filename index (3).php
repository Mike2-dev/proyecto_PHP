<?php
date_default_timezone_set("America/Mexico_City");
$dia  = date("N"); 
$hora = date("G"); 
/* Horario de la pagina  */
$diaPermitido = in_array($dia, [ 1, 2, 4, 5, 6, 7]);  
$horaPermitida = ($hora >= 1 && $hora < 23);     
$fueraDeHorario = !($diaPermitido && $horaPermitida);

if ($fueraDeHorario) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Horario Restringido</title>
        <link rel="stylesheet" href="Inicio.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playwrite+AU+NSW:wght@100..400&display=swap" rel="stylesheet">
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

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Iniciar Sesión</title>
        <link rel="stylesheet" href="Inicio.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playwrite+AU+NSW:wght@100..400&display=swap" rel="stylesheet">
</head>
<body>
  <div class="pagina">
    <div class="todo">
        <div class="barra-arriba">
            <h2>Taqueria</h2>
        </div>
        <div class="decorado-columna izq">
            <img src="imagen/Gringa.png" class="decorado-img">
            <img src="imagen/Hamburguesa C.png" class="decorado-img">
            <img src="imagen/Tacos de Bisteck.png" class="decorado-img">
            <img src="imagen/Torta de Trompo.png" class="decorado-img">
            <img src="imagen/Tacos de pastor.png" class="decorado-img">
        </div>
        <div class = "fondo">
            <div style="white-space: pre-line; text-align: justify; max-width: 600px; margin: 0 auto; padding: 10px;">
            ¡Bienvenido a Taquería Juquila! 🌮🔥

            Aquí no solo servimos tacos, servimos momentos llenos de sabor y tradición.  
            Cada tortilla caliente, cada carne bien sazonada y cada salsa casera están  
            preparados con dedicación, pensando en ti. Nos enorgullece atenderte como  
            mereces, porque para nosotros cada cliente es parte de la familia.
            Gracias por visitarnos. Siéntete como en casa y déjate llevar por el  
            auténtico sabor mexicano.  
            ¡Tu satisfacción es nuestra mayor recompensa!
            ¡Ordana Aqui!
            <a href="login.php"><button type="button"class="botonR">Ingresa</button></a>
        </div>
        <div class="decorado-columna der">
            <img src="imagen/Torta de bisteck.png" class="decorado-img">
            <img src="imagen/Hamburguesa do trom.png" class="decorado-img">
            <img src="imagen/Tacos de trompo.png" class="decorado-img">
            <img src="imagen/hamburguesa_d.png" class="decorado-img">
            <img src="imagen/1.png" class="decorado-img">
        </div>
    </div>
    <div class="barra-contacto">
      <div class="columna">
        <h3>Teléfonos</h3>
        <p>123-456-7890</p>
        <p>098-765-4321</p>
      </div>
      <div class="columna">
        <h3>Correos</h3>
        <p>Taqueria1@mail.com</p>
        <p>Taqueria2@mail.com</p>
      </div>
      <div class="columna">
        <h3>Redes</h3>
        <p>Facebook: Taquería</p>
        <p>Instagram: @taqueria</p>
      </div>
    </div>
  </div>

  </div>
    
</body>
</html>