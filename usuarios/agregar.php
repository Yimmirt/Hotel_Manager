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
    $correo = trim($_POST["correo"]);
    $contrasena = $_POST["contrasena"];
    $rol = $_POST["rol"];
    $telefono = trim($_POST["telefono"]);
    $estado = isset($_POST["estado"]);

    // Cifrar contraseña
    $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

    try {

        $sql = "
            INSERT INTO usuarios
            (
                nombre_completo,
                correo,
                contrasena,
                rol,
                telefono,
                estado
            )
            VALUES
            (
                :nombre,
                :correo,
                :contrasena,
                :rol,
                :telefono,
                :estado
            )
        ";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":nombre" => $nombre,
            ":correo" => $correo,
            ":contrasena" => $contrasenaHash,
            ":rol" => $rol,
            ":telefono" => $telefono,
            ":estado" => $estado
        ]);

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "Error al registrar usuario: " . $error->getMessage();

    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <form method="POST">
            <h1 style="margin-top: 0; margin-bottom: 16px;">Agregar nuevo usuario</h1>

            <div style="margin-bottom: 18px;">
                <a href="listar.php">Volver a usuarios</a>
            </div>

            <?php if ($mensaje != ""): ?>
                <p class="muted"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <label>Nombre completo</label>
            <input type="text" name="nombre_completo" required>

            <label>Correo</label>
            <input type="email" name="correo" required>

            <label>Contraseña</label>
            <input type="password" name="contrasena" required>

            <label>Rol</label>
            <select name="rol" required>
                <option value="">Selecciona un rol</option>
                <option value="Administrador">Administrador</option>
                <option value="Recepcionista">Recepcionista</option>
                <option value="Empleado">Empleado</option>
            </select>

            <label>Teléfono</label>
            <input type="text" name="telefono">

            <label>
                <input type="checkbox" name="estado" checked>
                Usuario activo
            </label>

            <button type="submit">Registrar usuario</button>
        </form>
    </main>
</body>
</html>