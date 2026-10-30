<?php
session_start();
include("connection.php");
$con=connection();

$username = trim($_POST['username']??'');
$password = $_POST['password']??'';

if(empty($username) || empty($password)){
    header("Location: login.php?error=empty");
    exit();
}
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $authenticated = false;

    // 1. Verificar con hash seguro
    if (password_verify($password, $row['password'])) {
        $authenticated = true;
    }
    // 2. Compatibilidad si la contraseña antigua estaba en texto plano
    else if ($password === $row['password']) {
        $authenticated = true;
        // Se actualiza automáticamente al hash seguro de 60 caracteres
        $new_hash = password_hash($password, PASSWORD_DEFAULT);
        $update_stmt = $con->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_hash, $row['id']);
        $update_stmt->execute();
        $update_stmt->close();
    }

    if ($authenticated) {
        $_SESSION['user'] = $row['username'];
        $_SESSION['name'] = $row['name'];

        $stmt->close();
        $con->close();
        header("Location: index.php");
        exit();
    }
}

// Credenciales incorrectas o usuario no encontrado
$stmt->close();
$con->close();
header("Location: login.php?error=1");
exit();
?>