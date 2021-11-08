CREATE TABLE manometro_usuarios(  
    id SERIAL NOT NULL primary key,
    username VARCHAR(45) UNIQUE,
    password TEXT 
);

CREATE TABLE  manometro_pozos (
    id SERIAL NOT NULL primary key,
    name VARCHAR(45) UNIQUE,
    descripcion VARCHAR(255)
);

CREATE TABLE  manometro_medidas (
    id SERIAL NOT NULL primary key,
    lectura DECIMAL(7,2),
    tiempo TIMESTAMP,
    id_pozo BIGINT,
    FOREIGN KEY(id_pozo) REFERENCES manometro_pozos(id)
);