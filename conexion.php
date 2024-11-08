<?php
$host = "localhost";
$usuario = "root";
$contrasenia = "123456";
$base_de_datos = "api";

// Crear conexión
$mysqli = new mysqli($host, $usuario, $contrasenia, $base_de_datos);

// Verificar la conexión
if ($mysqli->connect_errno) {
    die("Error de conexión: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

// Establecer el conjunto de caracteres a UTF-8
$mysqli->set_charset("utf8mb4");
?>
