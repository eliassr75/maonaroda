<?php
$parsed = parse_ini_file('settings.env', true);

// Configuração do banco de dados
define("DB_HOST", $parsed['DB_HOST']);
define("DB_USER", $parsed['DB_USER']);
define("DB_PASSWORD", $parsed['DB_PASSWORD']);
define("DB_NAME", $parsed['DB_NAME']);
