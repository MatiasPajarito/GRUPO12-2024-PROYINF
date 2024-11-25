CREATE DATABASE IF NOT EXISTS boletines_db;
USE boletines_db;

-- Luego crear la tabla
CREATE TABLE boletines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(50) NOT NULL,
    temas VARCHAR(100) NOT NULL,
    plazo INT NOT NULL,
    estado VARCHAR(30) DEFAULT 'Registrado',
    descripcion_extra VARCHAR(200),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    noticias VARCHAR(500),
    pub_cientificas VARCHAR(500),
    patentes VARCHAR(500),
    eventos VARCHAR(500),
    proyectos VARCHAR(500)
);