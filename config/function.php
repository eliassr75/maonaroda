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

function get_all() {
    $conn = conn();
    // if ($conn) {
    //     try {
            $stmt = $conn->prepare("SELECT * FROM campaign ORDER BY id DESC");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($rows){
                return $rows;
            }else{
                return [];
            }
    //     } catch (PDOException $e) {
    //         return ["error" => $e->getMessage()];
    //     }
    // } else {
    //     return "Nenhuma campanha encontrada.";
    // }
}

function counters_dashboard(){
    
    $values = [
        "users" => [
            "total" => 0,        
            "percent" => 0,
            "icon" => "up"
        ],
        "public_collabs" => [
            "total" => 0,        
            "percent" => 0,
            "icon" => "up"
        ],
        "private_collabs" => [
            "total" => 0,        
            "percent" => 0,
            "icon" => "up"
        ],
        "payments" => [
            "total" => 0,        
            "percent" => 0,
            "icon" => "up"
        ],
        "rows" => []
    ];
    
    if($conn = conn()){
    
        $current_end_date = date("Y-m-d");
        $current_init_date = date("Y-m-d", strtotime("-6 days", strtotime($current_end_date)));
        
        $previus_init_date = date("Y-m-d", strtotime("-1 days", strtotime($current_init_date)));
        $previus_end_date = date("Y-m-d", strtotime("-6 days", strtotime($previus_init_date)));
        
        
        $query = "
        
        WITH current_public AS (
            SELECT
                COUNT(DISTINCT w.id) AS total_wallets
            FROM
                wallets w
            WHERE
                w.type = 0 and w.created BETWEEN '$current_init_date 00:00:00.000000' AND '$current_end_date 23:59:59.999999'
        ),
        previous_public AS (
            SELECT
                COUNT(DISTINCT w.id) AS total_wallets
            FROM
                wallets w
            WHERE
                w.type = 0 and w.created BETWEEN '$previus_init_date 00:00:00.000000' AND '$previus_end_date 23:59:59.999999'
        ),
        current_private AS (
            SELECT
                COUNT(DISTINCT w.id) AS total_wallets
            FROM
                wallets w
            WHERE
                w.type = 1 and w.created BETWEEN '$current_init_date 00:00:00.000000' AND '$current_end_date 23:59:59.999999'
        ),
        previous_private AS (
            SELECT
                COUNT(DISTINCT w.id) AS total_wallets
            FROM
                wallets w
            WHERE
                w.type = 1 and w.created BETWEEN '$previus_init_date 00:00:00.000000' AND '$previus_end_date 23:59:59.999999'
        ),
        current_users AS (
            SELECT COUNT(id) AS total_users
            FROM users
            WHERE created BETWEEN '$current_init_date 00:00:00.000000' AND '$current_end_date 23:59:59.999999'
        ),
        previous_users AS (
            SELECT COUNT(id) AS total_users
            FROM users
            WHERE created BETWEEN '$previus_init_date 00:00:00.000000' AND '$previus_end_date 23:59:59.999999'
        ),
        current_deposits AS (
            SELECT COUNT(id) AS total_deposits
            FROM deposits
            WHERE deposit_date BETWEEN '$current_init_date 00:00:00.000000' AND '$current_end_date 23:59:59.999999'
        ),
        previous_deposits AS (
            SELECT COUNT(id) AS total_deposits
            FROM deposits
            WHERE deposit_date BETWEEN '$previus_init_date 00:00:00.000000' AND '$previus_end_date 23:59:59.999999'
        )
        SELECT
            current_public.total_wallets AS current_total_public_wallets,
            previous_public.total_wallets AS previous_total_public_wallets,
            current_private.total_wallets AS current_total_private_wallets,
            previous_private.total_wallets AS previous_total_private_wallets,
            current_users.total_users AS current_total_users,
            previous_users.total_users AS previous_total_users,
            current_deposits.total_deposits AS current_period_total_deposits,
            previous_deposits.total_deposits AS previous_period_total_deposits
        FROM
            current_public
        CROSS JOIN
            previous_public
        CROSS JOIN
            current_private
        CROSS JOIN
            previous_private
        CROSS JOIN
            current_users
        CROSS JOIN
            previous_users
        CROSS JOIN
            current_deposits
        CROSS JOIN
            previous_deposits;
        
        ";
        
        try {
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($rows){
                foreach ($rows as $row){
                
                    $values["rows"] = $row;
                    /* 
                    $values = [
                        "users" => [
                            "total" => 0,        
                            "percent" => 0        
                        ],
                        "public_collabs" => [
                            "total" => 0,        
                            "percent" => 0        
                        ],
                        "private_collabs" => [
                            "total" => 0,        
                            "percent" => 0        
                        ],
                        "payments" => [
                            "total" => 0,        
                            "percent" => 0        
                        ],
                    ];            
                    */
                    
                    // $row["current_total_wallets"];
                    // $row["previous_total_wallets"];
                    
                    $values["public_collabs"]["total"] = $row["current_total_public_wallets"];
                    $change = $row["current_total_public_wallets"] - $row["previous_total_public_wallets"];
                    
                    if ($row["previous_total_public_wallets"] != 0) {
                        $percent_change = abs($change) / $row["previous_total_public_wallets"] * 100;
                        $values["public_collabs"]["percent"] = round($percent_change, 2); // Arredonda para 2 casas decimais
                    } else {
                        $values["public_collabs"]["percent"] = 0; // Caso o total anterior seja zero para evitar divisão por zero
                    }
                    
                    if ($change > 0) {
                        $values["public_collabs"]["icon"] = "up"; // Indica aumento
                    } elseif ($change < 0) {
                        $values["public_collabs"]["icon"] = "down"; // Indica redução
                    } else {
                        $values["public_collabs"]["icon"] = "up"; // Indica que não houve mudança
                    }
                    
                    // $values["private_collabs"]["total"] = $row["current_total_private_wallets"];
                    // if ($row["current_total_private_wallets"] >= $row["previous_total_private_wallets"]){
                    //     $values["private_collabs"]["percent"] = floor(($row["current_total_private_wallets"]-$row["previous_total_private_wallets"])/$row["previous_total_private_wallets"])*100;
                    // }else{
                    //     $values["private_collabs"]["percent"] = floor(($row["previous_total_private_wallets"]-$row["current_total_private_wallets"])*$row["previous_total_private_wallets"])/100;
                    //     $values["private_collabs"]["icon"] = "down";
                    // }
                    
                    // $values["users"]["total"] = $row["current_total_users"];
                    // if ($row["current_total_users"] >= $row["previous_total_users"]){
                    //     $values["users"]["percent"] = floor(($row["current_total_users"]-$row["previous_total_users"])/$row["previous_total_users"])*100;
                    // }else{
                    //     $values["users"]["percent"] = floor(($row["previous_total_users"]-$row["current_total_users"])*$row["previous_total_users"])/100;
                    //     $values["users"]["icon"] = "down";
                    // }
 
                    // $values["payments"]["total"] = $row["current_period_total_deposits"];
                    // if ($row["current_period_total_deposits"] >= $row["previous_period_total_deposits"]){
                    //     $values["payments"]["percent"] = floor(($row["current_period_total_deposits"]-$row["previous_period_total_deposits"])/$row["previous_period_total_deposits"])*100;
                    // }else{
                    //     $values["payments"]["percent"] = floor(($row["previous_period_total_deposits"]-$row["current_period_total_deposits"])*$row["previous_period_total_deposits"])/100;
                    //     $values["payments"]["icon"] = "down";
                    // }
                }
                
                return $values;
            }else{
                return $values;
            }
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }

    }else{
    
        return $values;
    
    }
    
}

