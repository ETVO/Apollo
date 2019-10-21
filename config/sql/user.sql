DROP TABLE IF EXISTS usuario;

CREATE TABLE usuario (
    id_user SERIAL PRIMARY KEY,
    nome VARCHAR(70) NOT NULL,
    ra VARCHAR(7) UNIQUE,
    login VARCHAR(20) UNIQUE NOT NULL,
    senha TEXT NOT NULL,
    ano INT,
    tipo VARCHAR(15) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    telefone VARCHAR(16) UNIQUE,
    admin BOOLEAN NOT NULL DEFAULT FALSE,
    bloqueado BOOLEAN NOT NULL DEFAULT FALSE,
    excluido BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO usuario VALUES (DEFAULT, 'Admin', null, 'admin', '5eb30e9a4d77fc4e9edd9859ff0f5782', null, 'admin', '', null, TRUE, DEFAULT, DEFAULT);