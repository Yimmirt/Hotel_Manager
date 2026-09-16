<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . "/../config/conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre_completo"]);
    $telefono = trim($_POST["telefono"]);
    $correo = trim($_POST["correo"]);
    $direccion = trim($_POST["direccion"]);
    $ciudad = trim($_POST["ciudad"]);

    try {

        $sql = "
            INSERT INTO clientes
            (nombre_completo, telefono, correo, direccion, ciudad)
            VALUES
            (:nombre, :telefono, :correo, :direccion, :ciudad)
        ";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":nombre" => $nombre,
            ":telefono" => $telefono,
            ":correo" => $correo,
            ":direccion" => $direccion,
            ":ciudad" => $ciudad
        ]);

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "Error al registrar cliente: " . $error->getMessage();

    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cliente | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <form method="POST">
            <h1 style="margin-top: 0; margin-bottom: 16px;">Registrar nuevo cliente</h1>

            <div style="margin-bottom: 18px;">
                <a href="listar.php">Volver a clientes</a>
            </div>

            <?php if ($mensaje != ""): ?>
                <p class="muted"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <label>Nombre completo</label>
            <input type="text" name="nombre_completo" required>

            <label>Teléfono</label>
            <input type="text" name="telefono" required>

            <label>Correo</label>
            <input type="email" name="correo" required>

            <label>Dirección</label>
            <input type="text" name="direccion">

            <label>Ciudad</label>
            <input type="text" name="ciudad">

            <button type="submit">Registrar cliente</button>
        </form>
    </main>
</body>
</html>