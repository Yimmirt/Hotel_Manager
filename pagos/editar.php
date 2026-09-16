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

/* Obtener pago */
$sql = "
    SELECT *
    FROM pagos
    WHERE id_pago = :id
";

$consulta = $conexion->prepare($sql);

$consulta->execute([
    ":id" => $id
]);

$pago = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$pago) {
    die("Pago no encontrado.");
}

/* Obtener reservaciones */
$sqlReservaciones = "
    SELECT
        r.id_reservacion,
        c.nombre_completo AS cliente,
        h.numero_habitacion
    FROM reservaciones r
    INNER JOIN clientes c
        ON r.id_cliente = c.id_cliente
    INNER JOIN habitaciones h
        ON r.id_habitacion = h.id_habitacion
    ORDER BY r.id_reservacion
";

$consultaReservaciones = $conexion->query($sqlReservaciones);

$reservaciones = $consultaReservaciones->fetchAll(PDO::FETCH_ASSOC);

$mensaje = "";

/* Actualizar pago */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_reservacion = $_POST["id_reservacion"];
    $monto = $_POST["monto"];
    $metodo_pago = $_POST["metodo_pago"];
    $estado = $_POST["estado"];
    $referencia = trim($_POST["referencia"]);

    try {

        $sql = "
            UPDATE pagos
            SET
                id_reservacion = :id_reservacion,
                monto = :monto,
                metodo_pago = :metodo_pago,
                estado = :estado,
                referencia = :referencia
            WHERE id_pago = :id
        ";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":id_reservacion" => $id_reservacion,
            ":monto" => $monto,
            ":metodo_pago" => $metodo_pago,
            ":estado" => $estado,
            ":referencia" => $referencia,
            ":id" => $id
        ]);

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "Error al actualizar pago: " . $error->getMessage();

    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pago | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <form method="POST">
            <h1 style="margin-top: 0; margin-bottom: 16px;">Editar pago</h1>

            <div style="margin-bottom: 18px;">
                <a href="listar.php">Volver a pagos</a>
            </div>

            <?php if ($mensaje != ""): ?>
                <p class="muted"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <label>Reservación</label>
            <select name="id_reservacion" required>
                <?php foreach ($reservaciones as $reservacion): ?>
                    <option value="<?= $reservacion["id_reservacion"] ?>" <?= $reservacion["id_reservacion"] == $pago["id_reservacion"] ? "selected" : "" ?>>
                        Reservación #<?= $reservacion["id_reservacion"] ?> - <?= htmlspecialchars($reservacion["cliente"]) ?> - Habitación <?= htmlspecialchars($reservacion["numero_habitacion"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Monto</label>
            <input type="number" name="monto" step="0.01" min="0" value="<?= htmlspecialchars($pago["monto"]) ?>" required>

            <label>Método de pago</label>
            <select name="metodo_pago" required>
                <option value="Efectivo" <?= $pago["metodo_pago"] === "Efectivo" ? "selected" : "" ?>>Efectivo</option>
                <option value="Tarjeta" <?= $pago["metodo_pago"] === "Tarjeta" ? "selected" : "" ?>>Tarjeta</option>
                <option value="Transferencia" <?= $pago["metodo_pago"] === "Transferencia" ? "selected" : "" ?>>Transferencia</option>
            </select>

            <label>Estado</label>
            <select name="estado" required>
                <option value="Pagado" <?= $pago["estado"] === "Pagado" ? "selected" : "" ?>>Pagado</option>
                <option value="Pendiente" <?= $pago["estado"] === "Pendiente" ? "selected" : "" ?>>Pendiente</option>
                <option value="Cancelado" <?= $pago["estado"] === "Cancelado" ? "selected" : "" ?>>Cancelado</option>
            </select>

            <label>Referencia</label>
            <input type="text" name="referencia" value="<?= htmlspecialchars($pago["referencia"] ?? "") ?>">

            <button type="submit">Guardar cambios</button>
        </form>
    </main>
</body>

</html>
