<?php
include ("connection.php");
$con=connection();
$id = null; 
$name = trim ($_POST['name']??'');
$lastname = trim ($_POST['lastname']??'');
$username = trim ($_POST['username']??'');
$email = trim ($_POST['email']??'');
$password = $_POST['password'];

if(empty($name) || empty($lastname) || empty($username) || empty($email) || empty($password)){
   echo "<script>alert('todos los campos son obligatorios'); window.location.href='index.php';</script>";
   exit();
}
//parseamos la pass a forma de hash antes de guardar
$password_hashed = password_hash($password, PASSWORD_DEFAULT);


$sql="INSERT INTO users (name,lastname,username,email,password) VALUES (?,?,?,?,?)";
$stmt=$con->prepare($sql);
$stmt->bind_param("sssss",$name,$lastname,$username,$email,$password_hashed);

if ($stmt->execute()){
    header("location: index.php");
    exit();
}
else {
    echo "<script>alert('Error al insertar el usuario'); window.location.href='index.php';</script>";
}
$stmt->close();
 

?>