function get_contributors($collab){

    if ($collab){
    
        $conn = conn();
        
        if ($conn){
    
            try {
                $stmt = $conn->prepare("SELECT wallets_contributor.*, users.* FROM wallets_contributor join users ON users.id = wallets_contributor.user_id  where wallets_contributor.wallets_id = :wallets_id order by wallets_contributor.id desc");
                $stmt->bindParam(':wallets_id', $collab["id"], PDO::PARAM_STR);
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
        
        }else{
            return "Nenhum usuário vinculado a esta collab!";    
        }
    
    }
    
    return "Requisição inválida!";
    
}

function get_collabs($user_id=false){
    
    $user_id = ((isset($_SESSION["id"]) && !empty($_SESSION["id"])) ? intval($_SESSION["id"]) : $user_id );
    $conn = conn();
    
    if ($conn && $user_id){
    
        try {
            $stmt = $conn->prepare("SELECT wallets.*, (SELECT COUNT(id) from deposits where wallets_id = wallets.id) as have_amounts FROM wallets where created_by = :created_by order by id desc");
            $stmt->bindParam(':created_by', $user_id, PDO::PARAM_STR);
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
    
    }else{
        return ["msg" => "Nenhuma Collab Cadastrada!"];    
    }
    
}

function get_collab($collab_id){
    
    if ($collab_id){
    
        try {
        
            $conn = conn();
            $stmt = $conn->prepare("SELECT * FROM wallets where id = :id");
            $stmt->bindParam(':id', $collab_id, PDO::PARAM_STR);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($rows){
                $rows[0]["category"] = json_decode($rows[0]["category"]);
                $rows = $rows[0];
                $rows["success"] = true;
                $rows["form"] = "edit-collab";
                return $rows;
            }else{
                return [];
            }
            
        } catch (PDOException $e) {
            return ["error" => "Não foi possível encontrar os dados da collab solicitada."];
        }
    
    }else{
        return ["error" => "O ID da Collab é obrigatório!"];    
    }
    
}

function validarToken($token) {

    try {
        // Conexão com o banco de dados
        $pdo = conn();
        // Preparar e executar a consulta SQL
        $stmt = $pdo->prepare('SELECT id FROM users WHERE token = :token');
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
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