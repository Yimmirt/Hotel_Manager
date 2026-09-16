<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . "/../config/conexion.php";

if (!isset($_GET["id"])) {
    header("Location: listar.php");
    exit;
}

$id = (int) $_GET["id"];

$sql = "
    SELECT *
    FROM servicios
    WHERE id_servicio = :id
";

$consulta = $conexion->prepare($sql);

$consulta->execute([
    ":id" => $id
]);

$servicio = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$servicio) {
    die("Servicio no encontrado.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $sql = "
        DELETE FROM servicios
        WHERE id_servicio = :id
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ":id" => $id
    ]);

    header("Location: listar.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Servicio | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <div class="confirm-box">
            <h1 style="margin-top: 0;">Eliminar servicio</h1>
            <p>¿Estás seguro de que deseas eliminar este servicio?</p>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($servicio["nombre"]) ?></p>
            <p><strong>Precio:</strong> $<?= number_format($servicio["precio"], 2) ?></p>
            <p><strong>Descripción:</strong> <?= htmlspecialchars($servicio["descripcion"] ?? "") ?></p>

            <form method="POST">
                <button type="submit">Sí, eliminar servicio</button>
            </form>
            <br>
            <a href="listar.php">Cancelar</a>
        </div>
    </main>
</body>
</html>