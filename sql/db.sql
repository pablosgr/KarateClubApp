-- DATABASE

DROP DATABASE IF EXISTS club_deportivo;
CREATE DATABASE club_deportivo;
USE club_deportivo;

CREATE TABLE socio (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(150) NOT NULL,
    edad INTEGER,
    pass VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'socio') NOT NULL,
    usuario VARCHAR(255) NOT NULL UNIQUE,
    telefono VARCHAR(15) UNIQUE,
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
    cancelada TINYINT DEFAULT 0,
    PRIMARY KEY (socio, servicio, fecha, hora),
    FOREIGN KEY (socio) REFERENCES socio (id) ON DELETE CASCADE,
    FOREIGN KEY (servicio) REFERENCES servicio (id) ON DELETE CASCADE
);

CREATE TABLE productos (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL UNIQUE,
    precio FLOAT NOT NULL,
    categoria VARCHAR(150) NOT NULL,
    imagen VARCHAR(255),
    disponible TINYINT(1) NOT NULL DEFAULT 1,
    cantidad INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE api_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_socio INT NOT NULL,
    api_key VARCHAR(255) NOT NULL UNIQUE,
    creada_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    activa TINYINT(1) DEFAULT 1
);

-- INSERTs

INSERT INTO servicio (descripcion, duracion, unidad_duracion, precio) VALUES
('Clase de Karate', 60, 'minutos', 30.00),
('Clase de Karate Avanzado', 90, 'minutos', 40.00),
('Entrenamiento Personalizado', 45, 'minutos', 50.00),
('Taller de Defensa Personal', 120, 'minutos', 25.00),
('Seminario de Competencia', 180, 'minutos', 100.00);

INSERT INTO noticia (titulo, contenido, imagen, fecha_publicacion) VALUES
('Gran Éxito en el Torneo de Karate',
 'Nuestro club tuvo una destacada participación en el torneo nacional de karate, celebrado en la ciudad de Valencia. Nuestros atletas demostraron su gran habilidad y disciplina, logrando varios premios en diferentes categorías. Felicitamos especialmente a nuestro campeón local, quien se alzó con el primer lugar en su categoría y dejó en alto el nombre de nuestro club. Agradecemos a todos los que apoyaron y acompañaron a nuestro equipo en este evento inolvidable.',
 '../../pics/news2.webp', '2024-10-01'),

('Inauguración de Nuevas Instalaciones',
 'Estamos emocionados de anunciar la apertura de nuestras nuevas instalaciones dedicadas al karate. Este espacio, diseñado para ofrecer un entorno óptimo para el entrenamiento, incluye tatamis de última generación, un área de pesas, y una sala para eventos especiales y seminarios. Estas nuevas instalaciones reflejan nuestro compromiso con el crecimiento del club y con brindar a nuestros miembros el mejor espacio posible para su desarrollo en el karate. ¡Ven y conócenos!',
 '../../pics/news3.jpg', '2024-09-15'),

('Clases para Nuevos Miembros',
 '¡Inscripciones abiertas para el próximo ciclo! Si tienes interés en aprender karate, este es el momento ideal para unirte. Ofrecemos clases para principiantes, enfocadas en técnicas básicas, disciplina y trabajo en equipo. Además, los nuevos miembros podrán conocer la historia y filosofía del karate, y beneficiarse de nuestro programa de entrenamiento personalizado. Las plazas son limitadas, ¡así que no pierdas esta oportunidad!',
 '../../pics/nuevos-miembros.jpg', '2024-09-05'),

('Celebración del Día del Karate',
 'Estamos muy emocionados de anunciar un evento especial para celebrar el Día Internacional del Karate el próximo mes. Invitamos a todos nuestros miembros y a la comunidad local a unirse a esta celebración en nuestras instalaciones, donde tendremos exhibiciones de kata, kumite, y charlas con instructores invitados. También habrá actividades para todas las edades y una ceremonia especial para reconocer los logros de nuestros alumnos. ¡No te lo pierdas!',
 '../../pics/news1.webp', '2024-11-01'),

('Seminario Internacional de Karate',
 'Nuestro club será anfitrión del Seminario Internacional de Karate 2024, donde contaremos con la presencia de destacados maestros de diversas partes del mundo. Este evento ofrece una oportunidad única para aprender de expertos en diferentes estilos de karate y técnicas avanzadas de combate. Se impartirán clases de kata, kumite y defensa personal. Las inscripciones están abiertas para todos los niveles, pero el cupo es limitado.',
 '../../pics/seminario.webp', '2024-11-20'),

