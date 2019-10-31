DROP TABLE IF EXISTS livro;

CREATE TABLE livro (
    id_livro SERIAL PRIMARY KEY NOT NULL,
    titulo TEXT NOT NULL,
    genero TEXT NOT NULL,
    autor TEXT NOT NULL,
    editora TEXT NOT NULL,
    ano INT,
    edicao INT,
    disponivel BOOLEAN NOT NULL DEFAULT TRUE,
    qtde INT NOT NULL DEFAULT 1,
    obs TEXT DEFAULT NULL,
    excluido BOOLEAN NOT NULL DEFAULT FALSE
);