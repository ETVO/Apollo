DROP TABLE IF EXISTS autor;

CREATE TABLE autor (
    id_autor SERIAL PRIMARY KEY NOT NULL,
    nome TEXT NOT NULL,
    ano_nasc INT,
    ano_morte INT,
    excluido BOOLEAN NOT NULL DEFAULT FALSE
);