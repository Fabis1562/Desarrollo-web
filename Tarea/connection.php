<?php 
function connection(){
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "alumnos_crud";

    $connect = @mysqli_connect($host, $user, $pass);
    if (!$connect) {
        die("<div style='font-family:Segoe UI, sans-serif;padding:25px;background:#fee2e2;color:#991b1b;border-radius:12px;max-width:650px;margin:40px auto;border:1px solid #f87171;box-shadow:0 10px 25px rgba(0,0,0,0.1);'>
            <h3 style='margin-top:0;display:flex;align-items:center;gap:10px;'>⚠️ Error de conexión a MySQL</h3>
            <p>No se pudo conectar al servidor MySQL en <strong>$host</strong>.</p>
            <p><strong>Solución:</strong> Abre el panel de <strong>XAMPP</strong> y haz clic en el botón <strong>Start</strong> junto a <strong>MySQL</strong>.</p>
            <pre style='background:#fef2f2;padding:12px;border-radius:8px;font-size:13px;overflow-x:auto;border:1px solid #fca5a5;'>" . mysqli_connect_error() . "</pre>
        </div>");
    }

    $sql_db = "CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    mysqli_query($connect, $sql_db);

    mysqli_select_db($connect, $db);
    mysqli_set_charset($connect, "utf8mb4");

    $sql_table = "CREATE TABLE IF NOT EXISTS `alumnos` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `nombre` VARCHAR(150) NOT NULL,
        `edad` INT NOT NULL,
        `altura` DECIMAL(5,2) NOT NULL,
        `peso` DECIMAL(5,2) NOT NULL,
        `imc` DECIMAL(5,2) NOT NULL,
        `preferencias_religiosas` VARCHAR(100) DEFAULT '',
        `montado_elefante` VARCHAR(10) DEFAULT 'No',
        `miedo_volar` VARCHAR(10) DEFAULT 'No',
        `libros_leidos` INT DEFAULT 0,
        `color_cabello` VARCHAR(50) DEFAULT '',
        `pelicula_favorita` VARCHAR(150) DEFAULT '',
        `serie_favorita` VARCHAR(150) DEFAULT '',
        `genero_musica_favorita` VARCHAR(100) DEFAULT '',
        `preferencias_politicas` VARCHAR(100) DEFAULT '',
        `recitar_epitafio` TEXT DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($connect, $sql_table);

    return $connect;
}
?>
