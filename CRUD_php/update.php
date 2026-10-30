<?php 
include ("connection.php");
$con=connection();
$id=$_GET['id'];

$sql="SELECT * FROM users WHERE id='$id'";
$query=mysqli_query($con,$sql);
$row=mysqli_fetch_array($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuario</title>
</head>
<body>
    <div>
    <form action="edit_user.php" method="POST">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <input type="text" name="name" placeholder="Ingresa tu nombre" value="<?= $row['name'] ?>">
        <input type="text" name="lastname" placeholder="Ingresa tu apellido" value="<?= $row['lastname'] ?>">
        <input type="text" name="username" placeholder="Ingresa tu usuario" value="<?= $row['username'] ?>">
        <input type="email" name="email" placeholder="Ingresa tu correo" value="<?= $row['email'] ?>">
        <input type="password" name="password" placeholder="Ingresa tu contraseña" value="<?= $row['password'] ?>">
        <input type="submit" value="actualizar">
    </form>
    </div>
</body>
</html>