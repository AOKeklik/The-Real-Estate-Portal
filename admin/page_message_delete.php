<?php
    include "./layout_config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["message_id"])){

        try{
            $message_id=htmlspecialchars(trim(base64_decode($_POST["message_id"])));
            
            if(!isset($_SESSION["admin"]))
                throw new PDOException("You do not have permission to delete this message.");
            
            if($_POST["message_id"] === "")
                throw new PDOException("The specified message could not be found.");

            $stmt=$pdo->prepare("
                DELETE FROM
                    message_replies
                WHERE
                    message_id=?
            ");
            $stmt->execute([$message_id]);

            $stmt=$pdo->prepare("
                DELETE FROM
                    messages
                WHERE
                    id=?
                LIMIT
                    1
            ");
            $stmt->execute([$message_id]);

            if($stmt->rowCount() == 0)
                throw new PDOException("The specified message could not be found.");


            echo json_encode(["success"=>["message"=>"The message was successfully deleted."]]);
        }catch(PDOException $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }