<?php 
session_start();
if(!isset($_SESSION['user'])){
    header("Location:login.php");
    exit;
}

include ("connection.php");
$con=connection();
$sql="SELECT * FROM users";
$query=mysqli_query($con,$sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User CRUD</title>
</head>
<body>

<div>
    <h2>bienvenido <?= htmlspecialchars($_SESSION['name']) ?> </h2>
    <a href="logout.php">Cerrar sesión</a>
</div>
    <div>
        <form action="insert_user.php" method="POST">
            <input type="text" name="name" placeholder="Ingresa tu nombre" required>
            <input type="text" name="lastname" placeholder="Ingresa tu apellido" required>
            <input type="text" name="username" placeholder="Ingresa tu usuario" required>
            <input type="email" name="email" placeholder="Ingresa tu correo" required>
            <input type="password" name="password" placeholder="Ingresa tu contraseña" required>

            <input type="submit" value="agregar">


        </form>
    </div>
    <div>
        <table>
            <thead>
                <tr>
                    <th>id</th>
                    <th>nombre</th>
                    <th>apellido</th>
                    <th>usuario</th>
                    <th>correo</th>
                    <th>password</th>
                    <th>accion</th>
                    <th>accion</th>
                </tr>
            </thead> 
              <tbody>
<?php while ($row = mysqli_fetch_array($query)): ?>
    <tr>
        <th><?= $row['id'] ?></th>
        <th><?= $row['name'] ?></th>
        <th><?= $row['lastname'] ?></th>
        <th><?= $row['username'] ?></th>
        <th><?= $row['email'] ?></th>
        <th><?= $row['password'] ?></th>
        <th><a href="update.php?id=<?= $row['id'] ?>">Editar</a></th>
        <th><a href="delete_user.php?id=<?= $row['id'] ?>">Eliminar</a></th>
    </tr>
<?php endwhile; ?>
</tbody>
        </table>
    </div>
    
</body>
</html>