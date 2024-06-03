<?php
$parsed = parse_ini_file('settings.env', true);

// Configuração do banco de dados
define("DB_HOST", $parsed['DB_HOST']);
define("DB_USER", $parsed['DB_USER']);
define("DB_PASSWORD", $parsed['DB_PASSWORD']);
define("DB_NAME", $parsed['DB_NAME']);

define("MAIL_HOST", $parsed['MAIL_HOST']);
define("MAIL_USERNAME", $parsed['MAIL_USERNAME']);
define("MAIL_PASSWORD", $parsed['MAIL_PASSWORD']);
define("MAIL_PORT", $parsed['MAIL_PORT']);
