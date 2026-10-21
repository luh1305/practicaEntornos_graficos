<?php
// La misma sesión conserva el contador entre páginas.
session_start();
$_SESSION['paginas_visitadas'] = ($_SESSION['paginas_visitadas'] ?? 0) + 1;
$visitas = $_SESSION['paginas_visitadas'];
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><title>Ejercicio 4 - Otra página</title></head><body>
<h1>Otra página</h1>
<p>Llevás <?= $visitas ?> páginas vistas en esta sesión.</p>
<p><a href="ejercicio4_inicio.php">Volver al inicio</a></p>
</body></html>
