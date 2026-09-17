<?php

$host = "127.0.0.1";
$puerto = "5432";
$bd = "Hotel Manager";
$usuario = "postgres";
$contrasena = "01090314";

try {

    $conexion = new PDO(
        "pgsql:host=$host;port=$puerto;dbname='$bd'",
        $usuario,
        $contrasena
    );

    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $error) {

    die("Error de conexión: " . $error->getMessage());

}
?>