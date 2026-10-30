<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
<body>
    <div>
        <h1>Iniciar Sesión</h1>
        <!--si existe error, lo mostramos-->
        <?php if (isset($_GET['error'])): ?>
            <p style="color:red">Usuario o contraseña incorrectos</p>
        <?php endif; ?>
        <form action="login_process.php" method="POST">
           <label for="username">Usuario</label>
           <input type="text" name="username" id="username" placeholder="Ingresa tu usuario" required>
           
           <label for="password">Contraseña</label>
           <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña" required>
           
           <input type="submit" value="Iniciar sesion">
        </form>
    </div>
</body>
</html>