<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . "/../config/conexion.php";

$sql = "
    SELECT
        r.id_reservacion,
        c.nombre_completo AS cliente,
        h.numero_habitacion,
        r.fecha_reservacion,
        r.fecha_entrada,
        r.fecha_salida,
        r.numero_huespedes,
        r.estado
    FROM reservaciones r
    INNER JOIN clientes c
        ON r.id_cliente = c.id_cliente
    INNER JOIN habitaciones h
        ON r.id_habitacion = h.id_habitacion
    ORDER BY r.id_reservacion
";

$consulta = $conexion->query($sql);

$reservaciones = $consulta->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservaciones | Hotel Manager</title>
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
                <a class="nav-link active" href="listar.php">Reservaciones</a>
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
                    <h1>Reservaciones</h1>
                </div>
                <div class="user-pill">
                    <strong><?php echo htmlspecialchars($_SESSION["nombre"]); ?></strong>
                    <span><?php echo htmlspecialchars($_SESSION["rol"]); ?></span>
                </div>
            </header>

            <section class="module-panel">
                <div class="section-header">
                    <div>
                        <h3>Gestión de reservaciones</h3>
                        <p>Control de fechas, huéspedes, habitaciones y estado.</p>
                    </div>
                    <div class="page-actions">
                        <a class="btn btn-primary" href="agregar.php">Nueva reservación</a>
                        <a class="btn btn-secondary" href="../dashboard.php">Volver</a>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Habitación</th>
                                <th>Fecha reservación</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Huéspedes</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reservaciones)): ?>
                                <tr>
                                    <td colspan="9" class="empty-state">No hay reservaciones registradas.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($reservaciones as $reservacion): ?>
                                    <tr>
                                        <td><?= $reservacion["id_reservacion"] ?></td>
                                        <td><?= htmlspecialchars($reservacion["cliente"]) ?></td>
                                        <td><?= htmlspecialchars($reservacion["numero_habitacion"]) ?></td>
                                        <td><?= htmlspecialchars($reservacion["fecha_reservacion"]) ?></td>
                                        <td><?= htmlspecialchars($reservacion["fecha_entrada"]) ?></td>
                                        <td><?= htmlspecialchars($reservacion["fecha_salida"]) ?></td>
                                        <td><?= htmlspecialchars($reservacion["numero_huespedes"]) ?></td>
                                        <td><span class="status-badge"><?= htmlspecialchars($reservacion["estado"]) ?></span></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="editar.php?id=<?= $reservacion["id_reservacion"] ?>">Editar</a>
                                                <a class="delete" href="eliminar.php?id=<?= $reservacion["id_reservacion"] ?>">Eliminar</a>
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