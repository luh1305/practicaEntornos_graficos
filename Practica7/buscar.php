<?php
session_start();
unset($_SESSION['nombre']);
$mensaje = 'No se encontró un alumno con ese mail.';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && filter_var($_POST['mail'] ?? '', FILTER_VALIDATE_EMAIL)) {
    try {
        $bd = new mysqli('localhost', 'root', '', 'base2');
        $bd->set_charset('utf8mb4');
        $consulta = $bd->prepare('SELECT nombre FROM alumnos WHERE mail = ? LIMIT 1');
        $consulta->bind_param('s', $_POST['mail']);
        $consulta->execute();
        $resultado = $consulta->get_result()->fetch_assoc();
        if ($resultado) {
            $_SESSION['nombre'] = $resultado['nombre'];
            $mensaje = 'Alumno encontrado. Nombre guardado en la sesión.';
        }
    } catch (mysqli_sql_exception $e) {
        $mensaje = 'No se pudo consultar la base de datos. Revisá la conexión.';
    }
}
?><!doctype html><html lang="es"><head><meta charset="UTF-8"><title>Ejercicio 6</title></head><body>
<p><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></p>
<a href="bienvenida.php">Ir a la tercera página</a> | <a href="formulario.php">Volver</a>
</body></html>
