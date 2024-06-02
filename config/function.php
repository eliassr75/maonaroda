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

function get_all($table, $total = false, $key = null, $value = null, $join = false, $join_on = false, $join_value = false) {
    $conn = conn();
    if ($conn) {
        try {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $table) ||
                ($join && !preg_match('/^[a-zA-Z0-9_]+$/', $join)) ||
                ($key && !preg_match('/^[a-zA-Z0-9_]+$/', $key)) ||
                ($join_on && !preg_match('/^[a-zA-Z0-9_]+$/', $join_on)) ||
                ($join_value && !preg_match('/^[a-zA-Z0-9_]+$/', $join_value))) {
                throw new InvalidArgumentException("Nome de tabela ou coluna inválido.");
            }

            $selectTotal = $total ? ", (SELECT COUNT(id) FROM $table) as total" : "";
            $where = ($key && $value) ? "WHERE $table.$key = :value" : "";

            $joinClause = "";
            $joinSelect = "";
            if ($join && $join_on && $join_value) {
                $joinClause = "JOIN $join ON $join.$join_on = $table.$join_value";
                $columns = $conn->query("SELECT column_name FROM information_schema.columns WHERE table_name = '$join'")->fetchAll(PDO::FETCH_COLUMN);
                foreach ($columns as $column) {
                    $joinSelect .= ", $join.$column AS ${join}_$column";
                }
            }

            $sql = "SELECT $table.* $joinSelect $selectTotal FROM $table $joinClause $where ORDER BY $table.id DESC";

            $stmt = $conn->prepare($sql);

            if ($key && $value) {
                $stmt->bindParam(':value', $value);
            }

            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $rows ? $rows : [];
        } catch (PDOException | InvalidArgumentException $e) {
            return ["error" => $e->getMessage()];
        }
    } else {
        return ["error" => "Nenhuma conexão com o banco de dados."];
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
            $stmt = $conn->prepare("SELECT * FROM campaign WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
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

function campaign($data=false, $edit=false) {

    $conn = conn();
    $name = ($data["input-campaign-name"] ?? "");
    $logo = $data["input-campaign-logo"];
    $description = ($data["input-campaign-editordata"] ?? "");
    $value = (str_replace(',', '.', $data["input-campaign-value"]) ?? 0);

    $campaign_id = $data["campaign_id"];
    $created_by = $_SESSION['id'];
    $entity_id = $_SESSION['entity_id'];

    $state = ($data["input-campaign-state"] ?? "");
    $city = ($data["input-campaign-city"] ?? "");
    $local = "$city - " . explode(" - ", $state)[1];
    $active = $data["input-campaign-active"] ? "true" : "false";

    if (empty($name)) {
        return ["error" => "Nome da campanha é obrigatório.", "code" => 500];
    }
    if (empty($description)) {
        return ["error" => "A descrição da campanha é obrigatória.", "code" => 500];
    }
    if (empty($local)) {
        return ["error" => "O local da campanha é obrigatório.", "code" => 500];
    }

    try {
        if (!$edit) {
            $stmt = $conn->prepare('INSERT INTO campaign (name, description, value, created_by, entity_id, local, active, logo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ON CONFLICT (name, entity_id, local)
            DO UPDATE SET 
               name = EXCLUDED.name,
               description = EXCLUDED.description,
               value = EXCLUDED.value,
               local = EXCLUDED.local,
               active = EXCLUDED.active,
               logo = EXCLUDED.logo,
               updated = now()
            RETURNING id;
            ');
            $stmt->execute([$name, $description, $value, $created_by, $entity_id, $local, $active, $logo]);
        }else{
            $stmt = $conn->prepare('UPDATE campaign SET name=?, description=?, value=?, local=?, active=?, logo=?, updated = now() WHERE id=? RETURNING id;');
            $stmt->execute([$name, $description, $value, $local, $active, $logo, $campaign_id]);
        }

        $last_id = $stmt->fetchColumn();

        return ["success" => true, "id" => $last_id, "code" => 200];
    } catch (PDOException $e) {
        return ["error" => "Falha ao criar/editar campanha: " . $e->getMessage(), "code" => 500];
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