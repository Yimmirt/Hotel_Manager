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

/* Obtener reservación */
$sql = "
    SELECT *
    FROM reservaciones
    WHERE id_reservacion = :id
";

$consulta = $conexion->prepare($sql);

$consulta->execute([
    ":id" => $id
]);

$reservacion = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$reservacion) {
    die("Reservación no encontrada.");
}


/* Obtener clientes */
$sqlClientes = "
    SELECT id_cliente, nombre_completo
    FROM clientes
    ORDER BY nombre_completo
";

$consultaClientes = $conexion->query($sqlClientes);

$clientes = $consultaClientes->fetchAll(PDO::FETCH_ASSOC);


/* Obtener habitaciones */
$sqlHabitaciones = "
    SELECT
        id_habitacion,
        numero_habitacion,
        tipo,
        estado
    FROM habitaciones
    ORDER BY numero_habitacion
";

$consultaHabitaciones = $conexion->query($sqlHabitaciones);

$habitaciones = $consultaHabitaciones->fetchAll(PDO::FETCH_ASSOC);


$mensaje = "";


/* Actualizar */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_cliente = $_POST["id_cliente"];
    $id_habitacion = $_POST["id_habitacion"];
    $fecha_entrada = $_POST["fecha_entrada"];
    $fecha_salida = $_POST["fecha_salida"];
    $numero_huespedes = $_POST["numero_huespedes"];
    $estado = $_POST["estado"];

    try {

        $sql = "
            UPDATE reservaciones
            SET
                id_cliente = :id_cliente,
                id_habitacion = :id_habitacion,
                fecha_entrada = :fecha_entrada,
                fecha_salida = :fecha_salida,
                numero_huespedes = :numero_huespedes,
                estado = :estado
            WHERE id_reservacion = :id
        ";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":id_cliente" => $id_cliente,
            ":id_habitacion" => $id_habitacion,
            ":fecha_entrada" => $fecha_entrada,
            ":fecha_salida" => $fecha_salida,
            ":numero_huespedes" => $numero_huespedes,
            ":estado" => $estado,
            ":id" => $id
        ]);

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "Error al actualizar reservación: " . $error->getMessage();

    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reservación | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <form method="POST">
            <h1 style="margin-top: 0; margin-bottom: 16px;">Editar reservación</h1>

            <div style="margin-bottom: 18px;">
                <a href="listar.php">Volver a reservaciones</a>
            </div>

            <?php if ($mensaje != ""): ?>
                <p class="muted"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <label>Cliente</label>
            <select name="id_cliente" required>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente["id_cliente"] ?>" <?= $cliente["id_cliente"] == $reservacion["id_cliente"] ? "selected" : "" ?>>
                        <?= htmlspecialchars($cliente["nombre_completo"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Habitación</label>
            <select name="id_habitacion" required>
                <?php foreach ($habitaciones as $habitacion): ?>
                    <option value="<?= $habitacion["id_habitacion"] ?>" <?= $habitacion["id_habitacion"] == $reservacion["id_habitacion"] ? "selected" : "" ?>>
                        Habitación <?= htmlspecialchars($habitacion["numero_habitacion"]) ?> - <?= htmlspecialchars($habitacion["tipo"]) ?> - <?= htmlspecialchars($habitacion["estado"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Fecha de entrada</label>
            <input type="date" name="fecha_entrada" value="<?= htmlspecialchars($reservacion["fecha_entrada"]) ?>" required>

            <label>Fecha de salida</label>
            <input type="date" name="fecha_salida" value="<?= htmlspecialchars($reservacion["fecha_salida"]) ?>" required>

            <label>Número de huéspedes</label>
            <input type="number" name="numero_huespedes" min="1" value="<?= htmlspecialchars($reservacion["numero_huespedes"]) ?>" required>

            <label>Estado</label>
            <select name="estado" required>
                <option value="Pendiente" <?= $reservacion["estado"] === "Pendiente" ? "selected" : "" ?>>Pendiente</option>
                <option value="Confirmada" <?= $reservacion["estado"] === "Confirmada" ? "selected" : "" ?>>Confirmada</option>
                <option value="En curso" <?= $reservacion["estado"] === "En curso" ? "selected" : "" ?>>En curso</option>
                <option value="Finalizada" <?= $reservacion["estado"] === "Finalizada" ? "selected" : "" ?>>Finalizada</option>
                <option value="Cancelada" <?= $reservacion["estado"] === "Cancelada" ? "selected" : "" ?>>Cancelada</option>
            </select>

            <button type="submit">Guardar cambios</button>
        </form>
    </main>
</body>
</html>