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

$sql = "SELECT * FROM habitaciones WHERE id_habitacion = :id";

$consulta = $conexion->prepare($sql);

$consulta->execute([
    ":id" => $id
]);

$habitacion = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$habitacion) {
    die("Habitación no encontrada.");
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {

        $sql = "DELETE FROM habitaciones WHERE id_habitacion = :id";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":id" => $id
        ]);

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "No se puede eliminar esta habitación porque tiene reservaciones relacionadas.";

    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Habitación | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <div class="confirm-box">
            <h1 style="margin-top: 0;">Eliminar habitación</h1>

            <?php if ($mensaje != ""): ?>
                <p><?= htmlspecialchars($mensaje) ?></p>
                <a href="listar.php">Volver a habitaciones</a>
            <?php else: ?>
                <p>¿Estás seguro de que quieres eliminar la habitación:</p>
                <strong><?= htmlspecialchars($habitacion["numero_habitacion"]) ?></strong>
                <br><br>
                <form method="POST">
                    <button type="submit">Sí, eliminar habitación</button>
                </form>
                <br>
                <a href="listar.php">Cancelar</a>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>