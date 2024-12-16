<?php include "../helpers.php"?>
<?php include "../config.php"?>
<?php include "./layout/header.php"?>

<?php if($current_page != "auth_login.php" && $current_page != "auth_forget.php" && $current_page != "auth_reset.php"):?>
    <?php if(!Auth::isLoggedIn()) Redirect::route("auth_login.php")->with("error","Access denied. Please log in.")?>
    <?php include "./layout/nav.php"?>
    <?php include "./layout/sidebar.php"?>
<?php else:?>
    <?php if(Auth::isLoggedIn()) Redirect::route("dashboard.php")->with() ?>
<?php endif?>
