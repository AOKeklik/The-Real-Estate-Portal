<?php
    include "./layout_config.php";
    

    if (!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }
    if(!isset($_GET["transaction_id"])) {
        header("Location: ".BASE_URL."agent-payment");
        exit();
    }

    $transaction_id = $_GET["transaction_id"];

    try {
        $stmt = $pdo->prepare("update orders set currently_active=? where agent_id=? and currently_active=?");
        $stmt->execute([0,$_SESSION["order"]["agent_id"],1]);

        $sql = "
            insert into orders
            (agent_id,package_id,transaction_id,payment_method,paid_amount,status,expire_date,currently_active)
            values (:agent_id,:package_id,:transaction_id,:payment_method,:paid_amount,:status,:expire_date,:currently_active)
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":agent_id",$_SESSION["order"]["agent_id"]);
        $stmt->bindValue(":package_id",$_SESSION["order"]["package_id"]);
        $stmt->bindValue(":transaction_id",$transaction_id);
        $stmt->bindValue(":payment_method",$_SESSION["order"]["payment_method"]);
        $stmt->bindValue(":paid_amount",$_SESSION["order"]["paid_amount"]);
        $stmt->bindValue(":status","Completed");
        $stmt->bindValue(":expire_date",$_SESSION["order"]["expire_date"]);
        $stmt->bindValue(":currently_active",1);
        $stmt->execute();

        if($stmt->rowCount() == 0)
            throw new PDOException("An error occurred during the payment process. Please try again later.");

        unset($_SESSION["order"]);

        $_SESSION["success"] = "Payment is successful. Your transaction id is: ". $transaction_id;
        header("Location: ".BASE_URL."agent-payment");
        exit();
    } catch (PDOException $err){
        $_SESSION["error"] = $err->getMessage();
        header("Location: ".BASE_URL."agent-payment");
        exit();
    }
?>