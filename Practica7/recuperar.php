<?php session_start(); ?>
<!doctype html><html lang="es"><head><meta charset="UTF-8"><title>Ejercicio 5</title></head><body>
<h1>Datos recuperados de la sesión</h1>
<?php if (isset($_SESSION['usuario'], $_SESSION['clave'])): ?>
<p>Usuario: <?= htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8') ?></p>
<p>Clave: <?= htmlspecialchars($_SESSION['clave'], ENT_QUOTES, 'UTF-8') ?></p>
<?php else: ?><p>No hay datos guardados. <a href="formulario.php">Volver</a></p><?php endif; ?>
</body></html>
