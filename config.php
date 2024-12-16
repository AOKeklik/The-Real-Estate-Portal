<?php
    ob_start();
    session_start();
    date_default_timezone_set("Europe/Warsaw");


    try {
        $host = "127.0.0.1";
        $port = "3306";
        $dbname = "the_real_estate_portal";
        $user = "root";
        $pass = "";

        $pdo = new PDO ("mysql:host=$host;dbname=$dbname;port=$port;charset=utf8;", $user, $pass);
        $pdo->setAttribute(pdo::ATTR_ERRMODE,pdo::ERRMODE_EXCEPTION);
    } catch (PDOException $err) {
        echo "Pdo: ".$err->getMessage();
    }	

    define("APP_NAME", "The-Real-Estate-Portal");

    define("FRONTEND_URL", "http://localhost/The-Real-Estate-Portal/");
    define("ADMIN_URL", FRONTEND_URL."admin/");
    define("PUBLIC_URL", FRONTEND_URL."public/");

    $current_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
    global $pdo;
    global $current_page;