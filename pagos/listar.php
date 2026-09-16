<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . "/../config/conexion.php";

$sql = "
    SELECT
        p.id_pago,
        p.id_reservacion,
        c.nombre_completo AS cliente,
        h.numero_habitacion,
        p.monto,
        p.metodo_pago,
        p.fecha_pago,
        p.estado,
        p.referencia
    FROM pagos p
    INNER JOIN reservaciones r
        ON p.id_reservacion = r.id_reservacion
    INNER JOIN clientes c
        ON r.id_cliente = c.id_cliente
    INNER JOIN habitaciones h
        ON r.id_habitacion = h.id_habitacion
    ORDER BY p.id_pago
";

$consulta = $conexion->query($sql);

$pagos = $consulta->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagos | Hotel Manager</title>
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
                <a class="nav-link active" href="listar.php">Pagos</a>
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
                    <h1>Pagos</h1>
                </div>
                <div class="user-pill">
                    <strong><?php echo htmlspecialchars($_SESSION["nombre"]); ?></strong>
                    <span><?php echo htmlspecialchars($_SESSION["rol"]); ?></span>
                </div>
            </header>

            <section class="module-panel">
                <div class="section-header">
                    <div>
                        <h3>Registro de pagos</h3>
                        <p>Control financiero de reservaciones y servicios del hotel.</p>
                    </div>
                    <div class="page-actions">
                        <a class="btn btn-primary" href="agregar.php">Registrar pago</a>
                        <a class="btn btn-secondary" href="../dashboard.php">Volver</a>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Reservación</th>
                                <th>Cliente</th>
                                <th>Habitación</th>
                                <th>Monto</th>
                                <th>Método</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Referencia</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pagos)): ?>
                                <tr>
                                    <td colspan="10" class="empty-state">No hay pagos registrados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pagos as $pago): ?>
                                    <tr>
                                        <td><?= $pago["id_pago"] ?></td>
                                        <td><?= $pago["id_reservacion"] ?></td>
                                        <td><?= htmlspecialchars($pago["cliente"]) ?></td>
                                        <td><?= htmlspecialchars($pago["numero_habitacion"]) ?></td>
                                        <td>$<?= number_format($pago["monto"], 2) ?></td>
                                        <td><?= htmlspecialchars($pago["metodo_pago"]) ?></td>
                                        <td><?= htmlspecialchars($pago["fecha_pago"]) ?></td>
                                        <td><span class="status-badge"><?= htmlspecialchars($pago["estado"]) ?></span></td>
                                        <td><?= htmlspecialchars($pago["referencia"] ?? "") ?></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="editar.php?id=<?= $pago["id_pago"] ?>">Editar</a>
                                                <a class="delete" href="eliminar.php?id=<?= $pago["id_pago"] ?>">Eliminar</a>
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