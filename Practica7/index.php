<?php
$estilos = ['claro', 'oscuro', 'azul'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($_POST['estilo'] ?? '', $estilos, true)) {
    setcookie('estilo', $_POST['estilo'], time() + 60 * 60 * 24 * 30, '/');
    header('Location: index.php');
    exit;
}
$estilo = in_array($_COOKIE['estilo'] ?? '', $estilos, true) ? $_COOKIE['estilo'] : 'claro';
?>
<!doctype html><html lang="es"><head><meta charset="UTF-8"><title>Ejercicio 1 - Estilos</title>
<style>
body{font-family:Arial,sans-serif;max-width:700px;margin:50px auto;padding:25px}
body.claro{background:#fff;color:#222}body.oscuro{background:#222;color:#fff}
body.azul{background:#d9eafd;color:#10375c}button{padding:8px 18px}
</style></head><body class="<?= htmlspecialchars($estilo) ?>">
<h1>Elegí el estilo de la página</h1>
<form method="post"><label for="estilo">Tema:</label>
<select name="estilo" id="estilo">
<?php foreach($estilos as $opcion): ?><option value="<?= $opcion ?>" <?= $opcion === $estilo ? 'selected' : '' ?>><?= ucfirst($opcion) ?></option><?php endforeach; ?>
</select><button type="submit">Guardar estilo</button></form>
<p>Tu elección se guarda en una cookie y se conserva en las próximas visitas.</p>
</body></html>
