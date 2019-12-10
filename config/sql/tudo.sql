DROP TABLE IF EXISTS caixa;

CREATE TABLE caixa (
    id SERIAL PRIMARY KEY NOT NULL,
    valor NUMERIC(5,2) NOT NULL,
    descricao TEXT NOT NULL,
    tipo CHAR NOT NULL,
    data DATE NOT NULL,
    excluido BOOLEAN NOT NULL DEFAULT FALSE
);

/* tipo -> 'e' || 's' */

DROP TABLE IF EXISTS config;

CREATE TABLE config (
    id_config SERIAL PRIMARY KEY NOT NULL,
    nome VARCHAR(16) NOT NULL UNIQUE,
    valor TEXT NOT NULL,
    tipo TEXT NOT NULL,
    data TIMESTAMP NOT NULL
);

INSERT INTO config VALUES (DEFAULT, 'multa', '1.00', 'float', NOW());
INSERT INTO config VALUES (DEFAULT, 'dias_dev', '10', 'int', NOW());
INSERT INTO config VALUES (DEFAULT, 'std_pass', 'senha', 'string', NOW());

DROP TABLE IF EXISTS emprestimo;

CREATE TABLE emprestimo (
    id_emprestimo SERIAL PRIMARY KEY NOT NULL,
    id_livro BIGINT REFERENCES livro(id_livro) ON UPDATE CASCADE ON DELETE CASCADE,
    id_admin BIGINT REFERENCES user(id_user) ON UPDATE CASCADE ON DELETE CASCADE,
    id_user BIGINT REFERENCES user(id_user) ON UPDATE CASCADE ON DELETE CASCADE,
    data_emp DATE NOT NULL,
    data_prev_dev DATE NOT NULL,
    data_dev DATE,
    obs TEXT,
    devolvido BOOLEAN NOT NULL DEFAULT FALSE,
    excluido BOOLEAN NOT NULL DEFAULT FALSE
);

DROP TABLE IF EXISTS livro;

CREATE TABLE livro (
    id_livro SERIAL PRIMARY KEY NOT NULL,
    codigo TEXT,
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

DROP TABLE IF EXISTS user;

CREATE TABLE user (
    id_user SERIAL PRIMARY KEY,
    nome VARCHAR(70) NOT NULL,
    ra VARCHAR(7),
    login VARCHAR(20) UNIQUE NOT NULL,
    senha TEXT NOT NULL,
    turma VARCHAR(7),
    tipo VARCHAR(15) NOT NULL,
    email VARCHAR(255),
    telefone VARCHAR(16),
    admin BOOLEAN NOT NULL DEFAULT FALSE,
    bloqueado BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO user VALUES (DEFAULT, 'Admin', null, 'admin', '5eb30e9a4d77fc4e9edd9859ff0f5782', null, 'admin', '', null, TRUE, DEFAULT);