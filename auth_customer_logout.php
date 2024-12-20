<?php

    include "./layout_config.php";

    unset($_SESSION["customer"]);
    $_SESSION["success"] = "You are logged out successfully!";
    header("Location: ".BASE_URL."customer-login");
    exit;
?>