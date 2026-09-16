<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . "/../config/conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio = $_POST["precio"];
    $disponibilidad = isset($_POST["disponibilidad"]) ? true : false;

    try {

        $sql = "
            INSERT INTO servicios
            (
                nombre,
                descripcion,
                precio,
                disponibilidad
            )
            VALUES
            (
                :nombre,
                :descripcion,
                :precio,
                :disponibilidad
            )
        ";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":nombre" => $nombre,
            ":descripcion" => $descripcion,
            ":precio" => $precio,
            ":disponibilidad" => $disponibilidad
        ]);

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "Error al registrar servicio: " . $error->getMessage();

    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Servicio | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <form method="POST">
            <h1 style="margin-top: 0; margin-bottom: 16px;">Agregar nuevo servicio</h1>

            <div style="margin-bottom: 18px;">
                <a href="listar.php">Volver a servicios</a>
            </div>

            <?php if ($mensaje != ""): ?>
                <p class="muted"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <label>Nombre del servicio</label>
            <input type="text" name="nombre" required>

            <label>Descripción</label>
            <textarea name="descripcion" rows="4"></textarea>

            <label>Precio</label>
            <input type="number" name="precio" step="0.01" min="0" required>

            <label>
                <input type="checkbox" name="disponibilidad" checked>
                Disponible
            </label>

            <button type="submit">Registrar servicio</button>
        </form>
    </main>
</body>
</html>