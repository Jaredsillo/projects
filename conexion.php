<?php
/* conexion de mi lap
$host = '127.0.0.2';
$port = '3310';
$db   = 'practica1';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$conexion = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $conexion = new PDO($conexion, $user, $pass, $options);
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}*/

// conexion trabajo
$host = '';
$db   = 'prueba';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$conexion = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $conexion = new PDO($conexion, $user, $pass, $options);
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}
?>
