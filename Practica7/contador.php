<?php
$visitasAnteriores = max(0, (int) ($_COOKIE['contador'] ?? 0));
$visitas = $visitasAnteriores + 1;
setcookie('contador', (string) $visitas, time() + 60 * 60 * 24 * 365, '/');
?><!doctype html><html lang="es"><head><meta charset="UTF-8"><title>Ejercicio 2</title></head><body>
<?php if ($visitasAnteriores === 0): ?>
<h1>¡Bienvenido por primera vez!</h1>
<?php else: ?>
<h1>Visitaste esta página <?= $visitas ?> veces.</h1>
<?php endif; ?>
<p>Recargá para incrementar el contador guardado en una cookie.</p>
</body></html>
