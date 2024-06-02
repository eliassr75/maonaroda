<?php

require __DIR__ . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once("../../../config/cors.php");
require_once("../../../config/function.php");

/*

RewriteEngine On
RewriteCond %{SERVER_PORT} 80
RewriteRule ^(.*)$ https://www.maonaroda.etecsystems.com.br/$1 [R,L]

*/

function sendMail($para, $assunto, $msg, $url=false, $btn=false)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    if($btn){
        $btn = "
        <table class='s-4 w-full' role='presentation' border='0' cellpadding='0' cellspacing='0' style='width: 100%;' width='100%'>
            <tbody>
              <tr>
                <td style='line-height: 16px; font-size: 16px; width: 100%; height: 16px; margin: 0;' align='left' width='100%' height='16'>
                  &#160;
                </td>
              </tr>
            </tbody>
          </table>
          <table class='btn btn-primary p-3 fw-700' role='presentation' border='0' cellpadding='0' cellspacing='0' style='border-radius: 6px; border-collapse: separate !important; font-weight: 700 !important;'>
            <tbody>
              <tr>
      
                <td style='line-height: 24px; font-size: 16px; border-radius: 6px; font-weight: 700 !important; margin: 0;' align='center' bgcolor='#0d6efd'>
                  <a href='{$url}' style='color: #ffffff; font-size: 16px; font-family: Helvetica, Arial, sans-serif; text-decoration: none; border-radius: 6px; line-height: 20px; display: block; font-weight: 700 !important; white-space: nowrap; background-color: #31D2F2; padding: 12px;'>{$btn}</a>
                </td>
              </tr>
            </tbody>
          </table>
        ";
    }else{
        $btn = "";
    }

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'email-ssl.com.br';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'no-reply@maonaroda.etecsystems.com.br';                     //SMTP username
        $mail->Password   = 'Dc7yM9yajVa5ST@';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        $mail->CharSet = 'UTF-8';
        //Recipients
        $mail->setFrom('no-reply@maonaroda.etecsystems.com.br');
        //$mail->addAddress('joe@example.net');     //Add a recipient
        $mail->addAddress($para);               //Name is optional

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $assunto;
        $mail->Body    = "
        
        <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
        <html>
            <head>
            <!-- Compiled with Bootstrap Email version: 1.3.1 -->
            <meta http-equiv='x-ua-compatible' content='ie=edge'>
            <meta name='x-apple-disable-message-reformatting'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <meta name='format-detection' content='telephone=no, date=no, address=no, email=no'>
            <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
            <style type='text/css'>
            body,table,td{font-family:Helvetica,Arial,sans-serif !important}.ExternalClass{width:100%}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{line-height:150%}a{text-decoration:none}*{color:inherit}a[x-apple-data-detectors],u+#body a,#MessageViewBody a{color:inherit;text-decoration:none;font-size:inherit;font-family:inherit;font-weight:inherit;line-height:inherit}img{-ms-interpolation-mode:bicubic}table:not([class^=s-]){font-family:Helvetica,Arial,sans-serif;mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0px;border-collapse:collapse}table:not([class^=s-]) td{border-spacing:0px;border-collapse:collapse}@media screen and (max-width: 600px){.w-full,.w-full>tbody>tr>td{width:100% !important}.p-lg-10:not(table),.p-lg-10:not(.btn)>tbody>tr>td,.p-lg-10.btn td a{padding:0 !important}.p-3:not(table),.p-3:not(.btn)>tbody>tr>td,.p-3.btn td a{padding:12px !important}.p-6:not(table),.p-6:not(.btn)>tbody>tr>td,.p-6.btn td a{padding:24px !important}*[class*=s-lg-]>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}.s-4>tbody>tr>td{font-size:16px !important;line-height:16px !important;height:16px !important}.s-10>tbody>tr>td{font-size:40px !important;line-height:40px !important;height:40px !important}}
            </style>
            </head>
        <body style='outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;' bgcolor='#ffffff'>
          <table class='body' valign='top' role='presentation' border='0' cellpadding='0' cellspacing='0' style='outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;' bgcolor='#ffffff'>
            <tbody>
              <tr>
                <td valign='top' style='line-height: 24px; font-size: 16px; margin: 0;' align='left'>
                  <table class='container' role='presentation' border='0' cellpadding='0' cellspacing='0' style='width: 100%;'>
                    <tbody>
                      <tr>
                        <td align='center' style='line-height: 24px; font-size: 16px; margin: 0; padding: 0 16px;'>
                          <!--[if (gte mso 9)|(IE)]>
                            <table align='center' role='presentation'>
                              <tbody>
                                <tr>
                                  <td width='600'>
                          <![endif]-->
                          <table align='center' role='presentation' border='0' cellpadding='0' cellspacing='0' style='width: 100%; max-width: 600px; margin: 0 auto;'>
                            <tbody>
                              <tr>
                                <td style='line-height: 24px; font-size: 16px; margin: 0;' align='left'>
                                  <table class='ax-center' role='presentation' align='center' border='0' cellpadding='0' cellspacing='0' style='margin: 0 auto;'>
                                    <tbody>
                                      <tr>
                                        <td style='line-height: 24px; font-size: 16px; margin: 0;' align='left'>
                                          <div class='text-center' style='' align='center'>
                                            <table class='s-10 w-full' role='presentation' border='0' cellpadding='0' cellspacing='0' style='width: 100%;' width='100%'>
                                              <tbody>
                                                <tr>
                                                  <td style='line-height: 40px; font-size: 40px; width: 100%; height: 40px; margin: 0;' align='left' width='100%' height='40'>
                                                    &#160;
                                                  </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                            <img class='' width='50%' src='https://maonaroda.etecsystems.com.br/assets/images/logo_lg_default.png' style='height: auto; line-height: 100%; outline: none; text-decoration: none; display: block; border-style: none; border-width: 0;'>
                                            <table class='s-10 w-full' role='presentation' border='0' cellpadding='0' cellspacing='0' style='width: 100%;' width='100%'>
                                              <tbody>
                                                <tr>
                                                  <td style='line-height: 40px; font-size: 40px; width: 100%; height: 40px; margin: 0;' align='left' width='100%' height='40'>
                                                    &#160;
                                                  </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </div>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <table class='card p-6 p-lg-10 space-y-4' role='presentation' border='0' cellpadding='0' cellspacing='0' style='border-radius: 6px; border-collapse: separate !important; width: 100%; overflow: hidden; border: 1px solid #e2e8f0;' bgcolor='#ffffff'>
                                    <tbody>
                                      <tr>
                                        <td style='line-height: 24px; font-size: 16px; width: 100%; margin: 0; padding: 40px;' align='left' bgcolor='#ffffff'>
                                          <h1 class='h3 fw-700' style='padding-top: 0; padding-bottom: 0; font-weight: 700 !important; vertical-align: baseline; font-size: 28px; line-height: 33.6px; margin: 0;' align='left'>{$assunto}</h1>
                                          <table class='s-4 w-full' role='presentation' border='0' cellpadding='0' cellspacing='0' style='width: 100%;' width='100%'>
                                            <tbody>
                                              <tr>
                                                <td style='line-height: 16px; font-size: 16px; width: 100%; height: 16px; margin: 0;' align='left' width='100%' height='16'>
                                                  &#160;
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                          <div class='' style='line-height: 24px; font-size: 16px; width: 100%; margin: 0;' align='left'>{$msg}</div>
                                          {$btn}
                                          
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                          <!--[if (gte mso 9)|(IE)]>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                          <![endif]-->
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        </body>
      </html>
        
      ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
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


