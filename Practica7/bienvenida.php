<?php session_start(); ?>
<!doctype html><html lang="es"><head><meta charset="UTF-8"><title>Ejercicio 6</title></head><body>
<?php if (isset($_SESSION['nombre'])): ?>
<h1>¡Bienvenido, <?= htmlspecialchars($_SESSION['nombre'], ENT_QUOTES, 'UTF-8') ?>!</h1>
<?php else: ?><p>No podés visitar esta página sin identificarte.</p><?php endif; ?>
<a href="formulario.php">Volver</a></body></html>
