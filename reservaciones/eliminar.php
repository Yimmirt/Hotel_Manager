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

/* Obtener reservación con cliente y habitación */
$sql = "
    SELECT
        r.id_reservacion,
        c.nombre_completo AS cliente,
        h.numero_habitacion,
        r.fecha_entrada,
        r.fecha_salida,
        r.estado
    FROM reservaciones r
    INNER JOIN clientes c
        ON r.id_cliente = c.id_cliente
    INNER JOIN habitaciones h
        ON r.id_habitacion = h.id_habitacion
    WHERE r.id_reservacion = :id
";

$consulta = $conexion->prepare($sql);

$consulta->execute([
    ":id" => $id
]);

$reservacion = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$reservacion) {
    die("Reservación no encontrada.");
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {

        $sql = "
            DELETE FROM reservaciones
            WHERE id_reservacion = :id
        ";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":id" => $id
        ]);

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "No se puede eliminar esta reservación porque tiene pagos relacionados.";

    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Reservación | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <div class="confirm-box">
            <h1 style="margin-top: 0;">Eliminar reservación</h1>

            <?php if ($mensaje != ""): ?>
                <p><?= htmlspecialchars($mensaje) ?></p>
                <a href="listar.php">Volver a reservaciones</a>
            <?php else: ?>
                <p>¿Estás seguro de que deseas eliminar esta reservación?</p>
                <p><strong>Cliente:</strong> <?= htmlspecialchars($reservacion["cliente"]) ?></p>
                <p><strong>Habitación:</strong> <?= htmlspecialchars($reservacion["numero_habitacion"]) ?></p>
                <p><strong>Entrada:</strong> <?= htmlspecialchars($reservacion["fecha_entrada"]) ?></p>
                <p><strong>Salida:</strong> <?= htmlspecialchars($reservacion["fecha_salida"]) ?></p>
                <p><strong>Estado:</strong> <?= htmlspecialchars($reservacion["estado"]) ?></p>

                <form method="POST">
                    <button type="submit">Sí, eliminar reservación</button>
                </form>
                <br>
                <a href="listar.php">Cancelar</a>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>