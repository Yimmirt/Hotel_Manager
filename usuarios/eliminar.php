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

/*
Evitar que el usuario que tiene la sesión iniciada
se elimine a sí mismo.
*/
if ($id === (int) $_SESSION["usuario"]) {
    die("No puedes eliminar el usuario con el que tienes la sesión iniciada.");
}

/* Buscar usuario */
$sql = "
    SELECT
        id_usuario,
        nombre_completo,
        correo,
        rol,
        telefono,
        estado
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


/* Eliminar usuario */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {

        $sql = "
            DELETE FROM usuarios
            WHERE id_usuario = :id
        ";

        $consulta = $conexion->prepare($sql);

        $consulta->execute([
            ":id" => $id
        ]);

        header("Location: listar.php");
        exit;

    } catch (PDOException $error) {

        $mensaje = "Error al eliminar usuario: " . $error->getMessage();

    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Eliminar Usuario | Hotel Manager
    </title>
    <link rel="stylesheet" href="../css/estilos.css">

</head>

<body class="dashboard-page">
    <main style="padding: 40px 20px;">
        <div class="confirm-box">
            <h1 style="margin-top: 0;">Eliminar usuario</h1>

            <?php if (isset($mensaje)): ?>
                <p><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <p>¿Estás seguro de que deseas eliminar este usuario?</p>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario["nombre_completo"]) ?></p>
            <p><strong>Correo:</strong> <?= htmlspecialchars($usuario["correo"]) ?></p>
            <p><strong>Rol:</strong> <?= htmlspecialchars($usuario["rol"]) ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario["telefono"] ?? "") ?></p>
            <p><strong>Estado:</strong> <?= $usuario["estado"] ? "Activo" : "Inactivo" ?></p>

            <form method="POST">
                <button type="submit">Sí, eliminar usuario</button>
            </form>

            <br>
            <a href="listar.php">Cancelar</a>
        </div>
    </main>
</body>

</html>