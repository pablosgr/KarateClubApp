-- DATABASE

DROP DATABASE IF EXISTS tienda_club_deportivo;
CREATE DATABASE tienda_club_deportivo;
USE tienda_club_deportivo;

CREATE TABLE productos (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL UNIQUE,
    precio FLOAT NOT NULL,
    categoria VARCHAR(150) NOT NULL,
    disponible TINYINT(1) NOT NULL DEFAULT 1,
    cantidad INTEGER NOT NULL DEFAULT 0
);

-- INSERTs

INSERT INTO productos (nombre, precio, categoria, disponible, cantidad) VALUES 
('Cinturón Blanco', 10.50, 'Cinturones', 1, 15),
('Cinturón Negro', 25.00, 'Cinturones', 0, 0),
('Cinturón Azul', 12.00, 'Cinturones', 1, 10),
('Karategui Básico', 45.00, 'Karateguis', 1, 13),
('Karategui Avanzado', 85.00, 'Karateguis', 1, 15),
('Guantillas de Entrenamiento', 20.00, 'Protecciones', 1, 25),
('Guantillas de Competición', 30.00, 'Protecciones', 1, 10),
('Protector Bucal Infantil', 5.00, 'Protecciones', 1, 8),
('Protector Bucal Adulto', 7.00, 'Protecciones', 0, 0),
('Espinilleras', 18.00, 'Protecciones', 1, 10),
('Casco Protector', 55.00, 'Protecciones', 1, 5),
('Protector de Pecho', 40.00, 'Protecciones', 1, 7),
('Bebida Isotónica 500ml', 2.50, 'Bebidas', 1, 25),
('Pack de 6 Bebidas Isotónicas', 14.00, 'Bebidas', 1, 10),
('Bálsamo para Dolores Musculares', 8.50, 'Salud', 1, 20),
('Tobilleras de Compresión', 12.00, 'Salud', 1, 30),
('Cinta Kinesiológica', 12.00, 'Salud', 1, 25),
('Cuerda para Saltar', 10.00, 'Accesorios', 1, 20),
('Bolsa de Entrenamiento', 35.00, 'Accesorios', 1, 4),
('DVD Técnicas Básicas de Kárate', 15.00, 'Material Didáctico', 1, 10);