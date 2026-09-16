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
    SELECT
        p.id_pago,
        p.monto,
        p.metodo_pago,
        p.estado,
        p.referencia,
        c.nombre_completo AS cliente,
        h.numero_habitacion
    FROM pagos p
    INNER JOIN reservaciones r
        ON p.id_reservacion = r.id_reservacion
    INNER JOIN clientes c
        ON r.id_cliente = c.id_cliente
    INNER JOIN habitaciones h
        ON r.id_habitacion = h.id_habitacion
    WHERE p.id_pago = :id
";

$consulta = $conexion->prepare($sql);

$consulta->execute([
    ":id" => $id
]);

$pago = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$pago) {
    die("Pago no encontrado.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $sql = "
        DELETE FROM pagos
        WHERE id_pago = :id
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
    <title>Eliminar Pago | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <div class="confirm-box">
            <h1 style="margin-top: 0;">Eliminar pago</h1>

            <p>¿Estás seguro de que deseas eliminar este pago?</p>
            <p><strong>Cliente:</strong> <?= htmlspecialchars($pago["cliente"]) ?></p>
            <p><strong>Habitación:</strong> <?= htmlspecialchars($pago["numero_habitacion"]) ?></p>
            <p><strong>Monto:</strong> $<?= number_format($pago["monto"], 2) ?></p>
            <p><strong>Método:</strong> <?= htmlspecialchars($pago["metodo_pago"]) ?></p>
            <p><strong>Estado:</strong> <?= htmlspecialchars($pago["estado"]) ?></p>
            <p><strong>Referencia:</strong> <?= htmlspecialchars($pago["referencia"] ?? "") ?></p>

            <form method="POST">
                <button type="submit">Sí, eliminar pago</button>
            </form>

            <br>
            <a href="listar.php">Cancelar</a>
        </div>
    </main>
</body>
</html>