<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Manager | Iniciar sesión</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="auth-page">
        <div class="auth-shell">
            <section class="auth-brand">
                <div class="brand-badge">HM</div>
                <h1>Hotel Manager</h1>
                <p>Gestiona reservas, clientes, pagos y servicios desde un panel moderno diseñado para optimizar la operación de tu hotel.</p>

                <ul class="feature-list">
                    <li>Control centralizado de reservas</li>
                    <li>Seguimiento de habitaciones y ocupación</li>
                    <li>Reportes rápidos y atención profesional</li>
                </ul>
            </section>

            <section class="auth-card">
                <span class="eyebrow">Acceso privado</span>
                <h2>Iniciar sesión</h2>

                <?php
                if (isset($_GET['error'])) {
                    echo '<p class="error">Correo o contraseña incorrectos.</p>';
                }
                ?>

                <form class="auth-form" action="login.php" method="POST">
                    <div class="input-group">
                        <label for="correo">Correo electrónico</label>
                        <input id="correo" type="email" name="correo" placeholder="correo@hotelmanager.com" required>
                    </div>

                    <div class="input-group">
                        <label for="contrasena">Contraseña</label>
                        <input id="contrasena" type="password" name="contrasena" placeholder="Contraseña" required>
                    </div>

                    <button class="primary-btn" type="submit">Iniciar sesión</button>
                </form>

                <div class="card-footer">
                    <span>Soporte 24/7</span>
                    <span>Hotel Manager</span>
                </div>
            </section>
        </div>
    </div>
</body>
</html>