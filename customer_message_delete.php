<?php
    include "./layout_config.php";

    try{
        if(
            !isset($_SESSION["customer"]) ||
            !isset($_POST["message-id"])
        )
            throw new PDOException("Message information is incorrect!");

        $message_id = htmlspecialchars(trim(base64_decode($_POST["message-id"])));

        if($message_id === "")
            throw new PDOException("Message information is incorrect!");


        $stmt=$pdo->prepare("
            DELETE FROM
                message_replies
            WHERE
                message_id=?
        ");
        $stmt->execute([$message_id]);

        if($stmt->rowCount() == 0)
            throw new PDOException("The related message could not be deleted. Please try again!");

        $stmt=$pdo->prepare("
            DELETE FROM
                messages
            WHERE
                id=?
        ");
        $stmt->execute([$message_id]);

        if($stmt->rowCount() == 0)
            throw new PDOException("The related message could not be deleted. Please try again!");

        echo json_encode(["success"=> ["message"=> "Message successfully deleted."]]);
    }catch(PDOException $err){
        echo json_encode(["error" => ["message" => $err->getMessage()]]);
    }