<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . "/../config/conexion.php";

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
            INSERT INTO habitaciones
            (
                numero_habitacion,
                tipo,
                capacidad,
                precio_noche,
                estado,
                descripcion
            )
            VALUES
            (
                :numero,
                :tipo,
                :capacidad,
                :precio,
                :estado,
                :descripcion
            )
        ";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":numero" => $numero,
            ":tipo" => $tipo,
            ":capacidad" => $capacidad,
            ":precio" => $precio,
            ":estado" => $estado,
            ":descripcion" => $descripcion
        ]);

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "Error al registrar habitación: " . $error->getMessage();

    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Habitación | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <form method="POST">
            <h1 style="margin-top: 0; margin-bottom: 16px;">Registrar nueva habitación</h1>

            <div style="margin-bottom: 18px;">
                <a href="listar.php">Volver a habitaciones</a>
            </div>

            <?php if ($mensaje != ""): ?>
                <p class="muted"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <label>Número de habitación</label>
            <input type="number" name="numero_habitacion" required>

            <label>Tipo</label>
            <select name="tipo" required>
                <option value="">Selecciona</option>
                <option value="Sencilla">Sencilla</option>
                <option value="Doble">Doble</option>
                <option value="Suite">Suite</option>
                <option value="Ejecutiva">Ejecutiva</option>
                <option value="Familiar">Familiar</option>
            </select>

            <label>Capacidad</label>
            <input type="number" name="capacidad" min="1" required>

            <label>Precio por noche</label>
            <input type="number" name="precio_noche" step="0.01" min="0" required>

            <label>Estado</label>
            <select name="estado" required>
                <option value="Disponible">Disponible</option>
                <option value="Ocupada">Ocupada</option>
                <option value="Mantenimiento">Mantenimiento</option>
            </select>

            <label>Descripción</label>
            <textarea name="descripcion" rows="4"></textarea>

            <button type="submit">Registrar habitación</button>
        </form>
    </main>
</body>
</html>