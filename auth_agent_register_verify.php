<?php
    include "./layout_config.php";

    if(!isset($_GET["token"]) || !isset($_GET["email"])) {
        $_SESSION["error"] = "Invalid or missing email/token. Please try again!";
        header("Location: ".BASE_URL."agent-register");
        exit();
    }

    $token = htmlspecialchars(trim($_GET["token"]));
    $email = htmlspecialchars(trim($_GET["email"]));

    try {
        $sql = "select * from agents where email=:email limit 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":email",$email);
        $stmt->execute();
        $agent = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stmt->rowCount() == 0 || !password_verify($agent["token"], $token))
            throw new PDOException("No user found with the provided information.");

        $sql = "update agents set token=:token,status=:status where id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":token",NULL);
        $stmt->bindValue(":status",1);
        $stmt->bindValue(":id",$agent["id"]);
        $stmt->execute();

        if($stmt->rowCount() == 0)
            throw new PDOException("Something went wrong. Please try again later!");

        $_SESSION["success"] = "Registration verified successfully. Please login now!";
        header("Location: ".BASE_URL."agent-login");
        exit();
    } catch (PDOException $err) {
        $_SESSION["error"] = $err->getMessage();
        header("Location: ".BASE_URL."agent-register");
        exit();
    }
?>