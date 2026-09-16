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

$sql = "SELECT * FROM clientes WHERE id_cliente = :id";

$consulta = $conexion->prepare($sql);

$consulta->execute([
    ":id" => $id
]);

$cliente = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    die("Cliente no encontrado.");
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {

        $sql = "DELETE FROM clientes WHERE id_cliente = :id";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":id" => $id
        ]);

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "No se puede eliminar este cliente porque tiene reservaciones relacionadas.";

    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Cliente | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <div class="confirm-box">
            <h1 style="margin-top: 0;">Eliminar cliente</h1>

            <?php if ($mensaje != ""): ?>
                <p><?= htmlspecialchars($mensaje) ?></p>
                <a href="listar.php">Volver a clientes</a>
            <?php else: ?>
                <p>¿Estás seguro de que quieres eliminar a:</p>
                <strong><?= htmlspecialchars($cliente["nombre_completo"]) ?></strong>
                <br><br>
                <form method="POST">
                    <button type="submit">Sí, eliminar cliente</button>
                </form>
                <br>
                <a href="listar.php">Cancelar</a>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>