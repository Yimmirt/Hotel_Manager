<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . "/../config/conexion.php";

$sql = "
    SELECT
        id_usuario,
        nombre_completo,
        correo,
        rol,
        telefono,
        estado,
        fecha_registro
    FROM usuarios
    ORDER BY id_usuario
";

$consulta = $conexion->query($sql);

$usuarios = $consulta->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios | Hotel Manager</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="dashboard-page">
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="brand-box">
                <div class="mark">HM</div>
                <h2>Hotel Manager</h2>
            </div>

            <nav class="nav-menu">
                <a class="nav-link" href="../dashboard.php">Dashboard</a>
                <a class="nav-link" href="../clientes/listar.php">Clientes</a>
                <a class="nav-link" href="../habitaciones/listar.php">Habitaciones</a>
                <a class="nav-link" href="../reservaciones/listar.php">Reservaciones</a>
                <a class="nav-link" href="../servicios/listar.php">Servicios</a>
                <a class="nav-link" href="../pagos/listar.php">Pagos</a>
                <a class="nav-link active" href="listar.php">Usuarios</a>
            </nav>

            <div class="sidebar-footer">
                <p>Sesión actual</p>
                <a class="logout-link" href="../logout.php">Cerrar sesión</a>
            </div>
        </aside>

        <main class="content-area">
            <header class="topbar">
                <div>
                    <h1>Usuarios</h1>
                </div>
                <div class="user-pill">
                    <strong><?php echo htmlspecialchars($_SESSION["nombre"]); ?></strong>
                    <span><?php echo htmlspecialchars($_SESSION["rol"]); ?></span>
                </div>
            </header>

            <section class="module-panel">
                <div class="section-header">
                    <div>
                        <h3>Administración de usuarios</h3>
                        <p>Gestión de accesos y permisos del sistema.</p>
                    </div>
                    <div class="page-actions">
                        <a class="btn btn-primary" href="agregar.php">Agregar usuario</a>
                        <a class="btn btn-secondary" href="../dashboard.php">Volver</a>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Teléfono</th>
                                <th>Estado</th>
                                <th>Fecha de registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($usuarios)): ?>
                                <tr>
                                    <td colspan="8" class="empty-state">No hay usuarios registrados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?= $usuario["id_usuario"] ?></td>
                                        <td><?= htmlspecialchars($usuario["nombre_completo"]) ?></td>
                                        <td><?= htmlspecialchars($usuario["correo"]) ?></td>
                                        <td><?= htmlspecialchars($usuario["rol"]) ?></td>
                                        <td><?= htmlspecialchars($usuario["telefono"] ?? "") ?></td>
                                        <td>
                                            <?php if ($usuario["estado"]): ?>
                                                <span class="status-badge">Activo</span>
                                            <?php else: ?>
                                                <span class="status-badge inactive">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($usuario["fecha_registro"]) ?></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="editar.php?id=<?= $usuario["id_usuario"] ?>">Editar</a>
                                                <a class="delete" href="eliminar.php?id=<?= $usuario["id_usuario"] ?>">Eliminar</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>