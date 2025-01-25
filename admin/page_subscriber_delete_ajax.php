<?php
    include "./layout_config.php";


    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["subscriber_id"])) {
        try{
            $subscriber_id=htmlspecialchars(trim(base64_decode($_POST["subscriber_id"])));

            if(!isset($_SESSION["admin"]))
                throw new PDOException("You do not have permission to delete this subscriber.");

            if(filter_var($subscriber_id,FILTER_VALIDATE_INT) === false)
                throw new PDOException("The subscriber you are trying to delete could not be found.");

            try{
                $stmt=$pdo->prepare("
                    DELETE FROM 
                        subscribers
                    WHERE
                        id=?
                    LIMIT 
                        1
                ");
                $stmt->execute([$subscriber_id]);

                if($stmt->rowCount() == 0)
                    throw new PDOException("An error occurred while attempting to delete the subscriber.");

                echo json_encode(["success"=>["message"=>"The subscriber has been deleted successfully."]]);
            }catch(PDOException $err){
                echo json_encode(["error"=>["message"=>$err->getMessage()]]);
            }
        }catch(Exception $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }