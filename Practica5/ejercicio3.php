<?php
// Ejercicio 3: recomendar el sitio a un amigo mediante correo.
$estado = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $amigo = trim($_POST['amigo'] ?? '');
    $url = trim($_POST['sitio'] ?? '');
    if ($nombre === '' || !filter_var($amigo, FILTER_VALIDATE_EMAIL)
        || !filter_var($url, FILTER_VALIDATE_URL)
        || !in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)) {
        $estado = 'Completá los datos con un correo y una URL válidos.';
    } else {
        $nombreSeguro = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
        $sitioSeguro = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        $cuerpo = "<html><body><p>{$nombreSeguro} te recomienda visitar este sitio:</p><p><a href=\"{$sitioSeguro}\">{$sitioSeguro}</a></p></body></html>";
        $cabeceras = "MIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8";
        $estado = mail($amigo, 'Te recomiendo este sitio', $cuerpo, $cabeceras)
            ? 'Recomendación aceptada por el servidor de correo.'
            : 'No se pudo enviar. Revisá la configuración de PHP.';
    }
}
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><title>Práctica 5 - Ejercicio 3</title></head><body>
<h1>Recomendar el sitio a un amigo</h1>
<?php if ($estado !== ''): ?><p><?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<form method="post">
<label>Tu nombre: <input name="nombre" required></label><br>
<label>Correo del amigo: <input type="email" name="amigo" required></label><br>
<label>URL del sitio: <input type="url" name="sitio" value="http://localhost:8000/ejercicio3.php" required></label><br>
<button type="submit">Recomendar</button>
</form></body></html>
