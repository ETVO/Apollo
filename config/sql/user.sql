DROP TABLE IF EXISTS user;

CREATE TABLE user (
    id_user SERIAL PRIMARY KEY,
    nome VARCHAR(70) NOT NULL,
    ra VARCHAR(7),
    login VARCHAR(20) UNIQUE NOT NULL,
    senha TEXT NOT NULL,
    turma VARCHAR(7),
    tipo VARCHAR(15) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefone VARCHAR(16),
    admin BOOLEAN NOT NULL DEFAULT FALSE,
    bloqueado BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO user VALUES (DEFAULT, 'Admin', null, 'admin', '5eb30e9a4d77fc4e9edd9859ff0f5782', null, 'admin', '', null, TRUE, DEFAULT);