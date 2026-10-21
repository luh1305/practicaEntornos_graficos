<?php
session_start();
require __DIR__ . '/conexion.php';
function escapar($valor): string { return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8'); }
if (empty($_SESSION['token'])) { $_SESSION['token'] = bin2hex(random_bytes(16)); }
$token = $_SESSION['token'];
$accion = $_GET['accion'] ?? 'menu';
$permitidas = ['menu','alta','baja','modificar','listado','paginado'];
if (!in_array($accion, $permitidas, true)) { $accion = 'menu'; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($token, $_POST['token'] ?? '')) { http_response_code(403); exit('Solicitud inválida'); }
    $operacion = $_POST['operacion'] ?? '';
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]);
    if ($operacion === 'eliminar' && $id) {
        $stmt = $conexion->prepare('DELETE FROM Ciudades WHERE id = ?');
        $stmt->bind_param('i', $id); $stmt->execute(); $stmt->close();
        header('Location: index.php?accion=listado'); exit;
    }
    if (in_array($operacion, ['crear','actualizar'], true)) {
        $ciudad = trim($_POST['ciudad'] ?? '');
        $pais = trim($_POST['pais'] ?? '');
        $habitantes = filter_var($_POST['habitantes'] ?? null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>0]]);
        $superficie = filter_var($_POST['superficie'] ?? null, FILTER_VALIDATE_FLOAT);
        $metro = isset($_POST['tieneMetro']) ? 1 : 0;
        if ($ciudad === '' || $pais === '' || mb_strlen($ciudad)>100 || mb_strlen($pais)>100 || $habitantes === false || $superficie === false || $superficie < 0 || ($operacion==='actualizar' && !$id)) {
            $error = 'Revisá los datos ingresados.';
        } else {
            if ($operacion === 'crear') {
                $stmt = $conexion->prepare('INSERT INTO Ciudades (ciudad,pais,habitantes,superficie,tieneMetro) VALUES (?,?,?,?,?)');
                $stmt->bind_param('ssidi', $ciudad,$pais,$habitantes,$superficie,$metro);
            } else {
                $stmt = $conexion->prepare('UPDATE Ciudades SET ciudad=?,pais=?,habitantes=?,superficie=?,tieneMetro=? WHERE id=?');
                $stmt->bind_param('ssidii', $ciudad,$pais,$habitantes,$superficie,$metro,$id);
            }
            $stmt->execute(); $stmt->close();
            header('Location: index.php?accion=listado'); exit;
        }
    }
}
$ciudadActual = null;
if (in_array($accion, ['modificar','baja'], true)) {
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]);
    if ($id) {
        $stmt = $conexion->prepare('SELECT * FROM Ciudades WHERE id=?');
        $stmt->bind_param('i',$id); $stmt->execute();
        $ciudadActual = $stmt->get_result()->fetch_assoc(); $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Práctica 6 - ABML Ciudades</title>
<style>body{font:16px Arial,sans-serif;max-width:980px;margin:30px auto;padding:0 14px;line-height:1.45} nav a{margin-right:14px}table{border-collapse:collapse;width:100%;margin-top:15px}th,td{border:1px solid #999;padding:9px;text-align:left}label{display:block;margin:12px 0}input{padding:7px;max-width:100%}button{padding:9px 16px;cursor:pointer}.error{color:#b00020}.acciones{white-space:nowrap}</style>
</head><body>
<h1>ABML de Ciudades</h1>
<nav><a href="index.php">Menú</a><a href="?accion=alta">Alta</a><a href="?accion=listado">Listado completo</a><a href="?accion=paginado">Listado paginado</a></nav><hr>
<?php if ($error): ?><p class="error"><?= escapar($error) ?></p><?php endif; ?>
<?php if ($accion === 'menu'): ?>
<p>Elegí una opción del menú para dar de alta, dar de baja, modificar o listar ciudades.</p>
<?php elseif ($accion === 'alta' || ($accion === 'modificar' && $ciudadActual)): ?>
<h2><?= $accion==='alta'?'Alta de ciudad':'Modificar ciudad' ?></h2>
<form method="post">
<input type="hidden" name="token" value="<?= escapar($token) ?>">
<input type="hidden" name="operacion" value="<?= $accion==='alta'?'crear':'actualizar' ?>">
<?php if ($ciudadActual): ?><input type="hidden" name="id" value="<?= (int)$ciudadActual['id'] ?>"><?php endif; ?>
<label>Ciudad <input name="ciudad" maxlength="100" required value="<?= escapar($_POST['ciudad'] ?? $ciudadActual['ciudad'] ?? '') ?>"></label>
<label>País <input name="pais" maxlength="100" required value="<?= escapar($_POST['pais'] ?? $ciudadActual['pais'] ?? '') ?>"></label>
<label>Habitantes <input name="habitantes" type="number" min="0" step="1" required value="<?= escapar($_POST['habitantes'] ?? $ciudadActual['habitantes'] ?? '') ?>"></label>
<label>Superficie <input name="superficie" type="number" min="0" step="0.01" required value="<?= escapar($_POST['superficie'] ?? $ciudadActual['superficie'] ?? '') ?>"></label>
<label><input name="tieneMetro" type="checkbox" <?= (int)($_POST['tieneMetro'] ?? $ciudadActual['tieneMetro'] ?? 0) === 1 ? 'checked' : '' ?>> Tiene metro</label>
<button type="submit">Guardar</button>
</form>
<?php elseif ($accion === 'baja' && $ciudadActual): ?>
<h2>Dar de baja</h2><p>¿Eliminar la ciudad <strong><?= escapar($ciudadActual['ciudad']) ?></strong>?</p>
<form method="post"><input type="hidden" name="token" value="<?= escapar($token) ?>"><input type="hidden" name="id" value="<?= (int)$ciudadActual['id'] ?>"><input type="hidden" name="operacion" value="eliminar"><button type="submit">Confirmar baja</button> <a href="?accion=listado">Cancelar</a></form>
<?php elseif (in_array($accion,['listado','paginado'],true)): ?>
<h2><?= $accion==='listado'?'Listado completo':'Listado con paginación' ?></h2>
<?php
if ($accion === 'paginado') {
    $porPagina=4;
    $total=(int)$conexion->query('SELECT COUNT(*) FROM Ciudades')->fetch_row()[0];
    $paginas=max(1,(int)ceil($total/$porPagina));
    $pagina=max(1,min($paginas,(int)($_GET['pagina'] ?? 1)));
    $offset=($pagina-1)*$porPagina;
    $stmt=$conexion->prepare('SELECT * FROM Ciudades ORDER BY id LIMIT ? OFFSET ?');
    $stmt->bind_param('ii',$porPagina,$offset); $stmt->execute();
    $filas=$stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close();
} else { $filas=$conexion->query('SELECT * FROM Ciudades ORDER BY id')->fetch_all(MYSQLI_ASSOC); }
?>
<table><thead><tr><th>ID</th><th>Ciudad</th><th>País</th><th>Habitantes</th><th>Superficie</th><th>Tiene metro</th><th>Acciones</th></tr></thead><tbody>
<?php foreach ($filas as $fila): ?><tr>
<td><?= (int)$fila['id'] ?></td><td><?= escapar($fila['ciudad']) ?></td><td><?= escapar($fila['pais']) ?></td>
<td><?= (int)$fila['habitantes'] ?></td><td><?= escapar($fila['superficie']) ?></td><td><?= $fila['tieneMetro']?'Sí':'No' ?></td>
<td class="acciones"><a href="?accion=modificar&amp;id=<?= (int)$fila['id'] ?>">Modificar</a> | <a href="?accion=baja&amp;id=<?= (int)$fila['id'] ?>">Baja</a></td></tr><?php endforeach; ?>
</tbody></table>
<?php if (!$filas): ?><p>No hay ciudades registradas.</p><?php endif; ?>
<?php if ($accion==='paginado'): ?><p>Página <?= $pagina ?> de <?= $paginas ?> — <?php for($i=1;$i<=$paginas;$i++): ?><a href="?accion=paginado&amp;pagina=<?= $i ?>"><?= $i ?></a> <?php endfor; ?></p><?php endif; ?>
<?php else: ?><p>No se encontró la ciudad solicitada. <a href="?accion=listado">Volver al listado</a></p><?php endif; ?>
</body></html>
