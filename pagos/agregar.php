<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . "/../config/conexion.php";

$mensaje = "";

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


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_reservacion = $_POST["id_reservacion"];
    $monto = $_POST["monto"];
    $metodo_pago = $_POST["metodo_pago"];
    $estado = $_POST["estado"];
    $referencia = trim($_POST["referencia"]);

    try {

        $sql = "
            INSERT INTO pagos
            (
                id_reservacion,
                monto,
                metodo_pago,
                estado,
                referencia
            )
            VALUES
            (
                :id_reservacion,
                :monto,
                :metodo_pago,
                :estado,
                :referencia
            )
        ";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":id_reservacion" => $id_reservacion,
            ":monto" => $monto,
            ":metodo_pago" => $metodo_pago,
            ":estado" => $estado,
            ":referencia" => $referencia
        ]);

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "Error al registrar pago: " . $error->getMessage();

    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Pago | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <form method="POST">
            <h1 style="margin-top: 0; margin-bottom: 16px;">Registrar nuevo pago</h1>

            <div style="margin-bottom: 18px;">
                <a href="listar.php">Volver a pagos</a>
            </div>

            <?php if ($mensaje != ""): ?>
                <p class="muted"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <label>Reservación</label>
            <select name="id_reservacion" required>
                <option value="">Selecciona una reservación</option>

                <?php foreach ($reservaciones as $reservacion): ?>
                    <option value="<?= $reservacion["id_reservacion"] ?>">
                        Reservación #<?= $reservacion["id_reservacion"] ?> - <?= htmlspecialchars($reservacion["cliente"]) ?> - Habitación <?= htmlspecialchars($reservacion["numero_habitacion"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Monto</label>
            <input type="number" name="monto" step="0.01" min="0" required>

            <label>Método de pago</label>
            <select name="metodo_pago" required>
                <option value="">Selecciona</option>
                <option value="Efectivo">Efectivo</option>
                <option value="Tarjeta">Tarjeta</option>
                <option value="Transferencia">Transferencia</option>
            </select>

            <label>Estado</label>
            <select name="estado" required>
                <option value="Pagado">Pagado</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Cancelado">Cancelado</option>
            </select>

            <label>Referencia</label>
            <input type="text" name="referencia" placeholder="Ejemplo: REF016">

            <button type="submit">Registrar pago</button>
        </form>
    </main>
</body>
</html>