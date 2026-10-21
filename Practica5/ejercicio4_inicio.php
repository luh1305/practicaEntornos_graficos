<?php
// Ejercicio 4: contar páginas vistas durante la sesión.
session_start();
$_SESSION['paginas_visitadas'] = ($_SESSION['paginas_visitadas'] ?? 0) + 1;
$visitas = $_SESSION['paginas_visitadas'];
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><title>Ejercicio 4 - Inicio</title></head><body>
<h1>Página de inicio</h1>
<p>Llevás <?= $visitas ?> páginas vistas en esta sesión.</p>
<p><a href="ejercicio4_otra.php">Visitar la otra página</a></p>
</body></html>
