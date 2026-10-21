<?php
function comprobar_nombre_usuario(string $nombre_usuario): bool {
    if (strlen($nombre_usuario) < 3 || strlen($nombre_usuario) > 20) {
        return false;
    }
    $permitidos = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_';
    for ($i = 0; $i < strlen($nombre_usuario); $i++) {
        if (strpos($permitidos, substr($nombre_usuario, $i, 1)) === false) {
            return false;
        }
    }
    return true;
}
$pruebas = ['juan', 'ab', 'Ana_2026', 'nombre-con-guion', 'usuario con espacio', 'abcdefghijklmnopqrstu'];
?><!doctype html><html lang="es"><head><meta charset="utf-8"><title>Validación de usuarios</title></head><body>
<h1>Ejercicio 4 - Validación de usuarios</h1>
<?php foreach ($pruebas as $nombre): ?>
<p><?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>: <strong><?= comprobar_nombre_usuario($nombre) ? 'válido' : 'no válido' ?></strong></p>
<?php endforeach; ?>
</body></html>
