<?php
    include "./layout_config.php";

    if(!isset($_SESSION["agent"])) {
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    unset($_SESSION["agent"]);
    $_SESSION["success"] = "You are logged out successfully!";
    header("Location: ".BASE_URL."agent-login");
    exit();
?>