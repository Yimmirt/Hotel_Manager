<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . "/../config/conexion.php";

$sql = "SELECT * FROM servicios ORDER BY id_servicio";

$consulta = $conexion->query($sql);

$servicios = $consulta->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios | Hotel Manager</title>
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
                <a class="nav-link active" href="listar.php">Servicios</a>
                <a class="nav-link" href="../pagos/listar.php">Pagos</a>
                <a class="nav-link" href="../usuarios/listar.php">Usuarios</a>
            </nav>

            <div class="sidebar-footer">
                <p>Sesión actual</p>
                <a class="logout-link" href="../logout.php">Cerrar sesión</a>
            </div>
        </aside>

        <main class="content-area">
            <header class="topbar">
                <div>
                    <h1>Servicios</h1>
                </div>
                <div class="user-pill">
                    <strong><?php echo htmlspecialchars($_SESSION["nombre"]); ?></strong>
                    <span><?php echo htmlspecialchars($_SESSION["rol"]); ?></span>
                </div>
            </header>

            <section class="module-panel">
                <div class="section-header">
                    <div>
                        <h3>Catálogo de servicios</h3>
                        <p>Administración de servicios y disponibilidad del hotel.</p>
                    </div>
                    <div class="page-actions">
                        <a class="btn btn-primary" href="agregar.php">Agregar servicio</a>
                        <a class="btn btn-secondary" href="../dashboard.php">Volver</a>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Disponibilidad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($servicios)): ?>
                                <tr>
                                    <td colspan="6" class="empty-state">No hay servicios registrados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($servicios as $servicio): ?>
                                    <tr>
                                        <td><?= $servicio["id_servicio"] ?></td>
                                        <td><?= htmlspecialchars($servicio["nombre"]) ?></td>
                                        <td><?= htmlspecialchars($servicio["descripcion"] ?? "") ?></td>
                                        <td>$<?= number_format($servicio["precio"], 2) ?></td>
                                        <td>
                                            <?php if ($servicio["disponibilidad"]): ?>
                                                <span class="status-badge">Disponible</span>
                                            <?php else: ?>
                                                <span class="status-badge inactive">No disponible</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="editar.php?id=<?= $servicio["id_servicio"] ?>">Editar</a>
                                                <a class="delete" href="eliminar.php?id=<?= $servicio["id_servicio"] ?>">Eliminar</a>
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