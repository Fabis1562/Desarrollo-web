<?php
/**
 * Conexión a la Base de Datos con Auto-Instalación
 * Sistema de Usuarios y Productos
 * Desarrollado para ejecución en XAMPP y pruebas en Postman
 */

function connection() {
    $host = "localhost";
    $user = "root";
    // Array de contraseñas para compatibilidad automática (MySQL 8 servicio con contraseña o XAMPP por defecto sin contraseña)
    $passwords = ["12345678", ""];
    $db = "sistema_usuarios_productos";

    mysqli_report(MYSQLI_REPORT_OFF);

    $connect = null;
    $connected = false;

    foreach ($passwords as $pwd) {
        $testConn = @mysqli_connect($host, $user, $pwd);
        if ($testConn) {
            $connect = $testConn;
            $connected = true;
            break;
        }
    }

    if (!$connected || !$connect) {
        http_response_code(500);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode([
            "status" => "error",
            "message" => "No se pudo conectar al servidor MySQL en $host.",
            "detalle" => mysqli_connect_error()
        ]);
        exit();
    }

    // 1. Crear base de datos si no existe
    $sql_db = "CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    mysqli_query($connect, $sql_db);

    // Seleccionar la base de datos
    mysqli_select_db($connect, $db);
    mysqli_set_charset($connect, "utf8mb4");

    // 2. Crear tabla `usuarios` (Exactamente 5 campos)
    // 1. id, 2. nombre, 3. email, 4. telefono, 5. direccion
    $sql_usuarios = "CREATE TABLE IF NOT EXISTS `usuarios` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `nombre` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `telefono` VARCHAR(20) NOT NULL,
        `direccion` VARCHAR(200) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    mysqli_query($connect, $sql_usuarios);

    // 3. Crear tabla `productos` (Exactamente 5 campos)
    // 1. id, 2. nombre, 3. precio, 4. stock, 5. usuario_id
    $sql_productos = "CREATE TABLE IF NOT EXISTS `productos` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `nombre` VARCHAR(100) NOT NULL,
        `precio` DECIMAL(10,2) NOT NULL,
        `stock` INT NOT NULL,
        `usuario_id` INT NOT NULL,
        CONSTRAINT `fk_productos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    mysqli_query($connect, $sql_productos);

    // 4. Sembrado automático de datos de prueba si la tabla usuarios está vacía
    $check_users = mysqli_query($connect, "SELECT COUNT(*) as total FROM `usuarios`");
    $row_check = mysqli_fetch_assoc($check_users);
    if ($row_check['total'] == 0) {
        // Insertar 3 usuarios iniciales (5 campos)
        mysqli_query($connect, "INSERT INTO `usuarios` (`nombre`, `email`, `telefono`, `direccion`) VALUES
            ('Carlos Eduardo Mendoza', 'carlos.mendoza@gmail.com', '4921002233', 'Av. Hidalgo #104, Centro'),
            ('Mariana López Valenzuela', 'mariana.lopez@empresa.com', '4922004455', 'Calle Sonora #512, Col. Progreso'),
            ('Roberto Alejandro Castro', 'roberto.castro@outlook.com', '4923006677', 'Blvd. López Mateos #890, Suite 3B')");

        // Insertar productos iniciales asociados a los clientes (5 campos)
        mysqli_query($connect, "INSERT INTO `productos` (`nombre`, `precio`, `stock`, `usuario_id`) VALUES
            ('Laptop HP Pavilion 15', 14500.00, 10, 1),
            ('Mouse Óptico Inalámbrico Logitech', 320.50, 45, 1),
            ('Teclado Mecánico RGB Switch Blue', 890.00, 18, 1),
            ('Monitor Samsung 27 144Hz Full HD', 4200.00, 12, 2),
            ('Silla Ergonómica Gamer Pro', 3150.00, 8, 2),
            ('Disco Sólido SSD NVMe 1TB Kingston', 1350.00, 25, 3),
            ('Memoria RAM DDR4 16GB 3200MHz Corsair', 920.00, 30, 3)");
    }

    return $connect;
}
?>
