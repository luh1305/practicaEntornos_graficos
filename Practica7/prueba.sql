CREATE DATABASE IF NOT EXISTS prueba CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE prueba;
CREATE TABLE IF NOT EXISTS buscador (
 canciones VARCHAR(200) NOT NULL
);
INSERT INTO buscador (canciones) VALUES
 ('De música ligera'),('Persiana americana'),('La flaca'),('Rayando el sol');
