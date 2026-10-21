CREATE DATABASE IF NOT EXISTS base2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE base2;
CREATE TABLE IF NOT EXISTS alumnos (
 codigo INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(100) NOT NULL,
 codigocurso INT NOT NULL,
 mail VARCHAR(150) NOT NULL UNIQUE
);
INSERT IGNORE INTO alumnos (codigo, nombre, codigocurso, mail) VALUES
 (1, 'Ana Pérez', 101, 'ana@ejemplo.com'),
 (2, 'Juan López', 102, 'juan@ejemplo.com');
