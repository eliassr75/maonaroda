<?php

require_once("../../../config/cors.php");
require_once("../../../config/function.php");
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use MercadoPago\Client\Payment\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

use Detection\MobileDetect;

function sendPaymentRequest($requestData, $method) {

    $client = new Client();
    
    /*
    
    $requestData = [
        'credentials' => [
            'login' => $this->login,
            'password' => $this->password,
            'token' => $this->token,
        ],
        'method' => '--',
        'values' => [
            'id' => $bill,
        ],
    ];
    
    
    */
    
    switch ($method) {
    
        case "GET":
            $response = $client->get($this->apiUrl, [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($requestData),
            ]);
            break;
        case "POST":
            $response = $client->post($this->apiUrl, [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($requestData),
            ]);
            break;
        case "PUT":
            $response = $client->put($this->apiUrl, [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($requestData),
            ]);
            break;
        case "DELETE":
            $response = $client->delete($this->apiUrl, [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($requestData),
            ]);
            break;
    }

    return json_decode($response->getBody(), true);
}

function auto_decimal_format($n, $def = 2) {
    
    $a = explode(".", $n);
    if (count($a)>1) {
        $b = str_split($a[1]);
        $pos = 1;
        foreach ($b as $value) {
            if ($value != 0 && $pos >= $def) {
                $c = number_format($n, $pos);
                $c_len = strlen(substr(strrchr($c, "."), 1));
                if ($c_len > $def) { return rtrim($c, 0); }
                return $c; // or break
            }
            $pos++;
        }
    }
    return number_format($n, $def);
}

function amount_history($wallets){
    try {
    
        // Conexão com o banco de dados
        $pdo = conn();
        
        // Preparar e executar a consulta SQL
        $stmt = $pdo->prepare('
        SELECT u.name AS user_name,
            d.amount AS deposit_amount,
            d.deposit_date
        FROM wallets_contributor wc
        JOIN wallets w ON wc.wallets_id = w.id
        JOIN users u ON wc.user_id = u.id
        LEFT JOIN deposits d ON w.id = d.wallets_id
        WHERE wc.wallets_id = :wallets_id and d.amount is not null order by d.deposit_date desc
        ');
        
        $stmt->bindParam(':wallets_id', $wallets["wallets_id"], PDO::PARAM_STR);
        $stmt->execute();

        // Verificar se o token existe
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($row) {
            foreach($row as $values) {
                $values["deposit_amount"] = formatarDinheiro($values["deposit_amount"]);
                $values["deposit_date"] = date("d/m/Y H:i", strtotime($values["deposit_date"]));
                $data[] = $values;
            }
            
            return $data;
        }else{
            return [];
        }
    
    } catch (PDOException $e) {
        return ["error" => $e->getMessage()];
    }
}

function dashboard($user) {

    try {
        $pdo = conn();
        $stmt = $pdo->prepare('
            SELECT wc.user_id, 
                   wc.wallets_id, 
                   w.name, 
                   w.value_total,
                   w.value_deposit,
                   w.value_remaining,
                   w.created_by,
                   w.updated,
                   w.created
            FROM wallets_contributor wc
            JOIN wallets w ON wc.wallets_id = w.id
            WHERE wc.user_id = :user_id
        ');
        
        $stmt->bindParam(':user_id', $user["id"], PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($row) {
            foreach($row as $values) {
            
                $value_deposit =  $values["value_deposit"] ? $values["value_deposit"] : 0;
                
                $values["percent_progress"] = 0;
                if($values["value_total"] > 0){
                    $values["percent_progress"] = number_format((float)(100 - ($values["value_total"] - $value_deposit) * 100 / $values["value_total"]), 2, ',', '.');
                }   
            
                $values["created"] = date("d/m/Y H:i", strtotime($values["created"]));
                $values["updated"] = $values["updated"] ? date("d/m/Y H:i", strtotime($values["updated"])) : "--";
                
                $values["value_total"] = formatarDinheiro($values["value_total"]);
                $values["value_deposit"] = formatarDinheiro($values["value_deposit"]);
                $values["value_remaining"] = formatarDinheiro($values["value_remaining"]);
                
                $values["amounts"] = amount_history($values);
                
                $results[] = $values;
            }
            
            return $results;
        } else {
            return ["warning" => "Nada para exibir"];
        }
    } catch (PDOException $e) {
        echo "Erro na conexão: " . $e->getMessage();
        return false;
    }
}

function criarWallet($name, $user_id, $type, $value_total, $category) {

    if (isset($name) && isset($user_id) && isset($type) && isset($value_total)) {

        try {
        
            $value_total = str_replace(',', '.', str_replace('.' , '', $value_total));
            
            $conn = conn();
            
            $sql = "INSERT INTO wallets (name, created_by, type, value_total, category) VALUES (:name, :created_by, :type, :value_total, :category)";
            $stmt = $conn->prepare($sql);
            
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':created_by', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
            $stmt->bindParam(':value_total', $value_total, PDO::PARAM_STR);
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
            
            $stmt->execute();
    
            $conn = null;
    
            return ["success" => "Collab criada com sucesso!"];
        
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    } else {
        return ["error" => "Todos os campos são obrigatórios!"];
    }

}

function editarWallet($id, $name, $user_id, $type, $value_total, $category) {

    if (isset($id) && isset($name) && isset($user_id) && isset($type) && isset($value_total)) {

        try {
        
            $value_total = str_replace(',', '.', str_replace('.' , '', $value_total));
            
            $conn = conn();
            
            $sql = "UPDATE wallets set name=:name, type=:type, value_total=:value_total, category=:category where id=:id and created_by=:created_by";
            $stmt = $conn->prepare($sql);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':created_by', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
            $stmt->bindParam(':value_total', $value_total, PDO::PARAM_STR);
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
            
            $stmt->execute();
    
            $conn = null;
    
            return ["success" => "Collab editada com sucesso!"];
        
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    } else {
        return ["error" => "Todos os campos são obrigatórios!"];
    }

}

function inserirDeposito($wallets_id, $user_id, $amount) {

    if (isset($wallets_id) && isset($user_id) && isset($amount)) {

        try {
            $conn = conn();
            $sql = "INSERT INTO deposits (wallet_id, user_id, amount) VALUES (:wallet_id, :user_id, :amount)";
            $stmt = $conn->prepare($sql);
            $stmt->execute(array(':wallet_id' => $wallets_id, ':user_id' => $user_id, ':amount' => $amount));
    
            $conn = null;
    
            return ["success" => "Depósito inserido com sucesso!"];
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    } else {
        return ["error" => "Todos os campos são obrigatórios!"];
    }
}


if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $msg = ["error" => "Nenhum parâmetro informado."];
    $code =  404;

    if (isset($_GET["query"]) && !empty($_GET["query"])){
    
        $QUERY = $_GET["query"];
        switch($QUERY){
            case 'get-campaigns':
                $code = 200;
                $msg["error"] = null;
                $msg["data"] = get_all("campaign");
                break;
            case 'edit-collab':
                $code = 200;

                $msg = get_collab($_GET["id"]);
                break;
            case "login":
            
                $username = (isset($_GET["username"]) ? $_GET["username"] : "");
                $always_logged = (isset($_GET["always-logged"]) ? true : false);
                $password = (isset($_GET["password"]) ? md5($_GET["password"]) : "");
                
                if (empty($username) || empty($password)){
                    $msg["error"] = "Usuário ou senha inválidos!";
                }else{
                
                    $conn = conn();
                    
                    $stmt = $conn->prepare("SELECT * FROM users where (username=? or email=?) and password=?");
                    $stmt->execute([$username, $username, $password]);
                    
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if ($rows){
                        
                        $msg = ["success" => true];
                    
                        foreach($rows as $user => $value){
                            foreach($value as $chave => $sec_value){
                                $_SESSION[$chave] = $sec_value;
                            }
                        }
                        
                        $code = 200;
                        $msg["msg"] = "Autenticação concluída, bem vindo(a) {$_SESSION["name"]}";
                        $hash_logged = md5(date('Y-m-d H:i:s'));
                        
                        $msg["logged"] = $hash_logged;
                        $_SESSION["logged"] = $hash_logged;
                                            
                        if ($always_logged){
                            $msg["persist_login"] = md5("{$_SERVER['HTTP_USER_AGENT']} {$_SESSION["token"]}");
                        }
                        
                    }else{
                        $msg["error"] = "Usuário ou senha inválidos!";
                    }
                }
                break;
            default:
                
                if(isset($_GET["token"]) && !empty($_GET["token"])){
                    
                    $token = $_GET["token"];
                    $user = validarToken($token);
                    if ($user) {
                    
                        switch($QUERY){
                            case "get_info_dashboard":
                            
                                $msg = ["results" => dashboard($user)];
                                $code = 200;
                            
                                break;
                        }
                    
                    }else{
                        $msg["error"] = "O token informado é inválido!";
                    }
                    
                }else{
                    $msg["error"] = "O token é obrigatório!";
                }
                
                break;
        }
        
    }
    
    http_response_code($code);
    echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
    
    
}
elseif ($_SERVER["REQUEST_METHOD"] === "POST") {

    $msg = ["error" => "Nenhum parâmetro informado."];
    $code = 404;

    if (isset($_POST["query"]) && !empty($_POST["query"])){
                
        if(isset($_SESSION["token"]) && !empty($_SESSION["token"])){
            
            $token = $_SESSION["token"];
            $user = validarToken($token);
            if ($user) {
                $code = false;
                
                switch($_POST["query"]){
                    case "create-collab":
                        break;
                    default:
                        break;
                }
            
            }else{
                $msg["error"] = "O token informado é inválido!";
            }
            
        }else{
            $msg["error"] = "O token é obrigatório!";
        }
        
    }
    
    if($code){
        http_response_code($code);
    }
    echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} elseif ($_SERVER["REQUEST_METHOD"] === "PUT") {
    echo json_encode(["message" => "Requisição PUT para o Endpoint 1"], JSON_UNESCAPED_UNICODE);
    
}else {
    http_response_code(405);
    echo json_encode(["error" => "Método não permitido"], JSON_UNESCAPED_UNICODE);
}
