<?php
session_start();
include("connection.php");
$con = connection();

$nombre = trim($_POST['nombre'] ?? '');
$epitafio = trim($_POST['recitar_epitafio'] ?? $_POST['epitafio'] ?? '');

if (empty($nombre) || empty($epitafio)) {
    header("Location: login.php?error=empty");
    exit();
}

// Búsqueda por nombre mediante Prepared Statements
$sql = "SELECT * FROM alumnos WHERE LOWER(TRIM(nombre)) = LOWER(?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $nombre);
$stmt->execute();
$result = $stmt->get_result();

$authenticated = false;
$matched_row = null;

// Verificar credenciales con password_verify o compatibilidad con texto plano
while ($row = $result->fetch_assoc()) {
    // 1. Verificación mediante hash seguro
    if (password_verify($epitafio, $row['recitar_epitafio'])) {
        $authenticated = true;
        $matched_row = $row;
        break;
    }
    // 2. Compatibilidad si el epitafio antiguo estaba almacenado en texto plano
    else if (strtolower(trim($epitafio)) === strtolower(trim($row['recitar_epitafio']))) {
        $authenticated = true;
        $matched_row = $row;
        // Migración automática: se actualiza al hash seguro de 60 caracteres
        $new_hash = password_hash($epitafio, PASSWORD_DEFAULT);
        $update_stmt = $con->prepare("UPDATE alumnos SET recitar_epitafio = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_hash, $row['id']);
        $update_stmt->execute();
        $update_stmt->close();
        break;
    }
}

if ($authenticated && $matched_row) {
    // Credenciales correctas: iniciamos la sesión del alumno
    $_SESSION['user'] = $matched_row['nombre'];
    $_SESSION['nombre'] = $matched_row['nombre'];
    $_SESSION['epitafio'] = $epitafio; // Guardamos el texto plano ingresado para mostrar en la insignia
    $_SESSION['edad'] = $matched_row['edad'];
    $_SESSION['alumno_id'] = $matched_row['id'];

    $stmt->close();
    $con->close();
    header("Location: index.php");
    exit();
} else {
    // Credenciales incorrectas o alumno no encontrado
    $stmt->close();
    $con->close();
    header("Location: login.php?error=1");
    exit();
}
?>
