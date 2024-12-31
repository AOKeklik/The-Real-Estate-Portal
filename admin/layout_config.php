<?php
    ob_start();
    session_start();
    date_default_timezone_set("Europe/Warsaw");

    define("APP_NAME", "The-Real-Estate-Portal");

    define("BASE_URL", "http://localhost/The-Real-Estate-Portal/");
    define("ADMIN_URL", BASE_URL."admin/");
    define("PUBLIC_URL", BASE_URL."public/");

    define("DB_HOST", "127.0.0.1");
    define("DB_PORT", "3306");
    define("DB_NAME", "the_real_estate_portal");
    define("DB_USER", "root");
    define("DB_PASSWORD", "");

    define("SMTP_HOST", "sandbox.smtp.mailtrap.io");
    define("SMTP_PORT", "2525");
    define("SMTP_USERNAME", "a227fb6c1991fc");
    define("SMTP_PASSWORD", "0183d80c52a3ba");
    define("SMTP_SECURE", "tsl");
    define("SMTP_FROM", "contact@mail.com");

    try {
        $pdo = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=utf8;",DB_USER,DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $err) {
        $error_message = $err->getMessage();
    }

    $current_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    global $pdo;
    global $current_page;