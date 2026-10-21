CREATE DATABASE IF NOT EXISTS Compras CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE Compras;
CREATE TABLE IF NOT EXISTS `catálogo` (
 id INT AUTO_INCREMENT PRIMARY KEY,
 producto VARCHAR(100) NOT NULL,
 precio DECIMAL(11,2) NOT NULL
);
INSERT INTO `catálogo` (producto,precio) VALUES
 ('Cuaderno',3500.00),('Lapicera',750.50),('Mochila',29500.00);
