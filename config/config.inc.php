<?php

date_default_timezone_set('America/Sao_Paulo');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

#Database
if(!$_SERVER['SERVER_ADDR'] === '127.0.0.1') {
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_HOST', 'localhost');
    define('DB_NAME', '');
    define('DB_PORT', '3306');
} else {
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_HOST', 'localhost');
    define('DB_NAME', '');
    define('DB_PORT', '3306');
}

#Path

define('BASE_URL', "http://" . $_SERVER['SERVER_NAME']. $_SERVER ['REQUEST_URI']);

#Error
ini_set('error_reporting', E_ALL);
ini_set('log_errors', TRUE);
ini_set('html_errors', TRUE);
ini_set('display_errors', TRUE);