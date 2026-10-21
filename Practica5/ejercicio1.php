<?php
// Ejercicio 1: enviar un correo con formato HTML.
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destinatario = trim($_POST['destinatario'] ?? '');
    $asunto = trim($_POST['asunto'] ?? '');
    $contenido = trim($_POST['contenido'] ?? '');
    if (!filter_var($destinatario, FILTER_VALIDATE_EMAIL) || $asunto === '' || $contenido === '') {
        $mensaje = 'Completá todos los campos con un correo válido.';
    } elseif (preg_match('/[\r\n]/', $asunto)) {
        $mensaje = 'El asunto contiene caracteres no permitidos.';
    } else {
        $cuerpo = '<html><body><h2>Mensaje desde PHP</h2><p>' . nl2br(htmlspecialchars($contenido, ENT_QUOTES, 'UTF-8')) . '</p></body></html>';
        $cabeceras = "MIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8";
        $mensaje = mail($destinatario, $asunto, $cuerpo, $cabeceras)
            ? 'El servidor aceptó el mensaje para envío.'
            : 'No se pudo enviar. Revisá la configuración de correo de PHP.';
    }
}
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><title>Práctica 5 - Ejercicio 1</title></head><body>
<h1>Enviar correo HTML</h1>
<?php if ($mensaje !== ''): ?><p><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<form method="post">
<label>Destinatario: <input type="email" name="destinatario" required></label><br>
<label>Asunto: <input type="text" name="asunto" required></label><br>
<label>Mensaje:<br><textarea name="contenido" required></textarea></label><br>
<button type="submit">Enviar</button>
</form></body></html>
