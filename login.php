<?php

session_start();

require_once __DIR__ . "/config/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $correo = trim($_POST["correo"]);
    $contrasena = $_POST["contrasena"];

    $sql = "
        SELECT
            id_usuario,
            nombre_completo,
            correo,
            contrasena,
            rol,
            estado
        FROM usuarios
        WHERE correo = :correo
        LIMIT 1
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ":correo" => $correo
    ]);

    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    $contrasenaValida = false;

    if ($usuario) {

        // Contraseña cifrada con password_hash()
        if (password_verify($contrasena, $usuario["contrasena"])) {

            $contrasenaValida = true;

        // Contraseñas antiguas guardadas en texto plano
        } elseif ($contrasena === $usuario["contrasena"]) {

            $contrasenaValida = true;
        }
    }

    if (
        $usuario &&
        $usuario["estado"] == true &&
        $contrasenaValida
    ) {

        $_SESSION["usuario"] = $usuario["id_usuario"];
        $_SESSION["nombre"] = $usuario["nombre_completo"];
        $_SESSION["correo"] = $usuario["correo"];
        $_SESSION["rol"] = $usuario["rol"];

        header("Location: dashboard.php");
        exit;
    }

    header("Location: index.php?error=1");
    exit;
}

header("Location: index.php");
exit;

?>