<?php
    include "./layout_helper.php";
    include "./layout_config.php";
    include "./layout_header.php";

    if($current_page != "auth_login.php" && $current_page != "auth_forget.php") {
        include "./layout_sidebar.php";
        include "./layout_nav.php";
    }


    include "./middleware_sessionMiddleware.php";
    use Middleware\SessionMiddleware; 

    if(isset($_SESSION["admin"])) {        
        SessionMiddleware::checkSession();   
    }