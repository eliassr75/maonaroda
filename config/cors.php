<?php
// Configuração CORS
session_start();
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST, GET');
header("Access-Control-Allow-Headers: *");
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set("America/Sao_Paulo");
