<?php 
    include "./layout_config.php";

    unset($_SESSION["admin"]);
    header("Location: ".ADMIN_URL."login");
    exit();
?> 