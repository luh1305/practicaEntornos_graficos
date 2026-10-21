<?php
// Ejercicio 2: página de contacto para enviar consultas al webmaster.
$estado = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $consulta = trim($_POST['consulta'] ?? '');
    $webmaster = getenv('WEBMASTER_EMAIL');
    if ($nombre === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL) || $consulta === '') {
        $estado = 'Ingresá nombre, correo válido y consulta.';
    } elseif (!$webmaster || !filter_var($webmaster, FILTER_VALIDATE_EMAIL)) {
        $estado = 'Configurá la variable de entorno WEBMASTER_EMAIL para enviar consultas.';
    } else {
        $cuerpo = '<html><body><h2>Nueva consulta</h2><p><b>Nombre:</b> ' . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8')
            . '</p><p><b>Correo:</b> ' . htmlspecialchars($correo, ENT_QUOTES, 'UTF-8')
            . '</p><p>' . nl2br(htmlspecialchars($consulta, ENT_QUOTES, 'UTF-8')) . '</p></body></html>';
        $cabeceras = "MIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8";
        $estado = mail($webmaster, 'Consulta desde el sitio', $cuerpo, $cabeceras)
            ? 'Consulta enviada al servidor de correo.'
            : 'No se pudo enviar la consulta. Revisá la configuración de PHP.';
    }
}
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><title>Práctica 5 - Ejercicio 2</title></head><body>
<h1>Contacto</h1>
<?php if ($estado !== ''): ?><p><?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<form method="post">
<label>Nombre: <input name="nombre" required></label><br>
<label>Correo: <input type="email" name="correo" required></label><br>
<label>Consulta:<br><textarea name="consulta" required></textarea></label><br>
<button type="submit">Enviar consulta</button>
</form></body></html>
