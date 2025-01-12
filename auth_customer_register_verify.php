<?php
    include "./layout_config.php";

    if (isset($_SESSION["customer"])) {
        header("Location: ".BASE_URL."customer-dashboard");
        exit;
    }   

    if(!isset($_GET["token"]) || !isset($_GET["email"])) {
        $_SESSION["error"] = "Invalid or missing email/token. Please try again!";
        header("Location: ".BASE_URL."customer-login");
        exit;
    }

    $token = htmlspecialchars(trim($_GET["token"]));
    $email = htmlspecialchars(trim($_GET["email"]));

    try {
        $stmt = $pdo->prepare("
            select 
                *
            from 
                customers 
            where 
                email=:email 
            and 
                status=:status 
            limit 
                1
            ");
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":status", 0);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($stmt->rowCount() == 0 || !password_verify($customer["token"],$token)) {
            $_SESSION["error"] = "Invalid or missing email/token. Please try again!";
            header("Location: ".BASE_URL."customer-login");
            exit;
        }

        $sql = "update customers set token=:token,status=:status where id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":token", null);
        $stmt->bindValue(":status", 1);
        $stmt->bindValue(":id",$customer["id"]);
        $stmt->execute();

        if($stmt->rowCount() == 0) {
            $_SESSION["error"] = "Something went wrong. Please try again later.";
            header("Location: ".BASE_URL."customer-login");
            exit;
        }            

        $_SESSION["success"] = "Registration verified successfully. Please login now.";
        header("Location: ".BASE_URL."customer-login");
        exit;
    }catch(PDOException $err) {
        $error_message = "Something went wrong. Please try again later. ".$err->getMessage();
    }

    

        