<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include("connection.php");
$con = connection();

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit();
}

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

// Consultar epitafio actual para evitar re-hashear si no cambió
$check_stmt = $con->prepare("SELECT recitar_epitafio FROM alumnos WHERE id = ?");
$check_stmt->bind_param("i", $id);
$check_stmt->execute();
$current_data = $check_stmt->get_result()->fetch_assoc();
$check_stmt->close();

$epitafio_to_save = $recitar_epitafio;
if (!empty($recitar_epitafio)) {
    if ($current_data && $recitar_epitafio === $current_data['recitar_epitafio']) {
        $info = password_get_info($recitar_epitafio);
        if (empty($info['algo'])) {
            // Epitafio antiguo en texto plano: migrar a hash
            $epitafio_to_save = password_hash($recitar_epitafio, PASSWORD_DEFAULT);
        } else {
            // Ya está hasheado
            $epitafio_to_save = $recitar_epitafio;
        }
    } else {
        // Epitafio / contraseña nuevo: hashearlo
        $info = password_get_info($recitar_epitafio);
        if (empty($info['algo'])) {
            $epitafio_to_save = password_hash($recitar_epitafio, PASSWORD_DEFAULT);
            // Si el alumno editado es el mismo que tiene la sesión activa, actualizar su sesión
            if (isset($_SESSION['alumno_id']) && $_SESSION['alumno_id'] == $id) {
                $_SESSION['epitafio'] = $recitar_epitafio;
            }
        }
    }
}

$sql = "UPDATE alumnos SET 
    nombre = ?,
    edad = ?,
    altura = ?,
    peso = ?,
    imc = ?,
    preferencias_religiosas = ?,
    montado_elefante = ?,
    miedo_volar = ?,
    libros_leidos = ?,
    color_cabello = ?,
    pelicula_favorita = ?,
    serie_favorita = ?,
    genero_musica_favorita = ?,
    preferencias_politicas = ?,
    recitar_epitafio = ?
WHERE id = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param(
    "sidddsssissssssi",
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
    $epitafio_to_save,
    $id
);

if ($stmt->execute()) {
    $stmt->close();
    $con->close();
    header("Location: index.php");
    exit();
} else {
    echo "<h3>Error al actualizar los datos:</h3> " . $stmt->error;
    $stmt->close();
    $con->close();
}
?>
