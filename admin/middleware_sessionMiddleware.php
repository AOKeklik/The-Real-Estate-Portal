<?php 
    namespace Middleware;

    class SessionMiddleware{
        static function checkSession(){
            $session_timeout = $_ENV["SESSION_TIMEOUT"] ?? 60;

            if(isset($_SESSION["last_activity"]) && (time() - $_SESSION["last_activity"]) > $session_timeout) {
                session_unset();
                session_destroy();
                header("Location: ".ADMIN_URL."login");
                exit();
            }
            
            $_SESSION["last_activity"]=time();
        }
    }