<?php
session_start();
if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
$error = '';
try {
    $bd = new mysqli('localhost', 'root', '', 'Compras');
    $bd->set_charset('utf8mb4');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $accion = $_POST['accion'] ?? '';
        if ($accion === 'vaciar') $_SESSION['carrito'] = [];
        elseif ($id) {
            $st = $bd->prepare('SELECT id FROM `catálogo` WHERE id = ?');
            $st->bind_param('i', $id); $st->execute();
            if ($st->get_result()->fetch_assoc()) {
                if ($accion === 'agregar') $_SESSION['carrito'][$id] = ($_SESSION['carrito'][$id] ?? 0) + 1;
                if ($accion === 'quitar') {
                    if (isset($_SESSION['carrito'][$id])) {
                        $_SESSION['carrito'][$id]--;
                        if ($_SESSION['carrito'][$id] <= 0) unset($_SESSION['carrito'][$id]);
                    }
                }
            }
        }
        header('Location: index.php'); exit;
    }
    $productos = $bd->query('SELECT id, producto, precio FROM `catálogo` ORDER BY id')->fetch_all(MYSQLI_ASSOC);
} catch (mysqli_sql_exception $e) {
    $productos = []; $error = 'No se pudo conectar con la base de datos Compras.';
}
$total = 0;
?><!doctype html><html lang="es"><head><meta charset="UTF-8"><title>Ejercicio 7 - Carrito</title></head><body>
<h1>Catálogo de productos</h1><p><?= htmlspecialchars($error) ?></p>
<?php foreach ($productos as $p): ?>
<p><?= htmlspecialchars($p['producto']) ?> — $<?= number_format((float)$p['precio'],2,',','.') ?>
<form method="post" style="display:inline"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button name="accion" value="agregar">Agregar al carrito</button></form></p>
<?php endforeach; ?>
<h2>Mi carrito</h2>
<?php foreach ($productos as $p): ?>
<?php $cantidad = $_SESSION['carrito'][$p['id']] ?? 0; if (!$cantidad) continue;
$subtotal = $cantidad * (float)$p['precio']; $total += $subtotal; ?>
<p><?= htmlspecialchars($p['producto']) ?> × <?= $cantidad ?> — $<?= number_format($subtotal,2,',','.') ?>
<form method="post" style="display:inline"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button name="accion" value="quitar">Quitar uno</button></form></p>
<?php endforeach; ?>
<p><strong>Total: $<?= number_format($total,2,',','.') ?></strong></p>
<form method="post"><button name="accion" value="vaciar">Vaciar carrito</button></form>
</body></html>
