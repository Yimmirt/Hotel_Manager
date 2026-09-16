<?php

session_start();

if (!isset($_SESSION["usuario"])) {

    header("Location: index.php");
    exit;

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Manager | Dashboard</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="dashboard-page">
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="brand-box">
                <div class="mark">HM</div>
                <h2>Hotel Manager</h2>
            </div>

            <nav class="nav-menu">
                <a class="nav-link active" href="dashboard.php">Dashboard</a>
                <a class="nav-link" href="clientes/listar.php">Clientes</a>
                <a class="nav-link" href="habitaciones/listar.php">Habitaciones</a>
                <a class="nav-link" href="reservaciones/listar.php">Reservaciones</a>
                <a class="nav-link" href="servicios/listar.php">Servicios</a>
                <a class="nav-link" href="pagos/listar.php">Pagos</a>
                <a class="nav-link" href="usuarios/listar.php">Usuarios</a>
            </nav>

            <div class="sidebar-footer">
                <p>Sesión actual</p>
                <a class="logout-link" href="logout.php">Cerrar sesión</a>
            </div>
        </aside>

        <main class="content-area">
            <header class="topbar">
                <div>
                    <h1>Dashboard</h1>
                </div>
                <div class="user-pill">
                    <strong><?php echo htmlspecialchars($_SESSION["nombre"]); ?></strong>
                    <span><?php echo htmlspecialchars($_SESSION["rol"]); ?></span>
                </div>
            </header>

            <section class="stats-grid">
                <article class="stat-card">
                    <span class="label">Reservas</span>
                    <strong>128</strong>
                    <small>+12% este mes</small>
                </article>

                <article class="stat-card">
                    <span class="label">Habitaciones</span>
                    <strong>42</strong>
                    <small>+3 ocupadas</small>
                </article>

                <article class="stat-card">
                    <span class="label">Ingresos</span>
                    <strong>$18.4K</strong>
                    <small>+8% vs mes anterior</small>
                </article>

                <article class="stat-card">
                    <span class="label">Clientes</span>
                    <strong>96</strong>
                    <small>+5 nuevos</small>
                </article>
            </section>

            <section class="panel-grid">
                <article class="panel">
                    <h3>Accesos rápidos</h3>
                    <div class="quick-links">
                        <a href="clientes/listar.php">Clientes</a>
                        <a href="habitaciones/listar.php">Habitaciones</a>
                        <a href="reservaciones/listar.php">Reservaciones</a>
                        <a href="servicios/listar.php">Servicios</a>
                    </div>
                </article>

                <article class="panel">
                    <h3>Resumen operativo</h3>
                    <ul class="summary-list">
                        <li><span>Ocupación</span><strong>74%</strong></li>
                        <li><span>Check-in hoy</span><strong>12</strong></li>
                        <li><span>Check-out hoy</span><strong>8</strong></li>
                        <li><span>Pagos pendientes</span><strong>5</strong></li>
                    </ul>
                </article>
            </section>
        </main>
    </div>
</body>
</html>