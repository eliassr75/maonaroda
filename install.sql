CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Tabela de usuários
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    username TEXT NOT NULL,
    password TEXT NOT NULL,
    city TEXT NULL,
    state TEXT NULL,
    country TEXT NULL,
    postcode TEXT NULL,
    gender TEXT NOT NULL,
    phone TEXT NULL,
    ddi_phone TEXT NULL,
    country_phone TEXT NULL,
    token TEXT NOT NULL DEFAULT uuid_generate_v4(),
    created TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE entity (
    id SERIAL primary key,
    name text NOT NULL,
    document text NOT NULL,
    created TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated TIMESTAMPTZ NULL,
    photo text NOT NULL,
    active BOOLEAN DEFAULT true,
)

-- Tabela de carteiras
CREATE TABLE campaign (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    value FLOAT NOT NULL,
    created TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated TIMESTAMPTZ NULL,
    created_by INT NOT NULL,
    entity_id INT NOT NULL,
    description TEXT NOT NULL,
    local text NOT NULL,
    active BOOLEAN DEFAULT true,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (entity_id) REFERENCES entity(id)
);

INSERT INTO users (name, email, username, password, city, state, country, postcode, gender, phone, ddi_phone, country_phone) VALUES ('Elias Craveiro', 'elias.craveiro@animabook.net', 'elias.craveiro', 'b24331b1a138cde62aa1f679164fc62f', 'Gramado', 'RS', 'Brasil', '95670-022', 'male', '54993276132', '55', 'br');
