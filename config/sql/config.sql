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