<?php

function formatarDinheiro(float $valor): string {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function truncateString($string, $length) {
    if (strlen($string) > $length) {
        $string = substr($string, 0, $length) . "..."; // Adiciona reticências para indicar que a string foi truncada
    }
    return $string;
}

function conn(){

    require_once("database.php");
    
    try {
        $pdo = new PDO("pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";options='--client_encoding=UTF8'", DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        return false;
    }
 
}

function get_all($table, $total=false, $key=null, $value=null) {
    $conn = conn();
    if ($conn) {
        try {

            if ($total) {
                $total = ", (SELECT COUNT(id) from $table) as total";
            }else{
                $total=null;
            }

            $stmt = $conn->prepare("SELECT $table.* $total FROM $table ORDER BY id DESC");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($rows){
                return $rows;
            }else{
                return [];
            }
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    } else {
        return "Nenhuma campanha encontrada.";
    }
}

function get_by_id($table, $id) {
    $conn = conn();

    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT * FROM $table where id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $rows = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rows){
                return $rows;
            }else{
                return [];
            }
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }else{
        return "Não foi possível obter essa informação";
    }
}

function get_campaign_by_id($id) {
    $conn = conn();

    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT campaign.*, entity.* FROM campaigns JOIN entity ON entity.id = campaign.entity_id WHERE campaign.id = :id ORDER BY campaign.id DESC");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $rows = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rows) {
                return $rows;
            } else {
                return [];
            }
        } catch(PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    } else {
        return "Nenhuma campanha encontrada.";
    }
}

function create_campaign($data) {
    $conn = conn();
    
    if ($conn) {
        $name = (isset($data["name"]) ? $data["name"] : "");
        $description = (isset($data["description"]) ? $data["description"] : "");
        $value = (isset($data["value"]) ? $data["value"] : "");
        $created_by = (isset($data["created_by"]) ? $data["created_by"] : "");
        $entity_id = (isset($data["entity_id"]) ? $data["entity_id"] : "");
        $local = (isset($data["local"]) ? $data["local"] : "");
        $active = (isset($data["active"]) ? $data["active"] : "");

        if (empty($name)) {
            return ["error" => "Nome da campanha é obrigatório."];
        }
        if (empty($description)) {
            return ["error" => "A descrição da campanha é obrigatória."];
        }
        if (empty($value)) {
            return ["error" => "O valor da campanha é obrigatório."];
        }
        if (empty($local)) {
            return ["error" => "O local da campanha é obrigatório."];
        }

        try {
            $stmt = $conn->prepare("INSERT INTO campaign (name, description, value, created_by, entity_id, local, active) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $value, $created_by, $entity_id, $local, $active]);
            
            $last_id = $conn->lastInsertId();
            
            return ["success" => true, "id" => $last_id];
        } catch (PDOException $e) {
            return ["error" => "Falha ao criar campanha: " . $e->getMessage()];
        }
    } else {
        return ["error" => "Falha ao conectar com o banco."];
    }
}

function create_entity($data) {

    $conn = conn();
    $name = ($data["name"] ?? null);
    $cnpj = ($data["cnpj"] ?? null);
    $active = ($data["active"] ?? true);
    $photo = ($data["photo"] ?? null);
    $phone_number = ($data["phone_number"] ?? null);
    $postcode = ($data["postcode"] ?? null);
    $state = ($data["state"] ?? null);
    $city = ($data["city"] ?? null);

    try {
        $stmt = $conn->prepare("
        INSERT INTO entity (name, cnpj, active, photo, phone_number, postcode, state, city)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON CONFLICT (cnpj)
        DO UPDATE SET
            name = EXCLUDED.name,
            active = EXCLUDED.active,
            photo = EXCLUDED.photo,
            phone_number = EXCLUDED.phone_number,
            postcode = EXCLUDED.postcode,
            state = EXCLUDED.state,
            city = EXCLUDED.city,
            updated = now()
        RETURNING id;
        ");
        $stmt->execute([$name, $cnpj, $active, $photo, $phone_number, $postcode, $state, $city]);
        $last_id = $stmt->fetchColumn();

        return ["success" => true, "code" => 200, "id" => $last_id];
    } catch (PDOException $e) {
        return ["error" => "Falha ao criar entidade: " . $e->getMessage(), "code" => 500];
    }
}

function create_user($data) {
    $conn = conn();

    $name = ($data["name"] ?? null);
    $email = ($data["email"] ?? null);
    $username = ($data["username"] ?? null);
    $password = ($data["password"] ?? null);
    $entity_id = ($data["entity_id"] ?? null);
    $city = ($data["city"] ?? null);
    $state = ($data["state"] ?? null);
    $country = ($data["country"] ?? null);
    $postcode = ($data["postcode"] ?? null);
    $gender = ($data["gender"] ?? null);
    $phone = ($data["phone"] ?? null);
    $ddi_phone = ($data["ddi_phone"] ?? null);
    $country_phone = ($data["country_phone"] ?? null);

    try {
        $stmt = $conn->prepare("INSERT INTO users (name, email, username, password, entity_id, city, state, country, postcode,
                                                    gender, phone, ddi_phone, country_phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $username, $password, $entity_id, $city, $state, $country, $postcode, $gender, $phone, $ddi_phone, $country_phone]);

        $last_id = $conn->lastInsertId();

        return ["success" => true, "id" => $last_id];
    } catch(PDOException $e) {
        return ["error" => "Falha ao criar usuário: " . $e->getMessage()];
    }

}
function validarEmail($email) {

    try {
        // Conexão com o banco de dados
        $pdo = conn();
        // Preparar e executar a consulta SQL
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Verificar se o token existe
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Token encontrado, retornar o ID do usuário
            return $row;
        } else {
            // Token não encontrado, retornar falso
            session_destroy();
            return false;
        }
    } catch (PDOException $e) {
        return ["error" => $e->getMessage()];
    }
}