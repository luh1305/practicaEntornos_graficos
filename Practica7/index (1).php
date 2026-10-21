<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['usuario'] ?? '');
    if ($nombre !== '') {
        setcookie('usuario', $nombre, time() + 60 * 60 * 24 * 30, '/');
        header('Location: index.php');
        exit;
    }
}
$ultimo = $_COOKIE['usuario'] ?? null;
?><!doctype html><html lang="es"><head><meta charset="UTF-8"><title>Ejercicio 3</title></head><body>
<h1>Nombre de usuario</h1>
<?php if ($ultimo !== null): ?><p>Último usuario ingresado: <strong><?= htmlspecialchars($ultimo, ENT_QUOTES, 'UTF-8') ?></strong></p><?php endif; ?>
<form method="post"><label>Nombre de usuario: <input type="text" name="usuario" required maxlength="100"></label>
<button type="submit">Guardar</button></form></body></html>
