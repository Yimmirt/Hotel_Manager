<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . "/../config/conexion.php";

$mensaje = "";

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
    SELECT id_habitacion, numero_habitacion, tipo, capacidad, estado
    FROM habitaciones
    ORDER BY numero_habitacion
";

$consultaHabitaciones = $conexion->query($sqlHabitaciones);
$habitaciones = $consultaHabitaciones->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_cliente = $_POST["id_cliente"];
    $id_habitacion = $_POST["id_habitacion"];
    $fecha_entrada = $_POST["fecha_entrada"];
    $fecha_salida = $_POST["fecha_salida"];
    $numero_huespedes = $_POST["numero_huespedes"];
    $estado = $_POST["estado"];

    try {

        $sql = "
            INSERT INTO reservaciones
            (
                id_cliente,
                id_habitacion,
                fecha_entrada,
                fecha_salida,
                numero_huespedes,
                estado
            )
            VALUES
            (
                :id_cliente,
                :id_habitacion,
                :fecha_entrada,
                :fecha_salida,
                :numero_huespedes,
                :estado
            )
        ";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":id_cliente" => $id_cliente,
            ":id_habitacion" => $id_habitacion,
            ":fecha_entrada" => $fecha_entrada,
            ":fecha_salida" => $fecha_salida,
            ":numero_huespedes" => $numero_huespedes,
            ":estado" => $estado
        ]);

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "Error al registrar reservación: " . $error->getMessage();

    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Reservación | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <form method="POST">
            <h1 style="margin-top: 0; margin-bottom: 16px;">Nueva reservación</h1>

            <div style="margin-bottom: 18px;">
                <a href="listar.php">Volver a reservaciones</a>
            </div>

            <?php if ($mensaje != ""): ?>
                <p class="muted"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <label>Cliente</label>
            <select name="id_cliente" required>
                <option value="">Selecciona un cliente</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente["id_cliente"] ?>"><?= htmlspecialchars($cliente["nombre_completo"]) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Habitación</label>
            <select name="id_habitacion" required>
                <option value="">Selecciona una habitación</option>
                <?php foreach ($habitaciones as $habitacion): ?>
                    <option value="<?= $habitacion["id_habitacion"] ?>">Habitación <?= htmlspecialchars($habitacion["numero_habitacion"]) ?> - <?= htmlspecialchars($habitacion["tipo"]) ?> - <?= htmlspecialchars($habitacion["estado"]) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Fecha de entrada</label>
            <input type="date" name="fecha_entrada" required>

            <label>Fecha de salida</label>
            <input type="date" name="fecha_salida" required>

            <label>Número de huéspedes</label>
            <input type="number" name="numero_huespedes" min="1" required>

            <label>Estado</label>
            <select name="estado" required>
                <option value="Pendiente">Pendiente</option>
                <option value="Confirmada">Confirmada</option>
                <option value="En curso">En curso</option>
                <option value="Finalizada">Finalizada</option>
                <option value="Cancelada">Cancelada</option>
            </select>

            <button type="submit">Registrar reservación</button>
        </form>
    </main>
</body>
</html>