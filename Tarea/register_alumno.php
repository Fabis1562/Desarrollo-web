<?php
session_start();
include("connection.php");
$con = connection();

$nombre = mysqli_real_escape_string($con, trim($_POST['nombre'] ?? ''));
$edad = intval($_POST['edad'] ?? 0);
$altura = floatval($_POST['altura'] ?? 0);

if ($altura > 3) {
    $altura = $altura / 100;
}

$peso = floatval($_POST['peso'] ?? 0);

if ($altura > 0 && $peso > 0) {
    $imc = round($peso / ($altura * $altura), 2);
} else {
    $imc = 0.00;
}

$recitar_epitafio = trim($_POST['recitar_epitafio'] ?? $_POST['epitafio'] ?? '');

if (empty($nombre) || empty($recitar_epitafio)) {
    header("Location: login.php?error=empty");
    exit();
}

// Cifrar el epitafio (que actúa como contraseña) con un hash seguro (bcrypt)
$recitar_epitafio_hashed = password_hash($recitar_epitafio, PASSWORD_DEFAULT);

$sql = "INSERT INTO alumnos (nombre, edad, altura, peso, imc, recitar_epitafio) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("siddds", $nombre, $edad, $altura, $peso, $imc, $recitar_epitafio_hashed);

if ($stmt->execute()) {
    $new_id = $stmt->insert_id;
    // Iniciar sesión automáticamente
    $_SESSION['user'] = $nombre;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['epitafio'] = $recitar_epitafio;
    $_SESSION['edad'] = $edad;
    $_SESSION['alumno_id'] = $new_id;

    $stmt->close();
    $con->close();
    header("Location: index.php");
    exit();
} else {
    echo "<h3>Error al registrar alumno:</h3> " . $stmt->error;
    $stmt->close();
    $con->close();
}
?>
