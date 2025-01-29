<?php
    include "./layout_helper.php";
    include "./layout_config.php";
    include "./layout_header.php";

    if($current_page != "auth_login.php" && $current_page != "auth_forget.php") {
        include "./layout_sidebar.php";
        include "./layout_nav.php";
    }