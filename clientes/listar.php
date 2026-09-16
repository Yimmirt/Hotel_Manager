<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . "/../config/conexion.php";

$sql = "SELECT * FROM clientes ORDER BY id_cliente";

$consulta = $conexion->query($sql);

$clientes = $consulta->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes | Hotel Manager</title>
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
                <a class="nav-link active" href="listar.php">Clientes</a>
                <a class="nav-link" href="../habitaciones/listar.php">Habitaciones</a>
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
                    <h1>Clientes</h1>
                </div>
                <div class="user-pill">
                    <strong><?php echo htmlspecialchars($_SESSION["nombre"]); ?></strong>
                    <span><?php echo htmlspecialchars($_SESSION["rol"]); ?></span>
                </div>
            </header>

            <section class="module-panel">
                <div class="section-header">
                    <div>
                        <h3>Listado de clientes</h3>
                        <p>Consulta y administración del registro de huéspedes.</p>
                    </div>
                    <div class="page-actions">
                        <a class="btn btn-primary" href="agregar.php">Agregar cliente</a>
                        <a class="btn btn-secondary" href="../dashboard.php">Volver</a>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Dirección</th>
                                <th>Ciudad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($clientes)): ?>
                                <tr>
                                    <td colspan="7" class="empty-state">No hay clientes registrados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($clientes as $cliente): ?>
                                    <tr>
                                        <td><?= $cliente["id_cliente"] ?></td>
                                        <td><?= htmlspecialchars($cliente["nombre_completo"]) ?></td>
                                        <td><?= htmlspecialchars($cliente["telefono"]) ?></td>
                                        <td><?= htmlspecialchars($cliente["correo"]) ?></td>
                                        <td><?= htmlspecialchars($cliente["direccion"]) ?></td>
                                        <td><?= htmlspecialchars($cliente["ciudad"]) ?></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="editar.php?id=<?= $cliente["id_cliente"] ?>">Editar</a>
                                                <a class="delete" href="eliminar.php?id=<?= $cliente["id_cliente"] ?>">Eliminar</a>
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