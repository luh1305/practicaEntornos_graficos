<?php
$termino = trim($_GET['q'] ?? '');
$canciones = [];
$error = '';
if ($termino !== '') {
    try {
        $bd = new mysqli('localhost', 'root', '', 'prueba');
        $bd->set_charset('utf8mb4');
        $st = $bd->prepare('SELECT canciones FROM buscador WHERE canciones LIKE ? ORDER BY canciones');
        $patron = '%' . $termino . '%';
        $st->bind_param('s', $patron); $st->execute();
        $canciones = $st->get_result()->fetch_all(MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $e) {
        $error = 'No se pudo conectar con la base de datos prueba.';
    }
}
?><!doctype html><html lang="es"><head><meta charset="UTF-8"><title>Ejercicio 8 - Buscador</title></head><body>
<h1>Buscador de canciones</h1><form method="get">
<label>Nombre o parte del título: <input name="q" value="<?= htmlspecialchars($termino, ENT_QUOTES, 'UTF-8') ?>" required></label>
<button type="submit">Buscar</button></form>
<?php if ($error): ?><p><?= htmlspecialchars($error) ?></p><?php elseif ($termino !== ''): ?>
<h2>Resultados</h2>
<?php if (!$canciones): ?><p>No se encontraron canciones.</p><?php endif; ?>
<ul><?php foreach ($canciones as $c): ?><li><?= htmlspecialchars($c['canciones'], ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?></ul>
<?php endif; ?></body></html>
