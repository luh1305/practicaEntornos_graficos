<?php
// Archivo incluido por visitas.php. Contador global persistido en texto.
$archivo = __DIR__ . '/contador.dat';
$manejador = fopen($archivo, 'c+');
if ($manejador === false) {
    echo 'No se pudo abrir el contador.';
    return;
}
if (!flock($manejador, LOCK_EX)) {
    fclose($manejador);
    echo 'No se pudo bloquear el contador.';
    return;
}
rewind($manejador);
$actual = trim(stream_get_contents($manejador));
$visitas = (int)$actual + 1;
rewind($manejador);
ftruncate($manejador, 0);
fwrite($manejador, (string)$visitas);
fflush($manejador);
flock($manejador, LOCK_UN);
fclose($manejador);
echo '<p>Cantidad de visitas: ' . $visitas . '</p>';