('Actualización de Protocolos de Seguridad',
 'En nuestro compromiso con la seguridad de todos nuestros miembros, hemos implementado nuevos protocolos de seguridad dentro y fuera del dojo. Estas medidas incluyen capacitaciones en primeros auxilios para nuestros instructores, revisiones periódicas de equipos de entrenamiento y la implementación de normas de higiene más estrictas. La seguridad y el bienestar de nuestros estudiantes son nuestra máxima prioridad.',
 '../../pics/seguridad.webp', '2024-08-25'),

('Campeonato Juvenil de Karate',
 'El próximo mes, nuestro club participará en el Campeonato Juvenil de Karate, una competencia regional que reúne a los mejores talentos jóvenes. Los alumnos han estado entrenando intensamente para este evento, y confiamos en que sus habilidades y espíritu de equipo los llevarán al éxito. Animamos a todos a venir y apoyar a nuestros jóvenes atletas en este emocionante campeonato.',
 '../../pics/campeonato-juvenil.jpg', '2024-10-10'),

('Examen de Cinturones Negros',
 'Nos complace anunciar que varios de nuestros miembros se presentarán para el examen de cinturón negro, uno de los mayores logros en el camino del karateka. Estos estudiantes han demostrado una dedicación excepcional y están listos para enfrentarse a esta importante prueba. Invitamos a familiares y amigos a presenciar este evento y a apoyar a nuestros futuros cinturones negros.',
 '../../pics/cintu-negro.jpg', '2024-11-05'),

('Práctica de Kata en el Parque',
 'Para aprovechar los días soleados, hemos organizado una práctica especial de kata en el parque de la ciudad. Este evento gratuito está abierto a todos los niveles y es una excelente oportunidad para entrenar al aire libre y aprender en un entorno diferente. Los instructores guiarán las prácticas en grupos, y al final del día habrá una exhibición de kata avanzada por nuestros alumnos más experimentados.',
 '../../pics/karate-park.jpg', '2024-10-25'),

('Recaudación de Fondos para Equipos Nuevos',
 'Estamos organizando una campaña de recaudación de fondos para renovar nuestro equipo de entrenamiento y mejorar la experiencia de nuestros miembros. Durante las próximas semanas, realizaremos varias actividades, incluyendo una rifa y un evento especial de exhibiciones. Invitamos a todos a participar y contribuir para que nuestro club siga siendo un lugar seguro y cómodo para entrenar.',
 '../../pics/equipos.jpg', '2024-11-15');

INSERT INTO socio (id, nombre, edad, pass, tipo, usuario, telefono, foto) VALUES
(1, 'Administrador', null, '$2y$10$zPLa7gF1W/v7aTpgOK0bYORTpZwEM3ssaf2fijbjvKUy/1cyzUdiG', 'admin', 'admin', null, null),
(2, 'Juan Pérez', 56, '$2y$10$MqYuPUN94KcvdxZxkIjeduFkzggaMr.XKeeU3xzGIh2p1sv5grmIO', 'socio', 'juanperez', '+34612345678', '../../pics/avatar3.jpg'),
(3, 'María López', 42, '$2y$10$jVkwLxktfkIn7UqW7769wec8ZH1C6.z74fwIbZaZZ6Ar.IFxrGP0e', 'socio', 'marialopez', '+34623456789', '../../pics/avatar1.jpg'),
(4, 'Pedro Gómez', 25, '$2y$10$idjaaatNg9Cu6ARNmH1ip.tZmgwEzE5ZkaoJ6g4nqgwjSW1IDTGQe', 'socio', 'pedrogomez', '+34634567890', '../../pics/avatar2.jpg'),
(5, 'Lauren Tsai', 28, '$2y$10$sDw1Zky5owtlmy/uovDIT.j/JyLAmN7jzkQjigc3Qp4pcjDndigZC', 'socio', 'ltsai', '+34667124890', '../../pics/avatar4.jpg');
-- usuario con id 2: contraseña1; usuario con id 3: contraseña2..

INSERT INTO testimonio (autor, contenido, fecha) VALUES
(2, 'Este club ha cambiado mi vida, he mejorado en disciplina y condición física.', '2024-09-20'),
(5, 'Las clases son excelentes, los entrenadores son muy profesionales y amables.', '2024-10-05'),
(3, 'El ambiente es increíble, siempre es motivador venir a entrenar.', '2024-11-20'),
(4, 'Me siento parte de una familia. Es un lugar que recomiendo a todos.', '2024-11-25');

