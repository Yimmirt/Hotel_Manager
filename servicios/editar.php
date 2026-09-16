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

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio = $_POST["precio"];
    $disponibilidad = isset($_POST["disponibilidad"]) ? true : false;

    try {

        $sql = "
            UPDATE servicios
            SET
                nombre = :nombre,
                descripcion = :descripcion,
                precio = :precio,
                disponibilidad = :disponibilidad
            WHERE id_servicio = :id
        ";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":nombre" => $nombre,
            ":descripcion" => $descripcion,
            ":precio" => $precio,
            ":disponibilidad" => $disponibilidad,
            ":id" => $id
        ]);

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "Error al actualizar servicio: " . $error->getMessage();

    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Servicio | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <form method="POST">
            <h1 style="margin-top: 0; margin-bottom: 16px;">Editar servicio</h1>

            <div style="margin-bottom: 18px;">
                <a href="listar.php">Volver a servicios</a>
            </div>

            <?php if ($mensaje != ""): ?>
                <p class="muted"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <label>Nombre del servicio</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($servicio["nombre"]) ?>" required>

            <label>Descripción</label>
            <textarea name="descripcion" rows="4"><?= htmlspecialchars($servicio["descripcion"] ?? "") ?></textarea>

            <label>Precio</label>
            <input type="number" name="precio" step="0.01" min="0" value="<?= htmlspecialchars($servicio["precio"]) ?>" required>

            <label>
                <input type="checkbox" name="disponibilidad" <?= $servicio["disponibilidad"] ? "checked" : "" ?>>
                Disponible
            </label>

            <button type="submit">Guardar cambios</button>
        </form>
    </main>
</body>
</html>