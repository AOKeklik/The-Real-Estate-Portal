<?php

    ob_start();
    session_start();
    date_default_timezone_set("Europe/Warsaw");

    require_once 'vendor/autoload.php';
    use Dotenv\Dotenv;
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    define("APP_NAME", $_ENV["APP_NAME"]);
    define("BASE_URL", $_ENV["BASE_URL"]);
    define("ADMIN_URL", $_ENV["ADMIN_URL"]);
    define("PUBLIC_URL", $_ENV["PUBLIC_URL"]);
    define("MAX_POSTS_PER_PAGE", $_ENV["MAX_POSTS_PER_PAGE"]);

    define("DB_HOST", $_ENV["DB_HOST"]);
    define("DB_PORT", $_ENV["DB_PORT"]);
    define("DB_NAME", $_ENV["DB_NAME"]);
    define("DB_USER", $_ENV["DB_USER"]);
    define("DB_PASSWORD", $_ENV["DB_PASSWORD"]);

    define("SMTP_HOST", $_ENV["SMTP_HOST"]);
    define("SMTP_PORT", $_ENV["SMTP_PORT"]);
    define("SMTP_USERNAME", $_ENV["SMTP_USERNAME"]);
    define("SMTP_PASSWORD", $_ENV["SMTP_PASSWORD"]);
    define("SMTP_SECURE", $_ENV["SMTP_SECURE"]);
    define("SMTP_FROM", $_ENV["SMTP_FROM"]);
        

    try {  
        $pdo = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASSWORD);
        $pdo->setAttribute(pdo::ATTR_ERRMODE,pdo::ERRMODE_EXCEPTION);
    } catch (PDOException $err) {
        $error_message = $err->getMessage();
    }

    $current_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    global $current_page;
    global $pdo;

    include "./provider_setting.php";
    include "./provider_location.php";
    ProviderSetting::load($pdo);
    ProviderLocation::load($pdo);
