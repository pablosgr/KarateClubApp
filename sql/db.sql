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

INSERT INTO socio (nombre, edad, pass, tipo, usuario, telefono, foto) VALUES
('Administrador', null, '$2y$10$zPLa7gF1W/v7aTpgOK0bYORTpZwEM3ssaf2fijbjvKUy/1cyzUdiG', 'admin', 'admin', null, null);
-- ('Juan Palomo', 56, 'contraseña1', 'socio', 'juanperez', '+34612345678', '../../pics/avatar3.jpg');
-- ('María López', 42, 'contraseña2', 'socio', 'marialopez', '+34623456789', '../../pics/avatar1.jpg'),
-- ('Pedro Gómez', 25, 'contraseña3', 'socio', 'pedrogomez', '+34634567890', '../../pics/avatar2.jpg'),
-- ('Lauren Tsai', 28, 'contraseña4', 'socio', 'lautsai', '+34667124890', '../../pics/avatar4.jpg');

INSERT INTO testimonio (autor, contenido, fecha) VALUES
(1, 'Este club ha cambiado mi vida, he mejorado en disciplina y condición física.', '2024-09-20'),
(1, 'Las clases son excelentes, los entrenadores son muy profesionales y amables.', '2024-10-05'),
(1, 'El ambiente es increíble, siempre es motivador venir a entrenar.', '2024-11-20'),
(1, 'Me siento parte de una familia. Es un lugar que recomiendo a todos.', '2024-11-25');

INSERT INTO citas (socio, servicio, fecha, hora, cancelada) VALUES
(1, 1, '2024-10-10', '18:00:00', 0),
(1, 2, '2024-11-15', '19:00:00', 0),
(1, 3, '2024-11-15', '19:00:00', 1),
(1, 1, '2024-12-05', '17:30:00', 0),
(1, 3, '2025-01-15', '20:00:00', 0),
(1, 5, '2025-02-03', '10:30:00', 0),
(1, 2, '2024-02-07', '18:00:00', 1),
(1, 2, '2024-12-10', '19:00:00', 1),
(1, 4, '2024-12-02', '17:15:00', 0),
(1, 2, '2024-12-02', '19:30:00', 0),
(1, 5, '2024-12-02', '18:00:00', 0);

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