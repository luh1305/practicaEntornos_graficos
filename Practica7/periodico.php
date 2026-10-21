<?php
$titulares = [
 'politica' => 'Se debate un proyecto en el Congreso',
 'economia' => 'Novedades en la actividad económica',
 'deporte' => 'Gran jornada del deporte local'
];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($titulares[$_POST['tipo'] ?? ''])) {
    $tipo = $_POST['tipo'];
    setcookie('tipo_titular', $tipo, time() + 60 * 60 * 24 * 30, '/');
} else {
    $tipo = $_COOKIE['tipo_titular'] ?? null;
}
if (!isset($titulares[$tipo ?? ''])) $tipo = null;
?><!doctype html><html lang="es"><head><meta charset="UTF-8"><title>Ejercicio 4 - Periódico</title></head><body>
<h1>Periódico de ejemplo</h1>
<?php foreach ($titulares as $clave => $titular): ?>
<?php if ($tipo === null || $clave === $tipo): ?><h2><?= htmlspecialchars($titular) ?></h2><?php endif; ?>
<?php endforeach; ?>
<p><a href="configurar.php">Elegir otro titular</a> | <a href="borrar_cookie.php">Borrar la cookie</a></p>
</body></html>
