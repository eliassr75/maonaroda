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
    document TEXT NULL,
    token TEXT NOT NULL DEFAULT uuid_generate_v4(),
    created TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- Tabela de carteiras
CREATE TABLE wallets (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    value_total FLOAT NOT NULL,
    value_deposit FLOAT DEFAULT 0 NOT NULL,
    value_remaining FLOAT DEFAULT 0 NOT NULL,
    created TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated TIMESTAMPTZ NULL,
    created_by INT NOT NULL,
    type INT DEFAULT 0 NOT NULL,
    category TEXT,
    active BOOLEAN DEFAULT true,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Tabela de contribuidores
CREATE TABLE wallets_contributor (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    wallets_id INT NOT NULL,
    created TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (wallets_id) REFERENCES wallets(id)
);

CREATE TABLE deposits (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    wallets_id INT NOT NULL,
    amount FLOAT NOT NULL, -- Valor do depósito
    deposit_date TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP NOT NULL, -- Data e hora do depósito
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (wallets_id) REFERENCES wallets(id)
);

CREATE INDEX idx_email ON users (email);
CREATE INDEX idx_user_id ON users (id);
CREATE INDEX idx_username ON users (username);

CREATE INDEX idx_created_by ON wallets (created_by);
CREATE INDEX idx_created_id ON wallets (id);

CREATE INDEX idx_wallets_contributor_user_id ON wallets_contributor (user_id);
CREATE INDEX idx_wallets_contributor_id ON wallets_contributor (id);
CREATE INDEX idx_wallets_contributor_wallets_id ON wallets_contributor (wallets_id);

CREATE INDEX idx_deposits_wallets_id ON deposits (wallets_id);
CREATE INDEX idx_deposits_user_id ON deposits (user_id);
CREATE INDEX idx_deposits_id ON deposits (id);


CREATE OR REPLACE FUNCTION atualizar_valor_total_wallet()
RETURNS TRIGGER AS $$
BEGIN
    -- Atualizar o valor_total na tabela wallets para o wallets_id da tabela deposits
    UPDATE wallets
    SET value_deposit = (SELECT COALESCE(SUM(amount), 0) FROM deposits WHERE wallets_id = NEW.wallets_id),
    value_remaining = (value_total - (SELECT COALESCE(SUM(amount), 0) FROM deposits WHERE wallets_id = NEW.wallets_id)),
    updated = NOW()
    WHERE id = NEW.wallets_id;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_atualizar_valor_total
AFTER INSERT OR UPDATE OR DELETE ON deposits
FOR EACH ROW
EXECUTE FUNCTION atualizar_valor_total_wallet();


CREATE OR REPLACE FUNCTION inserir_wallets_contributor()
RETURNS TRIGGER AS $$
BEGIN
    -- Inserir na tabela wallets_contributor com o ID do usuário que criou a carteira
    INSERT INTO wallets_contributor (user_id, wallets_id)
    VALUES (NEW.created_by, NEW.id);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_inserir_wallets_contributor
AFTER INSERT ON wallets
FOR EACH ROW
EXECUTE FUNCTION inserir_wallets_contributor();


CREATE OR REPLACE FUNCTION set_default_value_remaining()
RETURNS TRIGGER AS $$
BEGIN
    NEW.value_remaining := NEW.value_total;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_set_default_value_remaining
BEFORE INSERT ON wallets
FOR EACH ROW
EXECUTE FUNCTION set_default_value_remaining();


INSERT INTO users (name, email, username, password, city, state, country, postcode, gender, phone, ddi_phone, country_phone, document) VALUES ('Elias Craveiro', 'elias.craveiro@animabook.net', 'elias.craveiro', 'b24331b1a138cde62aa1f679164fc62f', 'Gramado', 'RS', 'Brasil', '95670-022', 'male', '54993276132', '55', 'br', '03934051251');
