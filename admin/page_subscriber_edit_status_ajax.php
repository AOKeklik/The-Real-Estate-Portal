<?php
    include "./layout_config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["subscriber_id"]) && isset($_POST["status"])){
        try{
            $status=base64_decode($_POST["status"]);
            $subscriber_id=htmlspecialchars(trim(base64_decode($_POST["subscriber_id"])));

            if(!isset($_SESSION["admin"]))
                throw new Exception("You are not allowed to update this item.");

            if(filter_var($status,FILTER_VALIDATE_INT) === false)
                throw new Exception("The subscriber you want to update does not exist.");

            if(filter_var($subscriber_id,FILTER_VALIDATE_INT) === false)
                throw new Exception("The subscriber you want to update does not exist.");

            try{
                $stmt=$pdo->prepare("
                    UPDATE
                        subscribers
                    SET
                        status=?
                    WHERE
                        id=?
                    LIMIT 
                        1
                ");
                $stmt->execute([$status,$subscriber_id]);
                
                if($stmt->rowCount() == 0)
                    throw new Exception("The subscriber you want to update does not exist.");

                echo json_encode(["success"=>["message"=>"Subscriber updated successfully."]]);
            }catch(PDOException $err){
                echo json_encode(["error"=>["message"=>$err->getMessage()]]);
            }
            
        }catch(Exception $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }