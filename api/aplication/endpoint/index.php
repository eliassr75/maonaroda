<?php
require_once("../../../config/cors.php");
require_once("../../../config/function.php");
require __DIR__ . '/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $msg = ["error" => "Nenhum parâmetro informado."];
    $code =  404;

    if (isset($_GET["query"]) && !empty($_GET["query"])){
    
        $QUERY = $_GET["query"];
        switch($QUERY){
            case "check-logged":
            
                validarEmail($_SESSION['email']);
                
                $code = 200;
                $hash = ((isset($_GET["hash"]) && !empty($_GET["hash"])) ? $_GET["hash"] : false);
                
                
                if ($hash && (isset($_SESSION["logged"]) && !empty($_SESSION["logged"]))){
                    if($hash == $_SESSION["logged"]){
                        $msg["logged"] = true;
                    }
                }else{
                    $msg["logged"] = false;
                }
                
                break;
            case 'end-session':
                session_destroy();
                $code = 200;
                $msg['end_session'] = true;
                
            default:
                
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
            
            switch($QUERY){
                case "":
                
                    
                break;
            }
        
        }else{
            $msg["error"] = "O token informado é inválido!";
        }
    
    
    if($code){
        http_response_code($code);
    }
    echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} 

elseif ($_SERVER["REQUEST_METHOD"] === "PUT") {
    // Lógica para manipulação de requisições PUT
    echo json_encode(["message" => "Requisição PUT para o Endpoint 1"], JSON_UNESCAPED_UNICODE);
    
    
    
}else {
    http_response_code(405);
    echo json_encode(["error" => "Método não permitido"], JSON_UNESCAPED_UNICODE);
}
