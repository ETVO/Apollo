DROP TABLE IF EXISTS caixa;

CREATE TABLE caixa (
    id SERIAL PRIMARY KEY NOT NULL,
    valor NUMERIC(5,2) NOT NULL,
    descricao TEXT NOT NULL,
    tipo CHAR NOT NULL,
    excluido BOOLEAN NOT NULL DEFAULT FALSE
);

/* tipo -> 'e' || 's' */

SELECT * FROM caixa;