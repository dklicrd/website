<?php
// Contacto.php - Lógica para procesar el formulario

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- 1. Recoger y limpiar los datos del formulario ---
    // Es buena práctica limpiar los datos para evitar inyección de código.
    $nombre = strip_tags(trim($_POST["nombre"])); // Campo "nombre" del formulario
    $nombre = str_replace(array("\r","\n"), array(" "," "), $nombre);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL); // Campo "email"
    $mensaje = trim($_POST["mensaje"]); // Campo "mensaje"

    // --- 2. Validación básica ---
    if ( empty($nombre) OR !filter_var($email, FILTER_VALIDATE_EMAIL) OR empty($mensaje)) {
        // Si hay un error, puedes redirigir a una página de error o mostrar un mensaje.
        echo "Lo sentimos, hubo un error al enviar el formulario. Por favor, completa todos los campos correctamente.";
        exit;
    }

    // --- 3. Configurar los detalles del correo ---
    $destinatario = "info@dklicrd.com"; // ¡AQUÍ PONES TU CORREO!
    $asunto = "Nuevo mensaje de contacto desde el sitio web";

    // Crear el cuerpo del mensaje en formato HTML para que se vea mejor
    $contenido_mensaje = "
    <html>
    <head>
        <title>Nuevo mensaje de contacto</title>
    </head>
    <body>
        <h2>Detalles del contacto:</h2>
        <p><strong>Nombre:</strong> {$nombre}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Mensaje:</strong><br>{$mensaje}</p>
    </body>
    </html>
    ";

    // Cabeceras del correo para indicar que es HTML y quién lo envía
    $cabeceras  = "MIME-Version: 1.0" . "\r\n";
    $cabeceras .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    // Es muy importante que el remitente sea una dirección de tu propio dominio para evitar problemas de spam [citation:1]
    $cabeceras .= 'From: ' . $nombre . ' <webmaster@dklicrd.com>' . "\r\n";
    $cabeceras .= 'Reply-To: ' . $email . "\r\n";

    // --- 4. Enviar el correo ---
    if (mail($destinatario, $asunto, $contenido_mensaje, $cabeceras)) {
        // Si se envía correctamente, redirige a una página de "gracias" o muestra un mensaje
        echo "¡Gracias por contactarnos, $nombre! Te responderemos a la brevedad.";
        // También puedes usar: header('Location: gracias.html'); para redirigir a otra página.
    } else {
        echo "Oops! Algo salió mal y el mensaje no pudo ser enviado.";
    }

} // Fin del IF que verifica si el formulario fue enviado
?>