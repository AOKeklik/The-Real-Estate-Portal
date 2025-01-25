<?php
    include "./layout_config.php";

    if(
        !isset($_GET["email"]) || 
        !isset($_GET["token"]) ||
        !filter_var($_GET["email"],FILTER_VALIDATE_EMAIL)
    ){
        header("Location: ".BASE_URL."404");
        exit();
    }
    
    $email = htmlspecialchars(trim($_GET["email"]));
    $token = htmlspecialchars(trim($_GET["token"]));

    try{
        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                subscribers
            WHERE
                email=?
            AND
                status=?
            LIMIT 
                1
        ");
        $stmt->execute([$email,0]);

        if($stmt->rowCount() == 0)
            throw new PDOException("The provided email is not registered.");

        $subscriber=$stmt->fetch(pdo::FETCH_ASSOC);
        if(!password_verify($subscriber["token"],$token))
            throw new PDOException("The provided email is not registered.");

        $stmt=$pdo->prepare("
            UPDATE
                subscribers
            SET
                status=?,
                token=?
            WHERE
                email=?
            LIMIT 
                1
        ");
        $stmt->execute([1,null,$email]);

        if($stmt->rowCount() == 0)
            throw new Exception("The mail could not be sent, please try again.");

        $_SESSION["success"]="Your email has been successfully subscribed to the newsletter.";
        header("Location: ".BASE_URL);
        exit();
    } catch(PDOException $err){
        $_SESSION["error"]=$err->getmessage();
        header("Location: ".BASE_URL);
        exit();
    }