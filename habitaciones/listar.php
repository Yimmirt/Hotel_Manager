<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . "/../config/conexion.php";

$sql = "SELECT * FROM habitaciones ORDER BY numero_habitacion";

$consulta = $conexion->query($sql);

$habitaciones = $consulta->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitaciones | Hotel Manager</title>
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
                <a class="nav-link active" href="listar.php">Habitaciones</a>
                <a class="nav-link" href="../reservaciones/listar.php">Reservaciones</a>
                <a class="nav-link" href="../servicios/listar.php">Servicios</a>
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
                    <h1>Habitaciones</h1>
                </div>
                <div class="user-pill">
                    <strong><?php echo htmlspecialchars($_SESSION["nombre"]); ?></strong>
                    <span><?php echo htmlspecialchars($_SESSION["rol"]); ?></span>
                </div>
            </header>

            <section class="module-panel">
                <div class="section-header">
                    <div>
                        <h3>Inventario de habitaciones</h3>
                        <p>Consulta rápida del estado y disponibilidad de cada habitación.</p>
                    </div>
                    <div class="page-actions">
                        <a class="btn btn-primary" href="agregar.php">Agregar habitación</a>
                        <a class="btn btn-secondary" href="../dashboard.php">Volver</a>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Número</th>
                                <th>Tipo</th>
                                <th>Capacidad</th>
                                <th>Precio por noche</th>
                                <th>Estado</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($habitaciones)): ?>
                                <tr>
                                    <td colspan="8" class="empty-state">No hay habitaciones registradas.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($habitaciones as $habitacion): ?>
                                    <tr>
                                        <td><?= $habitacion["id_habitacion"] ?></td>
                                        <td><?= htmlspecialchars($habitacion["numero_habitacion"]) ?></td>
                                        <td><?= htmlspecialchars($habitacion["tipo"]) ?></td>
                                        <td><?= htmlspecialchars($habitacion["capacidad"]) ?></td>
                                        <td>$<?= number_format($habitacion["precio_noche"], 2) ?></td>
                                        <td><span class="status-badge"><?= htmlspecialchars($habitacion["estado"]) ?></span></td>
                                        <td><?= htmlspecialchars($habitacion["descripcion"]) ?></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="editar.php?id=<?= $habitacion["id_habitacion"] ?>">Editar</a>
                                                <a class="delete" href="eliminar.php?id=<?= $habitacion["id_habitacion"] ?>">Eliminar</a>
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