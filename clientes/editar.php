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

$id = $_GET["id"];

$sql = "SELECT * FROM clientes WHERE id_cliente = :id";

$consulta = $conexion->prepare($sql);

$consulta->execute([
    ":id" => $id
]);

$cliente = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    die("Cliente no encontrado.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre_completo"]);
    $telefono = trim($_POST["telefono"]);
    $correo = trim($_POST["correo"]);
    $direccion = trim($_POST["direccion"]);
    $ciudad = trim($_POST["ciudad"]);

    $sql = "
        UPDATE clientes
        SET
            nombre_completo = :nombre,
            telefono = :telefono,
            correo = :correo,
            direccion = :direccion,
            ciudad = :ciudad
        WHERE id_cliente = :id
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ":nombre" => $nombre,
        ":telefono" => $telefono,
        ":correo" => $correo,
        ":direccion" => $direccion,
        ":ciudad" => $ciudad,
        ":id" => $id
    ]);

    header("Location: listar.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <form method="POST">
            <h1 style="margin-top: 0; margin-bottom: 16px;">Editar cliente</h1>

            <div style="margin-bottom: 18px;">
                <a href="listar.php">Volver a clientes</a>
            </div>

            <label>Nombre completo</label>
            <input type="text" name="nombre_completo" value="<?= htmlspecialchars($cliente["nombre_completo"]) ?>" required>

            <label>Teléfono</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($cliente["telefono"]) ?>" required>

            <label>Correo</label>
            <input type="email" name="correo" value="<?= htmlspecialchars($cliente["correo"]) ?>" required>

            <label>Dirección</label>
            <input type="text" name="direccion" value="<?= htmlspecialchars($cliente["direccion"]) ?>">

            <label>Ciudad</label>
            <input type="text" name="ciudad" value="<?= htmlspecialchars($cliente["ciudad"]) ?>">

            <button type="submit">Guardar cambios</button>
        </form>
    </main>
</body>
</html>