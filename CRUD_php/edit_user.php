<?php
include("connection.php");
$con = connection();

$id = intval($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($name) || empty($lastname) || empty($username) || empty($email) || empty($password) || $id <= 0) {
    echo "<script>alert('Todos los campos son obligatorios'); window.location.href='index.php';</script>";
    exit();
}

// Consultar contraseña actual para no re-hashear si no fue modificada
$check_stmt = $con->prepare("SELECT password FROM users WHERE id = ?");
$check_stmt->bind_param("i", $id);
$check_stmt->execute();
$current = $check_stmt->get_result()->fetch_assoc();
$check_stmt->close();

$password_to_save = $password;
if ($current && $password === $current['password']) {
    $info = password_get_info($password);
    if (empty($info['algo'])) {
        $password_to_save = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $password_to_save = $password;
    }
} else {
    $password_to_save = password_hash($password, PASSWORD_DEFAULT);
}

$sql = "UPDATE users SET name = ?, lastname = ?, username = ?, email = ?, password = ? WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("sssssi", $name, $lastname, $username, $email, $password_to_save, $id);

if ($stmt->execute()) {
    $stmt->close();
    $con->close();
    header("Location: index.php");
    exit();
} else {
    echo "<script>alert('Error al actualizar el usuario: " . addslashes($stmt->error) . "'); window.location.href='index.php';</script>";
    $stmt->close();
    $con->close();
}
?>

