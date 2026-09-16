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


/* Buscar usuario */
$sql = "
    SELECT *
    FROM usuarios
    WHERE id_usuario = :id
";

$consulta = $conexion->prepare($sql);

$consulta->execute([
    ":id" => $id
]);

$usuario = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado.");
}

$mensaje = "";


/* Actualizar usuario */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre_completo"]);
    $correo = trim($_POST["correo"]);
    $telefono = trim($_POST["telefono"]);
    $rol = $_POST["rol"];
    $estado = isset($_POST["estado"]);
    $nuevaContrasena = $_POST["contrasena"];

    try {

        /*
        Si se escribió una contraseña nueva,
        también actualizamos la contraseña.
        */
        if (!empty($nuevaContrasena)) {

            $contrasenaHash = password_hash(
                $nuevaContrasena,
                PASSWORD_DEFAULT
            );

            $sql = "
                UPDATE usuarios
                SET
                    nombre_completo = :nombre,
                    correo = :correo,
                    contrasena = :contrasena,
                    rol = :rol,
                    telefono = :telefono,
                    estado = :estado
                WHERE id_usuario = :id
            ";

            $consulta = $conexion->prepare($sql);

            $consulta->execute([
                ":nombre" => $nombre,
                ":correo" => $correo,
                ":contrasena" => $contrasenaHash,
                ":rol" => $rol,
                ":telefono" => $telefono,
                ":estado" => $estado,
                ":id" => $id
            ]);

        } else {

            /*
            Si deja la contraseña vacía,
            conservamos la contraseña actual.
            */

            $sql = "
                UPDATE usuarios
                SET
                    nombre_completo = :nombre,
                    correo = :correo,
                    rol = :rol,
                    telefono = :telefono,
                    estado = :estado
                WHERE id_usuario = :id
            ";

            $consulta = $conexion->prepare($sql);

            $consulta->execute([
                ":nombre" => $nombre,
                ":correo" => $correo,
                ":rol" => $rol,
                ":telefono" => $telefono,
                ":estado" => $estado,
                ":id" => $id
            ]);
        }

        /*
        Si el usuario está editando su propia cuenta,
        actualizamos algunos datos de la sesión.
        */
        if ($id === (int) $_SESSION["usuario"]) {

            $_SESSION["nombre"] = $nombre;
            $_SESSION["correo"] = $correo;
            $_SESSION["rol"] = $rol;
        }

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "Error al actualizar usuario: " . $error->getMessage();

    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Editar Usuario | Hotel Manager
    </title>
    <link rel="stylesheet" href="../css/estilos.css">

</head>

<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <form method="POST">
            <h1 style="margin-top: 0; margin-bottom: 16px;">Editar usuario</h1>

            <div style="margin-bottom: 18px;">
                <a href="listar.php">Volver a usuarios</a>
            </div>

            <?php if ($mensaje != ""): ?>
                <p class="muted"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <label>Nombre completo</label>
            <input type="text" name="nombre_completo" value="<?= htmlspecialchars($usuario["nombre_completo"]) ?>" required>

            <label>Correo</label>
            <input type="email" name="correo" value="<?= htmlspecialchars($usuario["correo"]) ?>" required>

            <label>Nueva contraseña</label>
            <input type="password" name="contrasena">
            <p class="muted" style="margin-top: -8px; margin-bottom: 18px;">Déjala vacía si no quieres cambiarla.</p>

            <label>Rol</label>
            <select name="rol" required>
                <option value="Administrador" <?= $usuario["rol"] === "Administrador" ? "selected" : "" ?>>Administrador</option>
                <option value="Recepcionista" <?= $usuario["rol"] === "Recepcionista" ? "selected" : "" ?>>Recepcionista</option>
                <option value="Empleado" <?= $usuario["rol"] === "Empleado" ? "selected" : "" ?>>Empleado</option>
            </select>

            <label>Teléfono</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($usuario["telefono"] ?? "") ?>">

            <label>
                <input type="checkbox" name="estado" <?= $usuario["estado"] ? "checked" : "" ?>>
                Usuario activo
            </label>

            <button type="submit">Guardar cambios</button>
        </form>
    </main>
</body>

</html>