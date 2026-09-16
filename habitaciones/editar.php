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

    $numero = $_POST["numero_habitacion"];
    $tipo = trim($_POST["tipo"]);
    $capacidad = $_POST["capacidad"];
    $precio = $_POST["precio_noche"];
    $estado = $_POST["estado"];
    $descripcion = trim($_POST["descripcion"]);

    try {

        $sql = "
            UPDATE habitaciones
            SET
                numero_habitacion = :numero,
                tipo = :tipo,
                capacidad = :capacidad,
                precio_noche = :precio,
                estado = :estado,
                descripcion = :descripcion
            WHERE id_habitacion = :id
        ";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":numero" => $numero,
            ":tipo" => $tipo,
            ":capacidad" => $capacidad,
            ":precio" => $precio,
            ":estado" => $estado,
            ":descripcion" => $descripcion,
            ":id" => $id
        ]);

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "Error al actualizar habitación: " . $error->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Habitación | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <form method="POST">
            <h1 style="margin-top: 0; margin-bottom: 16px;">Editar habitación</h1>

            <div style="margin-bottom: 18px;">
                <a href="listar.php">Volver a habitaciones</a>
            </div>

            <?php if ($mensaje != ""): ?>
                <p class="muted"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <label>Número de habitación</label>
            <input type="number" name="numero_habitacion" value="<?= htmlspecialchars($habitacion["numero_habitacion"]) ?>" required>

            <label>Tipo</label>
            <select name="tipo" required>
                <option value="Sencilla" <?= $habitacion["tipo"] === "Sencilla" ? "selected" : "" ?>>Sencilla</option>
                <option value="Doble" <?= $habitacion["tipo"] === "Doble" ? "selected" : "" ?>>Doble</option>
                <option value="Suite" <?= $habitacion["tipo"] === "Suite" ? "selected" : "" ?>>Suite</option>
                <option value="Ejecutiva" <?= $habitacion["tipo"] === "Ejecutiva" ? "selected" : "" ?>>Ejecutiva</option>
                <option value="Familiar" <?= $habitacion["tipo"] === "Familiar" ? "selected" : "" ?>>Familiar</option>
            </select>

            <label>Capacidad</label>
            <input type="number" name="capacidad" min="1" value="<?= htmlspecialchars($habitacion["capacidad"]) ?>" required>

            <label>Precio por noche</label>
            <input type="number" name="precio_noche" step="0.01" min="0" value="<?= htmlspecialchars($habitacion["precio_noche"]) ?>" required>

            <label>Estado</label>
            <select name="estado" required>
                <option value="Disponible" <?= $habitacion["estado"] === "Disponible" ? "selected" : "" ?>>Disponible</option>
                <option value="Ocupada" <?= $habitacion["estado"] === "Ocupada" ? "selected" : "" ?>>Ocupada</option>
                <option value="Mantenimiento" <?= $habitacion["estado"] === "Mantenimiento" ? "selected" : "" ?>>Mantenimiento</option>
            </select>

            <label>Descripción</label>
            <textarea name="descripcion" rows="4"><?= htmlspecialchars($habitacion["descripcion"] ?? "") ?></textarea>

            <button type="submit">Guardar cambios</button>
        </form>
    </main>
</body>
</html>