if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $msg = ["error" => "Nenhum parâmetro informado."];
    $code =  404;

    if (isset($_GET["query"]) && !empty($_GET["query"])){
    
        $QUERY = $_GET["query"];
        switch($QUERY){
            case 'get-entity':

                $msg = create_entity($_GET);
                if($msg['success']){
                    $_SESSION['entity_id'] = $msg['id'];
                }

                $code = $msg['code'];

                break;
            case 'get-campaigns':
                $code = 200;
                $msg["error"] = null;
                $msg["data"] = get_all("campaign");
                break;
            case 'edit-campaign':
                $code = 200;
                $msg = get_campaign_by_id($_GET["id"]);
                $msg['value'] = str_replace('.', ',', $msg['value']);
                $msg['form'] = "edit-campaign";
                $msg['success'] = true;

                break;
            case 'password-reset':

                $sub_query = $_GET['sub-query'];

                switch($sub_query){
                    case "get-email":

                        $code = 200;
                        $email = $_GET["username"];
                        $_SESSION['recovery-code'] = random_int(111111, 999999);
                        $_SESSION['recovery-email'] = $email;

                        $response_email = sendMail($email, 'Redefinição de Senha', "Seu código de verificação é: <b>{$_SESSION['recovery-code']}</b>");

                        $msg['recovery'] = true;
                        $msg['success'] = true;
                        $msg['page'] = "recovery-code";
                        $msg['msg'] = "Um código de validação foi enviado para o e-mail informado ($email)! ";
                        $msg['error'] = null;

                        break;
                    case "recovery-code":

                        $code = intval($_GET['code']);
                        $session_code = intval($_SESSION['recovery-code']);

                        if($code === $session_code){
                            $msg['page'] = "new-password";
                            $msg['msg'] = "Código validado! Agora vamos definir a nova senha.";
                            $msg['recovery'] = true;
                            $msg['success'] = true;
                            $msg['error'] = null;
                            $code = 200;
                        }else{
                            $msg['error'] = "Código inválido!";
                            $code = 500;
                        }

                        break;
                }
                break;
            case "login":
            
                $username = (isset($_GET["username"]) ? $_GET["username"] : "");
                $always_logged = (isset($_GET["always-logged"]) ? true : false);
                $password = ($_GET["password"] ?? "");
                
                if (empty($username) || empty($password)){
                    $msg["error"] = "Usuário ou senha inválidos!";
                }else{
                
                    $conn = conn();
                    
                    $stmt = $conn->prepare("SELECT * FROM users where username=? or email=?");
                    $stmt->execute([$username, $username]);
                    
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if ($rows){
                        

                        $validPass = true;
                        foreach($rows as $user => $value){

                            if(!password_verify($password, $value['password'])){
                                $validPass = false;
                                break;
                            }

                            foreach($value as $chave => $sec_value){
                                $_SESSION[$chave] = $sec_value;
                            }
                        }

                        if(!$validPass){
                            $msg["error"] = "Usuário ou senha inválidos!";
                        }else{
                            $msg = ["success" => true];
                            $code = 200;
                            $msg["msg"] = "Autenticação concluída, bem vindo(a) {$_SESSION["name"]}";
                            $hash_logged = md5(date('Y-m-d H:i:s'));

                            $msg["logged"] = $hash_logged;
                            $msg["reload"] = true;
                            $_SESSION["logged"] = $hash_logged;
                        }
                        

                        
                    }else{
                        $msg["error"] = "Usuário ou senha inválidos!";
                    }
                }
                break;
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
                
        if(isset($_SESSION["password"]) && !empty($_SESSION["password"])){
            
            $email = $_SESSION["email"];
            $user = validarEmail($email);
            if ($user) {
                $code = false;
                
                switch($_POST["query"]){
                    case 'edit-campaign':

                        if(isset($_FILES["input-campaign-logo"]["name"])) {
                            $uploadDir = "../../../assets/images/";
                            $destinationPath = $uploadDir . $_FILES["input-campaign-logo"]["name"];

                            $newFileName = md5($_FILES["input-campaign-logo"]["name"] . date('Y-m-d H:i:s'));
                            $newDestinationPath = $uploadDir . $newFileName;

                            if (move_uploaded_file($_FILES["input-campaign-logo"]["tmp_name"], $destinationPath)) {
                                rename($destinationPath, $newDestinationPath);
                                $_POST['input-campaign-logo'] = "/assets/images/{$newFileName}";
                            }
                        }else{
                            $_POST['input-campaign-logo'] = $_POST['campaign_old_image'];
                        }
                        $_POST['input-campaign-logo'] = $_POST['input-campaign-logo'] ?? $_POST['campaign_old_image'];

                        $msg = campaign($_POST, true);
                        if($msg['error']){
                            $code = 500;
                        }else{
                            $code = $msg['code'];
                            $msg['success'] = true;
                            $msg['msg'] = "Campanha modificada com sucesso!";
                            $msg['reload'] = true;
                        }

                        break;
                    case 'new-campaign':

                        if(isset($_FILES["input-campaign-logo"]["name"])) {
                            $uploadDir = "../../../assets/images/";
                            $destinationPath = $uploadDir . $_FILES["input-campaign-logo"]["name"];

                            $newFileName = md5($_FILES["input-campaign-logo"]["name"] . date('Y-m-d H:i:s'));
                            $newDestinationPath = $uploadDir . $newFileName;

                            if (move_uploaded_file($_FILES["input-campaign-logo"]["tmp_name"], $destinationPath)) {
                                rename($destinationPath, $newDestinationPath);
                                $_POST['input-campaign-logo'] = "/assets/images/{$newFileName}";
                            }
                        }else{
                            $_POST['input-campaign-logo'] = $_POST['campaign_old_image'];
                        }

                        $msg = campaign($_POST);
                        if($msg['error']){
                            $code = 500;
                        }else{
                            $code = $msg['code'];
                            $msg['success'] = true;
                            $msg['msg'] = "Campanha criada com sucesso!";
                            $msg['reload'] = true;
                        }

                        break;
                    case "summernote_upload_image":

                        $uploadDir = "../../../assets/images/";
                        $destinationPath = $uploadDir . $_FILES["file"]["name"];

                        $newFileName = md5($_FILES["file"]["name"] . date('Y-m-d H:i:s'));
                        $newDestinationPath = $uploadDir . $newFileName;

                        if(move_uploaded_file($_FILES["file"]["tmp_name"], $destinationPath)){
                            rename($destinationPath, $newDestinationPath);
                        }

                        exit("/assets/images/{$newFileName}");
                        break;
                    default:
                        break;
                }
            
            }else{
                $msg["error"] = "O token informado é inválido!";
            }
            
        }else{
            switch($_POST["query"]){
                case 'password-reset':

                    $sub_query = $_POST["sub-query"];

                    if ($sub_query == "new-password") {
                        try {

                            $code = 200;
                            $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
                            $email = $_SESSION['recovery-email'];

                            $conn = conn();
                            $stmt = $conn->prepare("UPDATE users SET password = ? where email = ?");
                            $stmt->execute([$password, $email]);

                            $stmt = $conn->prepare("SELECT * FROM users where email=?");
                            $stmt->execute([$email]);
                            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if ($rows) {

                                $msg = ["success" => true];

                                foreach ($rows as $user => $value) {
                                    foreach ($value as $chave => $sec_value) {
                                        $_SESSION[$chave] = $sec_value;
                                    }
                                }

                                $msg["msg"] = "Senha redefinida com sucesso! Bem vindo(a) {$_SESSION["name"]}";
                                $hash_logged = md5(date('Y-m-d H:i:s'));

                                $msg["reload"] = true;
                                $msg["logged"] = $hash_logged;
                                $_SESSION["logged"] = $hash_logged;

                            }
                        } catch (Exception $e) {
                            $code = 500;
                            $msg['error'] = "Não foi possível redefinir a senha ({$e->getMessage()})";
                        }
                    }
                    break;
                case "new-login":

                    $name = $_POST['name'];
                    $email = $_POST['username'];
                    $username = explode("@", $_POST['username'])[0];
                    $password = password_hash($_POST['password'],PASSWORD_BCRYPT);
                    $entity_id = ($_SESSION['entity_id'] ?? $_POST['entity_id']);

                    $data = [
                        "name" => $name,
                        "email" => $email,
                        "password" => $password,
                        "username" => $username,
                        "entity_id" => $entity_id
                    ];

                    $r = create_user($data);

                    if($r['error']){
                        $code = 500;
                        $msg['error'] = $r['error'];
                    }else{

                        $conn = conn();
                        $stmt = $conn->prepare("SELECT * FROM users where email=?");
                        $stmt->execute([$email]);
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($rows){

                            $msg = ["success" => true];

                            foreach($rows as $user => $value){
                                foreach($value as $chave => $sec_value){
                                    $_SESSION[$chave] = $sec_value;
                                }
                            }

                            $code = 200;
                            $msg["msg"] = "Conta criada com sucesso! Bem vindo(a) {$_SESSION["name"]}";
                            $hash_logged = md5(date('Y-m-d H:i:s'));

                            $msg["logged"] = $hash_logged;
                            $msg["reload"] = true;
                            $_SESSION["logged"] = $hash_logged;

                        }
                    }

                    break;
                default:
                    $msg = ["error" => "Requisição não permitida."];
                    break;
            }
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
