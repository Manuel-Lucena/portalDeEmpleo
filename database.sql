DROP DATABASE IF EXISTS portaldeempleo;
CREATE DATABASE portaldeempleo;
USE portaldeempleo;


-- 🔹 Crear tablas con DELETE CASCADE donde corresponde
CREATE TABLE ROL (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE USER (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombreUsuario VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    idRol INT NOT NULL,
    FOREIGN KEY (idRol) REFERENCES ROL(id) ON DELETE CASCADE
);

CREATE TABLE EMPRESA (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idUser INT UNIQUE,
    nombreEmpresa VARCHAR(100),
    telefono VARCHAR(20),
    direccion VARCHAR(150),
    personaContacto VARCHAR(50),
    email VARCHAR(100),
    logo VARCHAR(255),
    FOREIGN KEY (idUser) REFERENCES USER(id) ON DELETE CASCADE
);

CREATE TABLE EMPRESA_CANDIDATA (
    idUser INT PRIMARY KEY,
    nombreEmpresa VARCHAR(100),
    telefono VARCHAR(20),
    direccion VARCHAR(150),
    personaContacto VARCHAR(50),
    email VARCHAR(100),
    logo VARCHAR(255),
    FOREIGN KEY (idUser) REFERENCES USER(id) ON DELETE CASCADE
);

CREATE TABLE ALUMNO (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idUser INT UNIQUE,
    nombre VARCHAR(100),
    email VARCHAR(100),
    fecha_nacimiento DATE,
    direccion VARCHAR(100),
    telefono VARCHAR(50),
    foto VARCHAR(50),
    curriculum BLOB,
    FOREIGN KEY (idUser) REFERENCES USER(id) ON DELETE CASCADE
);

CREATE TABLE FAMILIA (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE CICLO (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('basico', 'medio', 'superior', 'especializacion') NOT NULL,
    idFamilia INT,
    FOREIGN KEY (idFamilia) REFERENCES FAMILIA(id) ON DELETE CASCADE
);

CREATE TABLE OFERTA (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idEmpresa INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT,
    fechaInicio DATE,
    fechaFin DATE,
    FOREIGN KEY (idEmpresa) REFERENCES EMPRESA(id) ON DELETE CASCADE
);

CREATE TABLE OFERTA_CICLO (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idOferta INT NOT NULL,
    idCiclo INT NOT NULL,
    FOREIGN KEY (idOferta) REFERENCES OFERTA(id) ON DELETE CASCADE,
    FOREIGN KEY (idCiclo) REFERENCES CICLO(id) ON DELETE CASCADE
);

CREATE TABLE ESTUDIOS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idAlumno INT NOT NULL,
    idCiclo INT NOT NULL,
    fechaInicio DATE,
    fechaFin DATE,
    FOREIGN KEY (idAlumno) REFERENCES ALUMNO(id) ON DELETE CASCADE,
    FOREIGN KEY (idCiclo) REFERENCES CICLO(id) ON DELETE CASCADE
);

CREATE TABLE SOLICITUD (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idAlumno INT NOT NULL,
    idOferta INT NOT NULL,
    fechaSolicitud DATE,
    estado ENUM('pendiente', 'aceptada', 'rechazada'),
    favorito BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (idAlumno) REFERENCES ALUMNO(idUser) ON DELETE CASCADE,
    FOREIGN KEY (idOferta) REFERENCES OFERTA(id) ON DELETE CASCADE
);

CREATE TABLE TOKEN (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idUser INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    FOREIGN KEY (idUser) REFERENCES USER(id) ON DELETE CASCADE
);

CREATE TABLE FORGOTTEN_PASSWORD (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idUser INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    FOREIGN KEY (idUser) REFERENCES USER(id) ON DELETE CASCADE
);

-- 🔹 Insertar datos de ejemplo
INSERT INTO ROL (nombre) VALUES ('admin'), ('empresa'), ('alumno');

INSERT INTO USER (nombreUsuario, password, idRol) VALUES
('admin', '$2y$10$2j15RkVlQURol6zEpZi9XeyQxP.Hf/eT1f3DckljjuIGDdQ9j3g.S', 1),
('empresa1', '$2y$10$VS.dli0qEhcPf7ExREQzke.oKG9gzHdx1FNdMUYiBOCBSSp3eCHbC', 2),
('empresa2', '$2y$10$im0dXBEGSMBiP2T/Td78Ae2F2atlNTHxeHdiOp2NWhWRkjexZLhp2', 2),
('empresa3', '$2y$10$T/K8BHFkYKBfjbj6k16OleYoIYSuqN84U9Lr5FW6yl.8ygs3PDncy', 2),
('alumno1', '$2y$10$OSwTAGFKzsiMkmwrzGaTIeaCGjbJR4bCt6qyJmyDKzdHWFJCd2GrO', 3),
('alumno2', '$2y$10$2LWfzVWVV1Qneb.M.OgegONvqO2Uwu9l8XdQ2thgMvPk6iXTzPZGa', 3);

INSERT INTO FAMILIA (nombre) VALUES ('Informática'), ('Administración');

INSERT INTO CICLO (nombre, tipo, idFamilia) VALUES 
('Desarrollo Web', 'medio', 1),
('Administración de Empresas', 'superior', 2);

-- Empresas aprobadas
INSERT INTO EMPRESA (idUser, nombreEmpresa, telefono, direccion, personaContacto, email, logo) VALUES
(2, 'Empresa Uno', '911223344', 'Calle Falsa 123', 'Juan Pérez', 'empresa1@empresa.com', 'logo1.png'),
(3, 'Empresa Dos', '933556677', 'Avenida Siempre Viva 45', 'Ana Gómez', 'empresa2@empresa.com', 'logo2.png');

-- Empresas candidatas
INSERT INTO EMPRESA_CANDIDATA (idUser, nombreEmpresa, telefono, direccion, personaContacto, email, logo) VALUES
(4, 'Empresa Tres', '922334455', 'Calle Luna 8', 'Luis Martínez', 'empresa3@empresa.com', 'logo3.png');

-- Alumnos
INSERT INTO ALUMNO (idUser, nombre, email, fecha_nacimiento, direccion, telefono, curriculum) VALUES
(5, 'Laura Gómez', 'laura@example.com', '2000-01-15', 'Calle Falsa 123', '911223344', NULL),
(6, 'Carlos Pérez', 'carlos@example.com', '1999-07-20', 'Avenida Siempre Viva 45', '933556677', NULL);

-- Ofertas (más completas)
INSERT INTO OFERTA (idEmpresa, titulo, descripcion, fechaInicio, fechaFin) VALUES
(1, 'Desarrollador Junior', 'Se busca programador junior PHP.', '2025-11-01', '2025-12-01'),
(2, 'Asistente Administrativo', 'Se busca asistente administrativo.', '2025-11-05', '2025-12-05'),
(1, 'Frontend React', 'Buscamos programador frontend con conocimientos en React.', '2025-10-10', '2025-12-15'),
(1, 'Backend Java', 'Desarrollo de APIs REST en Java Spring Boot.', '2025-09-01', '2025-12-31'),
(1, 'Soporte Técnico', 'Atención y resolución de incidencias informáticas.', '2025-08-15', '2025-11-30'),
(2, 'Contable Junior', 'Control de facturas y conciliaciones bancarias.', '2025-09-20', '2025-12-31'),
(2, 'Gestor de Nóminas', 'Gestión laboral y nóminas de clientes.', '2025-10-01', '2026-01-15'),
(2, 'Recepcionista de Oficina', 'Atención al público y gestión administrativa.', '2025-11-01', '2025-12-31'),
(1, 'Fullstack Developer', 'Perfil mixto con experiencia en frontend y backend.', '2025-11-10', '2026-02-10'),
(1, 'Prácticas en Ciberseguridad', 'Aprendiz con interés en seguridad informática.', '2025-10-01', '2025-12-31');

-- Ofertas asociadas a ciclos
INSERT INTO OFERTA_CICLO (idOferta, idCiclo) VALUES
(1, 1),
(2, 2),
(3, 1),
(4, 1),
(5, 1),
(6, 2),
(7, 2),
(8, 2),
(9, 1),
(10, 1);

-- Estudios de alumnos
INSERT INTO ESTUDIOS (idAlumno, idCiclo, fechaInicio, fechaFin) VALUES
(2, 1, '2024-09-01', '2025-06-30'),  -- Carlos estudia Desarrollo Web
(1, 2, '2024-09-01', '2025-06-30'); -- Laura estudia Administración

-- Solicitudes de prueba

-- Solicitudes de prueba completas para ambos alumnos y ambas empresas
-- Alumno Carlos Pérez (idUser=6) solicita ofertas de Empresa Uno
INSERT INTO SOLICITUD (idAlumno, idOferta, fechaSolicitud, estado, favorito) VALUES
(6, 1, '2025-10-03', 'pendiente', 1),
(6, 5, '2025-10-07', 'aceptada', 0),
(6, 9, '2025-10-20', 'pendiente', 0);

-- Alumno Carlos Pérez solicita ofertas de Empresa Dos
INSERT INTO SOLICITUD (idAlumno, idOferta, fechaSolicitud, estado, favorito) VALUES
(5, 2, '2025-10-04', 'aceptada', 1),
(5, 7, '2025-10-12', 'pendiente', 0),
(5, 8, '2025-10-18', 'rechazada', 0);



