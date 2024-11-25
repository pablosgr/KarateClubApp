-- DATABASE

DROP DATABASE IF EXISTS club_deportivo;
CREATE DATABASE club_deportivo;
USE club_deportivo;

CREATE TABLE socio (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(150) NOT NULL,
    edad INTEGER NOT NULL,
    pass VARCHAR(20) NOT NULL,
    usuario VARCHAR(255) NOT NULL UNIQUE, -- generar mensaje de error al intentar añadir duplicadas
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
    cancelada TINYINT DEFAULT 0,
    PRIMARY KEY (socio, servicio, fecha, hora),
    FOREIGN KEY (socio) REFERENCES socio (id) ON DELETE CASCADE,
    FOREIGN KEY (servicio) REFERENCES servicio (id) ON DELETE CASCADE
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

INSERT INTO socio (nombre, edad, pass, usuario, telefono, foto) VALUES
('Juan Pérez', 56, 'contraseña1', 'juanperez', '+34612345678', '../pics/avatar3.jpg'),
('María López', 42, 'contraseña2', 'marialopez', '+34623456789', '../pics/avatar1.jpg'),
('Pedro Gómez', 25, 'contraseña3', 'pedrogomez', '+34634567890', '../pics/avatar2.jpg'),
('Lauren Tsai', 28, 'contraseña4', 'ltsai23', '+34667124890', '../pics/avatar4.jpg');

INSERT INTO testimonio (autor, contenido, fecha) VALUES
(1, 'Este club ha cambiado mi vida, he mejorado en disciplina y condición física.', '2024-09-20'),
(2, 'Las clases son excelentes, los entrenadores son muy profesionales y amables.', '2024-10-05'),
(3, 'El ambiente es increíble, siempre es motivador venir a entrenar.', '2024-11-20'),
(4, 'Me siento parte de una familia. Es un lugar que recomiendo a todos.', '2024-11-25');

INSERT INTO citas (socio, servicio, fecha, hora, cancelada) VALUES
(1, 1, '2024-10-10', '18:00:00', 0),
(2, 2, '2024-11-15', '19:00:00', 0),
(3, 2, '2024-11-15', '19:00:00', 1),
(1, 1, '2024-12-05', '17:30:00', 0),
(4, 3, '2025-01-15', '20:00:00', 0),
(3, 5, '2025-02-03', '10:30:00', 0),
(4, 2, '2024-02-07', '18:00:00', 1),
(2, 2, '2024-12-10', '19:00:00', 1);