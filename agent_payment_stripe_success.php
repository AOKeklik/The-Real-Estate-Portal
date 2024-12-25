<?php
    include "./layout_config.php";

    if(!isset($_SESSION["agent"])) {
        header("Location: ".BASE_URL."login");
        exit();
    }

    if (isset($_GET['session_id'])) {
        \Stripe\Stripe::setApiKey($_ENV["STRIPE_TEST_SK"]);
        $response = \Stripe\Checkout\Session::retrieve($_GET['session_id']);
        $paymentIntent = $response->payment_intent; // Transaction Id
        // $paymentIntent = bin2hex(random_bytes(32/2)); // Transaction Id

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
        $stmt->bindValue(":transaction_id",$paymentIntent);
        $stmt->bindValue(":payment_method",$_SESSION["order"]["payment_method"]);
        $stmt->bindValue(":paid_amount",$_SESSION["order"]["paid_amount"]);
        $stmt->bindValue(":status","Completed");
        $stmt->bindValue(":expire_date",$_SESSION["order"]["expire_date"]);
        $stmt->bindValue(":currently_active",1);
        $stmt->execute();

        unset($_SESSION["order"]);

        $_SESSION["success"] = "Payment is successful. Your transaction id is: ". $paymentIntent;
        header("Location: ".BASE_URL."agent-payment");
        exit();
    } else {
        $_SESSION["error"] = "Payment failed!";
        header("Location: ".BASE_URL."agent-payment");
        exit();
    }