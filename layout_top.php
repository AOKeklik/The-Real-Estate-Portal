<?php

    include "./layout_config.php";
    include "./layout_header.php";
    include "./layout_nav.php";

    include "./middleware_sessionMiddleware.php";
    use Middleware\SessionMiddleware; 

    if(isset($_SESSION["customer"]) || isset($_SESSION["agent"])) {        
        SessionMiddleware::checkSession();   
    }