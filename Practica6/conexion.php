<?php
// Configurar estas credenciales según la instalación de MySQL.
$servidor = 'localhost';
$usuario = 'root';
$clave = '';
$base = 'Capitales';
$conexion = new mysqli($servidor, $usuario, $clave, $base);
if ($conexion->connect_error) {
    http_response_code(500);
    exit('No se pudo conectar a la base de datos. Revisar conexion.php');
}
$conexion->set_charset('utf8mb4');
