<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['usuario']) || empty($_POST['clave'])) {
    header('Location: formulario.php'); exit;
}
$_SESSION['usuario'] = (string) $_POST['usuario'];
$_SESSION['clave'] = (string) $_POST['clave']; // Solo para demostrar las dos variables que pide el ejercicio.
?><!doctype html><html lang="es"><head><meta charset="UTF-8"><title>Ejercicio 5</title></head><body>
<p>Se guardaron dos variables de sesión.</p><a href="recuperar.php">Ir a la tercera página</a>
</body></html>
