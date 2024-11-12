-- DATABASE

DROP DATABASE IF EXISTS club_deportivo;
CREATE DATABASE club_deportivo;
USE club_deportivo;

CREATE TABLE socio (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(150) NOT NULL,
    edad INTEGER NOT NULL,
    pass VARCHAR(20) NOT NULL,
    usuario VARCHAR(255) NOT NULL UNIQUE,
    telefono VARCHAR(15) NOT NULL UNIQUE,
    foto VARCHAR(255)
);

CREATE TABLE servicio (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    descripcion VARCHAR(255) NOT NULL,
    duracion INTEGER NOT NULL,
    unidad_duracion VARCHAR(50) NOT NULL,
    precio FLOAT NOT NULL DEFAULT 0.00
);

CREATE TABLE testimonio (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    autor INTEGER,
    contenido VARCHAR(255) NOT NULL,
    fecha DATE NOT NULL,
    CONSTRAINT escritopor FOREIGN KEY (autor) REFERENCES socio (id) ON DELETE SET NULL
);

CREATE TABLE noticia (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(150) NOT NULL,
    contenido TEXT NOT NULL,
    imagen VARCHAR(255),
    fecha_publicacion DATE NOT NULL
);

CREATE TABLE citas (
    socio INTEGER,
    servicio INTEGER,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    PRIMARY KEY (socio, servicio),
    FOREIGN KEY (socio) REFERENCES socio (id) ON DELETE CASCADE,
    FOREIGN KEY (servicio) REFERENCES servicio (id) ON DELETE CASCADE,
    UNIQUE (socio, fecha, hora)
);

-- INSERTs

INSERT INTO servicio (descripcion, duracion, unidad_duracion, precio) VALUES
('Clases de Karate Infantil', 60, 'minutos', 30.00),
('Clases de Karate Adulto', 90, 'minutos', 40.00),
('Entrenamiento Personalizado', 45, 'minutos', 50.00),
('Taller de Defensa Personal', 120, 'minutos', 25.00),
('Seminario de Competencia', 180, 'minutos', 100.00);

INSERT INTO noticia (titulo, contenido, imagen, fecha_publicacion) VALUES
('Gran Éxito en el Torneo de Karate', 'Nuestro club tuvo una destacada participación en el torneo nacional de karate, con varios premios y reconocimientos.', './pics/news2.webp', '2024-10-01'),
('Inauguración de Nuevas Instalaciones', 'Estamos emocionados de anunciar la apertura de nuestras nuevas instalaciones dedicadas al karate.', './pics/news3.jpg', '2024-09-15'),
('Clases para Nuevos Miembros', '¡Inscripciones abiertas! Únete a nuestras clases de karate para principiantes.', NULL, '2024-09-05'),
('Celebración del Día del Karate', 'Realizaremos un evento especial el próximo mes para celebrar el Día Internacional del Karate.', './pics/news1.webp', '2024-11-01');

INSERT INTO socio (nombre, edad, pass, usuario, telefono, foto) VALUES
('Juan Pérez', 28, 'contraseña1', 'juanperez', '612345678', '../pics/avatar3.jpg'),
('María López', 34, 'contraseña2', 'marialopez', '623456789', '../pics/avatar1.jpg'),
('Pedro Gómez', 22, 'contraseña3', 'pedrogomez', '634567890', '../pics/avatar2.jpg');

INSERT INTO testimonio (autor, contenido, fecha) VALUES
(1, 'Este club ha cambiado mi vida, he mejorado en disciplina y condición física.', '2024-09-20'),
(2, 'Las clases son excelentes, los entrenadores son muy profesionales y amables.', '2024-10-05');

INSERT INTO citas (socio, servicio, fecha, hora) VALUES
(1, 1, '2024-10-10', '18:00:00'),  -- Cita el mes pasado
(2, 2, '2024-11-15', '19:00:00');  -- Cita este mes