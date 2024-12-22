<?php
    include "./layout_config.php";
    include "./layout_header.php";

    if($current_page != "auth_login.php") {
        include "./layout_sidebar.php";
        include "./layout_nav.php";
    }