INSERT INTO citas (socio, servicio, fecha, hora, cancelada) VALUES
(2, 1, '2025-05-06', '18:00:00', 0),
(3, 2, '2025-04-15', '19:00:00', 0),
(4, 3, '2025-04-15', '19:00:00', 1),
(5, 1, '2025-02-14', '17:30:00', 0),
(5, 3, '2025-01-15', '20:00:00', 0),
(2, 5, '2025-02-03', '10:30:00', 0),
(4, 2, '2025-03-22', '18:00:00', 1),
(3, 2, '2025-01-10', '19:00:00', 1),
(2, 4, '2025-03-10', '17:15:00', 0),
(2, 2, '2025-02-28', '19:30:00', 0),
(3, 2, '2025-02-28', '20:30:00', 0),
(4, 2, '2025-02-28', '17:30:00', 0),
(5, 5, '2025-01-12', '18:00:00', 0);

INSERT INTO productos (nombre, precio, categoria, imagen, disponible, cantidad) VALUES 
('Cinturón Blanco', 10.50, 'Cinturones', '../../pics/products/white_belt.jpg', 1, 15),
('Cinturón Negro', 25.00, 'Cinturones', '../../pics/products/black_belt.webp', 0, 0),
('Cinturón Azul', 12.00, 'Cinturones', '../../pics/products/blue_belt.webp', 1, 10),
('Karategui Básico', 45.00, 'Karateguis', '../../pics/products/karategui.jpg', 1, 13),
('Karategui Avanzado', 85.00, 'Karateguis', '../../pics/products/karategui.jpg', 1, 15),
('Guantillas de Entrenamiento', 20.00, 'Protecciones', '../../pics/products/guantillas.jpeg', 1, 25),
('Guantillas de Competición', 30.00, 'Protecciones', '../../pics/products/guantillas.jpeg', 1, 10),
('Protector Bucal Infantil', 5.00, 'Protecciones', '../../pics/products/bucal_protector.jpg', 1, 8),
('Protector Bucal Adulto', 7.00, 'Protecciones', '../../pics/products/bucal_protector.jpg', 0, 0),
('Espinilleras', 18.00, 'Protecciones', '../../pics/products/espinilleras.webp', 1, 10),
('Casco Protector', 55.00, 'Protecciones', '../../pics/products/casco.jpg', 1, 5),
('Protector de Pecho', 40.00, 'Protecciones', '../../pics/products/pecho.webp', 1, 7),
('Bebida Isotónica 500ml', 2.50, 'Bebidas', '../../pics/products/isotonic.jpg', 1, 25),
('Pack de 6 Bebidas Isotónicas', 14.00, 'Bebidas', '../../pics/products/isotonic.jpg', 1, 10),
('Bálsamo para Dolores Musculares', 8.50, 'Salud', '../../pics/products/balsamo.jpg', 1, 20),
('Tobilleras de Compresión', 12.00, 'Salud', '../../pics/products/tobillera.jpg', 1, 30),
('Cinta Kinesiológica', 12.00, 'Salud', '../../pics/products/cinta_kine.png', 1, 25),
('Cuerda para Saltar', 10.00, 'Accesorios', '../../pics/products/cuerda.jpg', 1, 20),
('Bolsa de Entrenamiento', 35.00, 'Accesorios', '../../pics/products/saco.jpg', 1, 4),
('DVD Técnicas Básicas de Kárate', 15.00, 'Material Didáctico', '../../pics/products/dvd.jpg', 1, 10);

INSERT INTO api_keys (id_socio, api_key) VALUES
(1, '49aad7facef37fe40034dde5777533d8a8b60016f33c497dc7fbbbd23e7d91cb'),
(2, '6419cde2b51efb0b2c949c0586468f874e15afb6caebcbcce45f1a19f048e7e4'),
(3, 'f6b665a728faee1d47f17d22a44108cd72c4f9072535a960a3f266d7507c007f'),
(4, 'b4b541436b35a4c4e4e1c292435a959c3f5cb1a50d053e1eb0a6fa4b40d90f77'),
(5, 'f233438a5745db99863c7d096ebd403dac20ea1bf0f6f0b39386d567d0c44433');

DELIMITER //

CREATE TRIGGER actualizar_disponibilidad
BEFORE UPDATE ON productos
FOR EACH ROW
BEGIN
    SET NEW.disponible = IF(NEW.cantidad = 0, 0, 1);
END //

DELIMITER ;