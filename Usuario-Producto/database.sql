-- ========================================================
-- SISTEMA DE GESTIÓN DE USUARIOS Y PRODUCTOS
-- Script SQL de Creación y Datos Iniciales
-- Base de Datos: sistema_usuarios_productos
-- ========================================================

CREATE DATABASE IF NOT EXISTS `sistema_usuarios_productos`
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `sistema_usuarios_productos`;

-- --------------------------------------------------------
-- 1. TABLA: usuarios (Exactamente 5 campos)
-- 1. id
-- 2. nombre
-- 3. email
-- 4. telefono
-- 5. direccion
-- --------------------------------------------------------
DROP TABLE IF EXISTS `productos`;
DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `telefono` VARCHAR(20) NOT NULL,
    `direccion` VARCHAR(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. TABLA: productos (Exactamente 5 campos)
-- 1. id
-- 2. nombre
-- 3. precio
-- 4. stock
-- 5. usuario_id
-- --------------------------------------------------------
CREATE TABLE `productos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL,
    `precio` DECIMAL(10,2) NOT NULL,
    `stock` INT NOT NULL,
    `usuario_id` INT NOT NULL,
    CONSTRAINT `fk_productos_usuarios`
        FOREIGN KEY (`usuario_id`) 
        REFERENCES `usuarios` (`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. DATOS SEMILLA (DML)
-- --------------------------------------------------------
INSERT INTO `usuarios` (`nombre`, `email`, `telefono`, `direccion`) VALUES
('Carlos Eduardo Mendoza', 'carlos.mendoza@gmail.com', '4921002233', 'Av. Hidalgo #104, Centro'),
('Mariana López Valenzuela', 'mariana.lopez@empresa.com', '4922004455', 'Calle Sonora #512, Col. Progreso'),
('Roberto Alejandro Castro', 'roberto.castro@outlook.com', '4923006677', 'Blvd. López Mateos #890, Suite 3B');

INSERT INTO `productos` (`nombre`, `precio`, `stock`, `usuario_id`) VALUES
('Laptop HP Pavilion 15', 14500.00, 10, 1),
('Mouse Óptico Inalámbrico Logitech', 320.50, 45, 1),
('Teclado Mecánico RGB Switch Blue', 890.00, 18, 1),
('Monitor Samsung 27 144Hz Full HD', 4200.00, 12, 2),
('Silla Ergonómica Gamer Pro', 3150.00, 8, 2),
('Disco Sólido SSD NVMe 1TB Kingston', 1350.00, 25, 3),
('Memoria RAM DDR4 16GB 3200MHz Corsair', 920.00, 30, 3);

