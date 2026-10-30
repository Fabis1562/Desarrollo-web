<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

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

$preferencias_religiosas = trim($_POST['preferencias_religiosas'] ?? '');
$montado_elefante = trim($_POST['montado_elefante'] ?? 'No');
$miedo_volar = trim($_POST['miedo_volar'] ?? 'No');
$libros_leidos = intval($_POST['libros_leidos'] ?? 0);
$color_cabello = trim($_POST['color_cabello'] ?? '');
$pelicula_favorita = trim($_POST['pelicula_favorita'] ?? '');
$serie_favorita = trim($_POST['serie_favorita'] ?? '');
$genero_musica_favorita = trim($_POST['genero_musica_favorita'] ?? '');
$preferencias_politicas = trim($_POST['preferencias_politicas'] ?? '');
$recitar_epitafio = trim($_POST['recitar_epitafio'] ?? '');

// Cifrar el epitafio (que actúa como contraseña) con hash seguro si fue proporcionado
$recitar_epitafio_hashed = !empty($recitar_epitafio) ? password_hash($recitar_epitafio, PASSWORD_DEFAULT) : '';

$sql = "INSERT INTO alumnos (
    nombre, edad, altura, peso, imc, 
    preferencias_religiosas, montado_elefante, miedo_volar, 
    libros_leidos, color_cabello, pelicula_favorita, 
    serie_favorita, genero_musica_favorita, preferencias_politicas, recitar_epitafio
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql);
$stmt->bind_param(
    "sidddsssissssss",
    $nombre,
    $edad,
    $altura,
    $peso,
    $imc,
    $preferencias_religiosas,
    $montado_elefante,
    $miedo_volar,
    $libros_leidos,
    $color_cabello,
    $pelicula_favorita,
    $serie_favorita,
    $genero_musica_favorita,
    $preferencias_politicas,
    $recitar_epitafio_hashed
);

if ($stmt->execute()) {
    $stmt->close();
    $con->close();
    header("Location: index.php");
    exit();
} else {
    echo "<h3>Error al guardar los datos:</h3> " . $stmt->error;
    $stmt->close();
    $con->close();
}
?>
